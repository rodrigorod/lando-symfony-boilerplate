<?php

namespace App\Api\User\Controller;

use App\Controller\EndpointController;
use App\Security\Event\RegistrationRequestEvent;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @Route("/api/user", name="api_user_")
 */
class UserController extends EndpointController
{
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
     */
    public function register(Request $request): Response
    {
        $user = $this->userService->registerUser($this->getContentFromRequest($request));

        $this->eventDispatcher->dispatch(
            new RegistrationRequestEvent($user),
            RegistrationRequestEvent::NAME
        );

        $this->logger->info(sprintf('UserController::register - User %s registered', $user->getUserIdentifier()));

        return $this->buildEntityResponse($user, $request);
    }

    /**
     * @Route("/activate", name="activate", methods={"POST"})
     *
     * @IsGranted("PUBLIC_ACCESS")
     */
    public function activate(Request $request): Response
    {
        if (!$request->headers->has('X-Token')) {
            throw new AuthenticationException('Secured token is missing', Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->userService->activateUser(
            $request->headers->get('X-Token'),
        );

        $this->logger->info(sprintf('UserController::activate - User %s successfully activated', $user->getUserIdentifier()));

        return $this->buildEntityResponse($user, $request);
    }
}
