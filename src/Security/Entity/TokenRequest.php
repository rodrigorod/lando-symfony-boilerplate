<?php

namespace App\Security\Entity;

use App\Api\User\Entity\User;
use App\Security\Repository\TokenRequestRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TokenRequestRepository::class)
 */
class TokenRequest implements TokenRequestInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private object $user;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $selector;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $hashedToken;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeInterface $requestedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeInterface $expiresAt;

    public function __construct(object $user, DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->user = $user;
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): object
    {
        return $this->user;
    }

    public function getRequestedAt(): DateTimeInterface
    {
        return $this->requestedAt;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt->getTimestamp() <= \time();
    }

    public function getExpiresAt(): DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function getHashedToken(): string
    {
        return $this->hashedToken;
    }

    private function initialize(DateTimeInterface $expiresAt, string $selector, string $hashedToken): void
    {
        $this->requestedAt = new DateTimeImmutable('now');
        $this->expiresAt = $expiresAt;
        $this->selector = $selector;
        $this->hashedToken = $hashedToken;
    }
}