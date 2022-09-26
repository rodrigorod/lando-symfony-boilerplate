<?php

/*
 * This file is part of the SymfonyCasts ResetPasswordBundle package.
 * Copyright (c) SymfonyCasts <https://symfonycasts.com/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security\Command;

use App\Security\Util\TokenCleaner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * RemoveExpiredTokenCommand class.
 */
class RemoveExpiredTokenCommand extends Command
{
    protected static $defaultName = 'security:token:remove-expired';

    private TokenCleaner $cleaner;

    public function __construct(TokenCleaner $cleaner)
    {
        $this->cleaner = $cleaner;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setDescription('Remove expired reset password requests from persistence.');
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress InvalidReturnType
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Removing expired reset password requests...');

        $intRemoved = $this->cleaner->handleGarbageCollection(true);

        $output->writeln(\sprintf('Garbage collection successful. Removed %s reset password request object(s).', $intRemoved));

        return 0;
    }
}
