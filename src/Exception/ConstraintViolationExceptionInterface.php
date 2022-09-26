<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ConstraintViolationExceptionInterface
{
    /**
     * Get constraint violation list.
     *
     * @return ConstraintViolationList<ConstraintViolationInterface>
     */
    public function getViolations();

    /**
     * Format constraint violation list.
     */
    public function getFormattedViolation(): array;
}
