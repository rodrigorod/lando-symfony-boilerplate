<?php

namespace App\Api\User\Controller;

use App\Api\User\Entity\Profile;
use App\Api\User\Entity\User;
use App\Controller\EndpointController;
use App\DependencyInjection\EntityManagerAwareTrait;
use App\DependencyInjection\LoggerAwareTrait;
use App\DependencyInjection\SecurityAwareTrait;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user/profile", name="api_user_profile_")
 */
class ProfileController extends EndpointController
{
    use SecurityAwareTrait;
    use LoggerAwareTrait;
    use EntityManagerAwareTrait;

    /**
     * @Route(name="create", methods={"POST"}, priority=10)
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
     */
    public function getExternal(User $user, Request $request): Response
    {
        if (null === $user->getProfile()) {
            return $this->buildNotFoundResponse('User has no profile.');
        }

        return $this->buildEntityResponse($user, $request, [], ['profile', 'garage']);
    }
}
