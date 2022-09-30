<?php

namespace App\Api\User\Controller;

use App\Api\User\Entity\Profile;
use App\Api\User\Entity\User;
use App\Controller\EndpointController;
use App\DependencyInjection\SecurityAwareTrait;
use App\Security\Event\RegistrationRequestEvent;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/api/user", name="api_user_")
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
     */
    public function register(Request $request): Response
    {
        try {
            $user = $this->userService->registerUser($this->getContentFromRequest($request));
        } catch (Exception $e) {
            $this->logger->error(sprintf('UserController::activate - %s', $e->getMessage()));

            return $this->buildNotFoundResponse(sprintf('An error occurred %s', $e->getMessage()));
        }

        $this->eventDispatcher->dispatch(
            new RegistrationRequestEvent($user),
            RegistrationRequestEvent::NAME
        );

        $this->logger->info(sprintf('UserController::register - User %s registered', $user->getUserIdentifier()));

        return $this->buildEntityResponse($user, $request, [], ['user']);
    }

    /**
     * @Route("/{username}", name="get", priority=-100)
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function get(User $user, Request $request): Response
    {
        return $this->buildEntityResponse($user, $request, [], ['user']);
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

