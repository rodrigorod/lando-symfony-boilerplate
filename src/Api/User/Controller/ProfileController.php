<?php

namespace App\Api\User\Controller;

use App\Api\User\Entity\Profile;
use App\Api\User\Entity\User;
use App\Controller\EndpointController;
use App\DependencyInjection\EntityManagerAwareTrait;
use App\DependencyInjection\LoggerAwareTrait;
use App\DependencyInjection\SecurityAwareTrait;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user/profile", name="api_user_profile_")
 *
 * @OA\Tag(
 *     name="User",
 *     description="User informations.",
 * )
 */
class ProfileController extends EndpointController
{
    use SecurityAwareTrait;
    use LoggerAwareTrait;
    use EntityManagerAwareTrait;

    /**
     * @Route(name="create", methods={"POST"}, priority=10)
     *
     * @OA\Post(
     *     operationId="profileCreate",
     *     summary="Create user profile.",
     *     path="/api/user/profile/create",
     *     @OA\Response(
     *          response="200",
     *          description="User.",
     *          @OA\JsonContent(ref=@Model(type=User::class))
     *     ),
     *     @OA\Response(response="404", description="E-mail must be confirmed in order to create profile."),
     *     @OA\Response(response="404", description="User already has a profile."),
     * )
     *
     * @Security(name="Bearer")
     */
    public function create(Request $request): Response
    {
        $requestContent = (array) $this->getContentFromRequest($request, true);
        $user = $this->getUser();

        if (!$user->isActive()) {
            return $this->buildNotFoundResponse('E-mail must be confirmed in order to create profile.');
        }

        if (null !== $user->getProfile()) {
            return $this->buildNotFoundResponse('User already has a profile.');
        }

        $profile = new Profile($requestContent['image'], $requestContent['firstName'], $requestContent['lastName']);

        $user->setProfile($profile);

        try {
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('UserController::createProfile - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('UserController::createProfile - User %s profile created.', $user->getUserIdentifier()));

        return $this->buildEntityResponse($user, $request);
    }

    /**
     * @Route(name="get", methods={"GET"}, priority=10)
     *
     * @OA\Get(
     *     operationId="profileGet",
     *     summary="Get user own profile.",
     *     path="/api/user/profile/get",
     *     @OA\Response(
     *         response="200",
     *         description="Profile.",
     *         @OA\JsonContent(ref=@Model(type=Profile::class)),
     *     ),
     *     @OA\Response(response="404", description="User has no profile."),
     * )
     *
     * @Security(name="Bearer")
     */
    public function get(Request $request): Response
    {
        $user = $this->getUser();

        if (null === $user->getProfile()) {
            return $this->buildNotFoundResponse('User has no profile.');
        }

        return $this->buildEntityResponse($user->getProfile(), $request);
    }

    /**
     * @Route("/{username}", name="external", methods={"GET"})
     *
     * @IsGranted("PUBLIC_ACCESS")
     *
     * @OA\Get(
     *     operationId="profileExternalGet",
     *     summary="Get external user profile.",
     *     path="/api/user/profile/{username}",
     *     @OA\Parameter(
     *          name="username",
     *          in="path",
     *          description="User username.",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              example="johndoe",
     *          ),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="User",
     *          @OA\JsonContent(ref=@Model(type=User::class, groups={"profile", "garage"})),
     *     ),
     *     @OA\Response(response="404", description="User has no profile."),
     * )
     */
    public function getExternal(User $user, Request $request): Response
    {
        if (null === $user->getProfile()) {
            return $this->buildNotFoundResponse('User has no profile.');
        }

        return $this->buildEntityResponse($user, $request, [], ['profile', 'garage']);
    }
}
