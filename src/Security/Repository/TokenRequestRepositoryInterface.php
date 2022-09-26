<?php

namespace App\Security\Repository;

use App\Security\Entity\TokenRequestInterface;

/**
 * TokenRequestRepository Interface.
 */
interface TokenRequestRepositoryInterface
{
    /**
     * Create a new TokenRequest object.
     *
     * @param object $user        User entity - typically implements Symfony\Component\Security\Core\User\UserInterface
     * @param string $selector    A non-hashed random string used to fetch a request from persistence
     * @param string $hashedToken The hashed token used to verify a reset request
     */
    public function createTokenRequest(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken): TokenRequestInterface;

    /**
     * Get the unique user entity identifier from persistence.
     *
     * @param object $user User entity - typically implements Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUserIdentifier(object $user): string;

    /**
     * Save a reset password request entity to persistence.
     */
    public function persistTokenRequest(TokenRequestInterface $resetPasswordRequest): void;

    /**
     * Get a reset password request entity from persistence, if one exists, using the request's selector.
     *
     * @param string $selector A non-hashed random string used to fetch a request from persistence
     */
    public function findTokenRequest(string $selector): ?TokenRequestInterface;

    /**
     * Get the most recent non-expired reset password request date for the user, if one exists.
     *
     * @param object $user User entity - typically implements Symfony\Component\Security\Core\User\UserInterface
     */
    public function getMostRecentNonExpiredRequestDate(object $user): ?\DateTimeInterface;

    /**
     * Remove this reset password request from persistence and any other for this user.
     *
     * This method should remove this TokenRequestInterface and also all
     * other TokenRequestInterface objects in storage for the same user.
     */
    public function removeTokenRequest(object $user): void;

    /**
     * Remove all expired reset password request objects from persistence.
     *
     * @return int Number of request objects removed from persistence
     */
    public function removeExpiredTokenRequests(): int;
}
