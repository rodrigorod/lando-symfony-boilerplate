<?php

namespace App\Security\Util;

use App\Security\Repository\TokenRequestRepositoryInterface;

/**
 * TokenCleaner class.
 *
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 */
class TokenCleaner
{
    /**
     * @var bool Enable/disable garbage collection
     */
    private bool $enabled;

    private TokenRequestRepositoryInterface $repository;

    public function __construct(TokenRequestRepositoryInterface $repository, bool $enabled = true)
    {
        $this->repository = $repository;
        $this->enabled = $enabled;
    }

    /**
     * Clears expired reset password requests from persistence.
     *
     * Enable/disable in configuration. Calling with $force = true
     * will attempt to remove expired requests regardless of
     * configuration setting.
     */
    public function handleGarbageCollection(bool $force = false): int
    {
        if ($this->enabled || $force) {
            return $this->repository->removeExpiredTokenRequests();
        }

        return 0;
    }
}
