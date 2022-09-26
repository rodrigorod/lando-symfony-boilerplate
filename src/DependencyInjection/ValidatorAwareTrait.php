<?php

namespace App\DependencyInjection;

use App\Exception\ConstraintViolationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ValidatorAwareTrait
{
    /**
     * Validator.
     */
    protected ValidatorInterface $validator;

    /**
     * Set validator.
     *
     * @param ValidatorInterface $validator
     *  Validator
     *
     * @required
     */
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    public function validateEntity(object $entity, $constraints = null, $groups = null): void
    {
        $violations = $this->validator->validate($entity, $constraints, $groups);

        if ($violations->count() > 0) {
            throw new ConstraintViolationException(
                sprintf('Entity failed validation (%s violations).', $violations->count()),
                $violations
            );
        }
    }
}
