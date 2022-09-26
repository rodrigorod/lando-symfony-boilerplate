<?php

namespace App\Api\User\Entity;

use DateTimeInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as UserSecurityInterface;

/**
 * Interface UserInterface.
 */
interface UserInterface extends PasswordAuthenticatedUserInterface, UserSecurityInterface
{
    /**
     * Get User id.
     */
    public function getId(): ?int;

    /**
     * Get User username.
     */
    public function getUsername(): string;

    /**
     * @param string $username
     *    Username
     */
    public function setUsername(string $username): self;

    /**
     * Get User email address.
     */
    public function getEmail(): string;

    /**
     * @param string $email
     *    Email
     */
    public function setEmail(string $email): self;

    /**
     * @param null|string $password
     *    Password
     */
    public function setPassword(?string $password): self;

    /**
     * @param null|array $roles
     *     Roles
     */
    public function setRoles(?array $roles): self;

    /**
     * Whether the user is active.
     */
    public function isActive(): bool;

    /**
     * Set active status.
     */
    public function setActive(bool $active): self;

    /**
     * Get date of account activation.
     */
    public function getActivatedAt(): ?DateTimeInterface;

    /**
     * Set account activation date.
     */
    public function setActivatedAt(?DateTimeInterface $activatedAt): self;
}

