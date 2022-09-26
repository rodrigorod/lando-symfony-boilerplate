<?php

namespace App\EventSubscriber;

use App\Api\User\Entity\UserInterface;
use App\DependencyInjection\MailManagerAwareTrait;
use App\DependencyInjection\TokenHelperAwareTrait;
use App\Security\Event\RegistrationRequestEvent;
use App\Security\Exception\TokenExceptionInterface;
use MongoDB\Driver\Exception\AuthenticationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * UserRegistrationEventSubscriber Class.
 *
 * Handles event related to registration process, including activation.
 */
class UserRegistrationEventSubscriber implements EventSubscriberInterface
{
    use MailManagerAwareTrait;
    use TokenHelperAwareTrait;

    public function __construct(
        protected UrlGeneratorInterface $generator
    ) {
    }

    /**
     * User has sent a registration request.
     */
    public function onUserRegistrationRequest(RegistrationRequestEvent $event): void
    {
        $this->sendActivationEmail($event->getUser());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RegistrationRequestEvent::NAME => 'onUserRegistrationRequest',
        ];
    }

    /**
     * Send Activation mail to user with secured token.
     *
     * @param UserInterface $user
     *  User
     * @param bool $reset
     *  Whether mail is about first activation or activation reset
     */
    private function sendActivationEmail(UserInterface $user, bool $reset = false): void
    {
        if (false !== $user->isActive()) {
            return;
        }

        if ($reset) {
            $this->tokenHelper->removeUserTokenRequests($user);
        }

        try {
            $token = $this->tokenHelper->generateToken($user);
        } catch (TokenExceptionInterface $e) {
            throw new AuthenticationException(
                sprintf('Issue with token generation - %s', $e->getReason()),
                Response::HTTP_UNAUTHORIZED
            );
        }

        // send activation e-mail
        $this->mailManager->send(
            $user->getEmail(),
            [
                'headers' => ['X-Token' => $token->getToken()],
                'title' => 'Carmeet - Confirmez votre inscription.',
                'message' => 'Veuillez confirmez votre inscription pour valider votre compte.',
                'url' => $this->generator->generate('api_user_activate', ['X-Token' => $token->getToken()], UrlGeneratorInterface::ABSOLUTE_URL),
            ],
            '@emails/registration.html.twig',
        );
    }
}
