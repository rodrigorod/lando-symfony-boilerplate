<?php

namespace App\Security\Exception;

/**
 * InvalidTokenException.
 */
final class InvalidTokenException extends \Exception implements TokenExceptionInterface
{
    public function getReason(): string
    {
        return 'The secured link is invalid. Please try again.';
    }
}
