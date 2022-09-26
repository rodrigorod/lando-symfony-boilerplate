<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use App\Mailer\MailManagerInterface;

/**
 * Trait MailManagerAwareTrait.
 */
trait MailManagerAwareTrait
{
    /**
     * Mail manager.
     */
    protected MailManagerInterface $mailManager;

    /**
     * Set mail manager.
     *
     * @param MailManagerInterface $mailManager
     *   Mail manager
     *
     * @required
     */
    public function setMailManager(MailManagerInterface $mailManager): void
    {
        $this->mailManager = $mailManager;
    }
}
