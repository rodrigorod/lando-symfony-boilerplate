<?php

namespace App\Security\Model;

/**
 * TokenComponents class.
 */
class TokenComponents
{
    /**
     * @var string
     */
    private string $selector;

    /**
     * @var string
     */
    private string $verifier;

    /**
     * @var string
     */
    private string $hashedToken;

    public function __construct(string $selector, string $verifier, string $hashedToken)
    {
        $this->selector = $selector;
        $this->verifier = $verifier;
        $this->hashedToken = $hashedToken;
    }

    /**
     * @return string Non-hashed random string used to fetch request objects from persistence
     */
    public function getSelector(): string
    {
        return $this->selector;
    }

    /**
     * @return string The hashed non-public token used to validate reset password requests
     */
    public function getHashedToken(): string
    {
        return $this->hashedToken;
    }

    /**
     * The public token consists of a concatenated random non-hashed selector string and random non-hashed verifier string.
     */
    public function getPublicToken(): string
    {
        return $this->selector.$this->verifier;
    }
}
