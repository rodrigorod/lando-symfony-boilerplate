<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use App\Security\Helper\TokenHelperInterface;

/**
 * Trait TokenHelperAwareTrait.
 */
trait TokenHelperAwareTrait
{
    /**
     * User service.
     */
    protected TokenHelperInterface $tokenHelper;

    /**
     * Set user service.
     *
     * @param TokenHelperInterface $tokenHelper
     *   User service
     *
     * @required
     */
    public function setTokenHelper(TokenHelperInterface $tokenHelper): void
    {
        $this->tokenHelper = $tokenHelper;
    }
}
