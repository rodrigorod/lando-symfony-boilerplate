<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use Psr\Log\LoggerInterface;

/**
 * Trait LoggerAwareTrait.
 */
trait LoggerAwareTrait
{
    /**
     * LoggerInterface.
     */
    protected LoggerInterface $logger;

    /**
     * Set LoggerInterface.
     *
     * @param LoggerInterface $logger
     *  LoggerInterface
     *
     * @required
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
