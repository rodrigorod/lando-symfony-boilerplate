<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ConstraintViolationException extends HttpException implements ConstraintViolationExceptionInterface
{
    /**
     * Constraint violation list.
     *
     * @var ConstraintViolationListInterface<ConstraintViolationInterface>
     */
    protected ConstraintViolationListInterface $violations;

    /**
     * ConstraintViolationException constructor.
     *
     * @param null|string $message
     *  Message
     * @param ConstraintViolationListInterface<ConstraintViolationInterface> $violations
     *  Constraint violation list
     * @param null|Throwable $previous
     *  Previous exception
     * @param int $code
     *  Exception code
     * @param array<string> $headers
     *  Exception headers
     */
    public function __construct(string $message = null, ConstraintViolationListInterface $violations, Throwable $previous = null, int $code = 0, array $headers = [])
    {
        $this->violations = $violations;

        parent::__construct(Response::HTTP_BAD_REQUEST, $message, $previous, $headers, $code);
    }

    /**
     * {@inheritDoc}
     */
    public function getViolations()
    {
        return $this->violations;
    }

    /**
     * {@inheritDoc}
     */
    public function getFormattedViolation(): array
    {
        $formattedViolations = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($this->violations as $violation) {
            $formattedViolations[] = [
                'path' => $violation->getPropertyPath(),
                'type' => $this->getViolationType($violation),
                'message' => $violation->getMessage(),
            ];
        }

        return $formattedViolations;
    }

    /**
     * Return a violation type (class name).
     * Caller may use this type in order to override messages.
     */
    private function getViolationType(ConstraintViolationInterface $violation): ?string
    {
        if (!($violation instanceof ConstraintViolation)) {
            return null;
        }

        $constraintPath = explode('\\', (string) get_class($violation->getConstraint()));

        return lcfirst(end($constraintPath));
    }
}
