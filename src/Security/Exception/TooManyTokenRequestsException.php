<?php

namespace App\Security\Exception;

use DateTime;
use DateTimeInterface;

/**
 * @author Ryan Weaver <ryan@symfonycasts.com>
 */
final class TooManyTokenRequestsException extends \Exception implements TokenExceptionInterface
{
    private DateTimeInterface $availableAt;

    public function __construct(DateTimeInterface $availableAt, string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->availableAt = $availableAt;
    }

    public function getAvailableAt(): DateTimeInterface
    {
        return $this->availableAt;
    }

    public function getRetryAfter(): int
    {
        return $this->getAvailableAt()->getTimestamp() - (new DateTime('now'))->getTimestamp();
    }

    public function getReason(): string
    {
        return 'There is already a pending secured request. Please check your email or try again soon.';
    }
}
