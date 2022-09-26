<?php

namespace App\Security\Exception;

/**
 * TokenExceptionInterface.
 */
interface TokenExceptionInterface extends \Throwable
{
    public function getReason(): string;
}
