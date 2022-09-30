<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Trait EntityManagerAwareTrait.
 */
trait EntityManagerAwareTrait
{
    /**
     * EntityManagerInterface.
     */
    protected EntityManagerInterface $em;

    /**
     * Set EntityManagerInterface.
     *
     * @param EntityManagerInterface $em
     *  EntityManagerInterface
     *
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }
}
