<?php

namespace App\Security\Helper;

use App\Security\Entity\TokenRequestInterface;
use App\Security\Exception\ExpiredTokenException;
use App\Security\Exception\InvalidTokenException;
use App\Security\Exception\TooManyTokenRequestsException;
use App\Security\Generator\TokenGenerator;
use App\Security\Model\Token;
use App\Security\Repository\TokenRequestRepositoryInterface;
use App\Security\Util\TokenCleaner;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * @author Jesse Rushlow <jr@rushlow.dev>
 * @author Ryan Weaver   <ryan@symfonycasts.com>
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class TokenHelper implements TokenHelperInterface
{
    /**
     * The first 20 characters of the token are a "selector".
     */
    private const SELECTOR_LENGTH = 20;

    private TokenGenerator $tokenGenerator;
    private TokenCleaner $tokenCleaner;
    private TokenRequestRepositoryInterface $repository;

    /**
     * @var int How long a token is valid in seconds
     */
    private int $tokenRequestLifetime;

    /**
     * @var int Another password reset cannot be made faster than this throttle time in seconds
     */
    private int $requestThrottleTime;

    public function __construct(
        TokenGenerator $generator,
        TokenCleaner $cleaner,
        TokenRequestRepositoryInterface $repository,
        int $tokenRequestLifetime,
        int $requestThrottleTime
    ) {
        $this->tokenGenerator = $generator;
        $this->tokenCleaner = $cleaner;
        $this->repository = $repository;
        $this->tokenRequestLifetime = $tokenRequestLifetime;
        $this->requestThrottleTime = $requestThrottleTime;
    }

    /**
     * {@inheritdoc}
     *
     * Some of the cryptographic strategies were taken from
     * https://paragonie.com/blog/2017/02/split-tokens-token-based-authentication-protocols-without-side-channels
     *
     * @throws TooManyTokenRequestsException
     */
    public function generateToken(object $user): Token
    {
        $this->tokenCleaner->handleGarbageCollection();

        $availableAt = $this->hasUserHitThrottling($user);

        if ($availableAt) {
            throw new TooManyTokenRequestsException($availableAt);
        }

        $expiresAt = new DateTimeImmutable(sprintf('+%d seconds', $this->tokenRequestLifetime));

        $tokenComponents = $this->tokenGenerator->createToken($expiresAt, $this->repository->getUserIdentifier($user));

        $passwordResetRequest = $this->repository->createTokenRequest(
            $user,
            $expiresAt,
            $tokenComponents->getSelector(),
            $tokenComponents->getHashedToken()
        );

        $this->repository->persistTokenRequest($passwordResetRequest);

        // final "public" token is the selector + non-hashed verifier token
        return new Token(
            $tokenComponents->getPublicToken(),
            $expiresAt
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws ExpiredTokenException
     * @throws InvalidTokenException
     */
    public function validateTokenAndFetchUser(string $fullToken): object
    {
        $this->tokenCleaner->handleGarbageCollection();

        if (40 !== strlen($fullToken)) {
            throw new InvalidTokenException();
        }

        $tokenRequest = $this->findTokenRequest($fullToken);

        if (null === $tokenRequest) {
            throw new InvalidTokenException();
        }

        if ($tokenRequest->isExpired()) {
            throw new ExpiredTokenException();
        }

        $user = $tokenRequest->getUser();

        $hashedVerifierToken = $this->tokenGenerator->createToken(
            $tokenRequest->getExpiresAt(),
            $this->repository->getUserIdentifier($user),
            substr($fullToken, self::SELECTOR_LENGTH)
        );

        if (false === hash_equals($tokenRequest->getHashedToken(), $hashedVerifierToken->getHashedToken())) {
            throw new InvalidTokenException();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidTokenException
     */
    public function removeTokenRequest(string $fullToken): void
    {
        $request = $this->findTokenRequest($fullToken);

        if (null === $request) {
            throw new InvalidTokenException();
        }

        $this->repository->removeTokenRequest($request->getUser());
    }

    /**
     * {@inheritdoc}
     */
    public function removeUserTokenRequests(object $user): void
    {
        $this->repository->removeTokenRequest($user);
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenLifetime(): int
    {
        return $this->tokenRequestLifetime;
    }

    private function findTokenRequest(string $token): ?TokenRequestInterface
    {
        $selector = substr($token, 0, self::SELECTOR_LENGTH);

        return $this->repository->findTokenRequest($selector);
    }

    private function hasUserHitThrottling(object $user): ?DateTimeInterface
    {
        /** @var null|DateTime|DateTimeImmutable $lastRequestDate */
        $lastRequestDate = $this->repository->getMostRecentNonExpiredRequestDate($user);

        if (null === $lastRequestDate) {
            return null;
        }

        $availableAt = (clone $lastRequestDate)->add(new DateInterval("PT{$this->requestThrottleTime}S"));

        if ($availableAt > new DateTime('now')) {
            return $availableAt;
        }

        return null;
    }
}
