<?php

namespace App\Security\Helper;

use App\Security\Exception\TokenExceptionInterface;
use App\Security\Model\Token;

/**
 * TokenHelperInterface.
 */
interface TokenHelperInterface
{
    /**
     * Generate a new Token that can be provided to the user.
     *
     * This method must also persist the token information to storage so that
     * the validateTokenAndFetchUser() method can verify the token validity
     * and removeTokenRequest() can eventually invalidate it by removing it
     * from storage.
     *
     * @throws TokenExceptionInterface
     */
    public function generateToken(object $user): Token;

    /**
     * Validate a reset request and fetch the user from persistence.
     *
     * The token provided to the user from generateToken() is validated
     * against a token stored in persistence. If the token cannot be validated,
     * a TokenExceptionInterface instance should be thrown.
     *
     * @param string $fullToken selector string + verifier string provided by the user
     *
     * @throws TokenExceptionInterface
     */
    public function validateTokenAndFetchUser(string $fullToken): object;

    /**
     * Remove a password reset request token from persistence.
     *
     * Intended to be used after validation - this will typically remove
     * the token from storage so that it can't be used again.
     *
     * @param string $fullToken selector string + verifier string provided by the user
     */
    public function removeTokenRequest(string $fullToken): void;

    /**
     * Remove all user's token requests.
     */
    public function removeUserTokenRequests(object $user): void;

    /**
     * Get the length of time in seconds a token is valid.
     */
    public function getTokenLifetime(): int;
}
