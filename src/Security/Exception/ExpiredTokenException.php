<?php

namespace App\Security\Exception;

/**
 * ExpiredTokenException.
 */
final class ExpiredTokenException extends \Exception implements TokenExceptionInterface
{
    public function getReason(): string
    {
        return 'The link in your email is expired. Please try to reset your password again.';
    }
}
