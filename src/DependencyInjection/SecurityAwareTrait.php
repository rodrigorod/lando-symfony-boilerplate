<?php

namespace App\DependencyInjection;

use App\Api\User\Entity\UserInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

/**
 * Trait SecurityAwareTrait.
 */
trait SecurityAwareTrait
{
    /**
     * Security service.
     */
    protected Security $security;

    /**
     * Set security.
     *
     * @param Security $security
     *  Security service
     *
     * @required
     */
    public function setSecurity(Security $security): void
    {
        $this->security = $security;
    }

    /**
     * Get the security token.
     *
     * @return TokenInterface
     *  Security Token
     */
    protected function getToken(): TokenInterface
    {
        $token = $this->security->getToken();

        if (isset($token) && $token instanceof TokenInterface) {
            return $token;
        }

        throw new InvalidArgumentException('The token is invalid.');
    }

    /**
     * Get the logged-in user.
     *
     * @return UserInterface
     *  User instance
     */
    protected function getUser(): UserInterface
    {
        $user = $this->security->getUser();

        if (isset($user) && $user instanceof UserInterface) {
            return $user;
        }

        throw new InvalidArgumentException('The JWT user is not valid.');
    }

    /**
     * Get the logged-in user or null.
     *
     * @return null|UserInterface
     *  User instance
     */
    protected function getUserOrNull(): ?UserInterface
    {
        $user = $this->security->getUser();

        if (isset($user) && $user instanceof UserInterface) {
            return $user;
        }

        return null;
    }

    /**
     * Check if a user is correctly authenticated or throws an exception.
     *
     * @param mixed $attributes
     *  Attributes to check against like a permission
     * @param mixed null|$subject
     *  A specific subject to check against
     * @param string $message
     *  Error message
     */
    protected function denyAccessUnlessGranted(mixed $attributes, mixed $subject = null, string $message = 'Access restricted to authenticated users.'): void
    {
        if (!$this->security->isGranted($attributes, $subject)) {
            throw new AuthenticationException($message, Response::HTTP_FORBIDDEN);
        }
    }
}
