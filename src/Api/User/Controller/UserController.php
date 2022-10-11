<?php

namespace App\Api\User\Controller;

use App\Api\User\Entity\User;
use App\Controller\EndpointController;
use App\DependencyInjection\SecurityAwareTrait;
use App\Security\Event\RegistrationRequestEvent;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @Route("/api/user", name="api_user_")
 *
 * @OA\Tag(
 *     name="User",
 *     description="User informations.",
 * )
 */
class UserController extends EndpointController
{
    use SecurityAwareTrait;

    public function __construct(
        protected EntityManagerInterface $em,
        protected EventDispatcherInterface $eventDispatcher,
        protected UserService $userService,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * @Route("/register", name="register", methods={"POST"})
     *
     * @IsGranted("PUBLIC_ACCESS")
     *
     * @OA\Post(
     *     operationId="userRegister",
     *     summary="Register a new User.",
     *     path="/api/user/register",
     *     @OA\Response(
     *          response="200",
     *          description="User",
     *          @OA\JsonContent(ref=@Model(type=User::class, groups={"user"}))
     *     ),
     *     @OA\Response(response="404", description="An error occurred."),
     * )
     *
     * @Security(name="Bearer")
     */
    public function register(Request $request): Response
    {
        try {
            $user = $this->userService->registerUser($this->getContentFromRequest($request));
        } catch (Exception $e) {
            $this->logger->error(sprintf('UserController::activate - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->eventDispatcher->dispatch(
            new RegistrationRequestEvent($user),
            RegistrationRequestEvent::NAME
        );

        $this->logger->info(sprintf('UserController::register - User %s registered', $user->getUserIdentifier()));

        return $this->buildEntityResponse($user, $request, [], ['user']);
    }

    /**
     * @Route("/{username}", name="get", priority=-100, methods={"GET"})
     *
     * @OA\Get(
     *     operationId="userGet",
     *     summary="Get all user informations.",
     *     path="/api/user/{username}",
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
     *          @OA\JsonContent(ref=@Model(type=User::class, groups={"user"})),
     *     ),
     *     @OA\Response(response="401", description="Access denied."),
     *     @OA\Response(response="404", description="An error occurred."),
     * )
     *
     * @Security(name="Bearer")
     */
    public function get(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->buildEntityResponse($user, $request, [], ['user']);
    }

    /**
     * @Route("/activate", name="activate", methods={"POST"})
     *
     * @IsGranted("PUBLIC_ACCESS")
     *
     * @OA\Post(
     *     operationId="userActivate",
     *     summary="Activate user from confirmation e-mail.",
     *     path="/api/user/activate",
     *     @OA\Response(
     *          response="200",
     *          description="User",
     *          @OA\JsonContent(ref=@Model(type=User::class, groups={"user"})),
     *     ),
     *     @OA\Response(response="404", description="An error occurred."),
     * )
     */
    public function activate(Request $request): Response
    {
        if (!$request->headers->has('X-Token')) {
            throw new AuthenticationException('Secured token is missing', Response::HTTP_UNAUTHORIZED);
        }

        try {
            $user = $this->userService->activateUser(
                $request->headers->get('X-Token'),
            );
        } catch (Exception $e) {
            $this->logger->error(sprintf('UserController::activate - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred');
        }

        $this->logger->info(sprintf('UserController::activate - User %s successfully activated', $user->getUserIdentifier()));

        return $this->buildEntityResponse($user, $request, [], ['user']);
    }
}
