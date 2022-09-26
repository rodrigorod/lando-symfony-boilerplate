<?php

namespace App\Security\Entity;

/**
 * TokenRequest Interface.
 */
interface TokenRequestInterface
{
    /**
     * Get the time the reset password request was created.
     */
    public function getRequestedAt(): \DateTimeInterface;

    /**
     * Check if the reset password request is expired.
     */
    public function isExpired(): bool;

    /**
     * Get the time the reset password request expires.
     */
    public function getExpiresAt(): \DateTimeInterface;

    /**
     * Get the non-public hashed token used to verify a request password request.
     */
    public function getHashedToken(): string;

    /**
     * Get the user whom requested a password reset.
     */
    public function getUser(): object;
}
