<?php

namespace App\Security\Generator;

use App\Security\Model\TokenComponents;
use RuntimeException;

/**
 * @author Jesse Rushlow <jr@rushlow.dev>
 * @author Ryan Weaver   <ryan@symfonycasts.com>
 *
 * @internal
 * @final
 */
class TokenGenerator
{
    /**
     * @var string Unique, random, cryptographically secure string
     */
    private $signingKey;

    /**
     * @var RandomGenerator
     */
    private $randomGenerator;

    public function __construct(string $signingKey, RandomGenerator $generator)
    {
        $this->signingKey = $signingKey;
        $this->randomGenerator = $generator;
    }

    /**
     * Get a cryptographically secure token with it's non-hashed components.
     *
     * @param mixed  $userId   Unique user identifier
     * @param string $verifier Only required for token comparison
     */
    public function createToken(\DateTimeInterface $expiresAt, $userId, string $verifier = null): TokenComponents
    {
        if (null === $verifier) {
            $verifier = $this->randomGenerator->getRandomAlphaNumStr();
        }

        $selector = $this->randomGenerator->getRandomAlphaNumStr();

        $encodedData = \json_encode([$verifier, $userId, $expiresAt->getTimestamp()]);

        if (!$encodedData) {
            throw new RuntimeException('Unable to encode data for secured token generation.');
        }

        return new TokenComponents(
            $selector,
            $verifier,
            $this->getHashedToken($encodedData)
        );
    }

    private function getHashedToken(string $data): string
    {
        return \base64_encode(\hash_hmac('sha256', $data, $this->signingKey, true));
    }
}
