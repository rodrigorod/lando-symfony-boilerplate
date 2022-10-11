<?php

namespace App\Api\User\Entity;

use App\Api\Club\Entity\ClubInterface;
use App\Api\Post\Entity\CommentInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as UserSecurityInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Interface UserInterface.
 */
interface UserInterface extends PasswordAuthenticatedUserInterface, UserSecurityInterface
{
    /**
     * Get User id.
     *
     * @return string
     *  Unique identifier
     */
    public function getId(): string;

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

    /**
     * Get user profile.
     */
    public function getProfile(): ?ProfileInterface;

    /**
     * Set user profile.
     *
     * @param null|ProfileInterface $profile
     *  Profile
     */
    public function setProfile(?ProfileInterface $profile): self;

    /**
     * Get user following clubs.
     *
     * @return Collection<ClubInterface>
     *  Clubs
     */
    public function getClubs(): Collection;

    /**
     * Add club.
     *
     * @param ClubInterface $club
     *  Club
     */
    public function addClub(ClubInterface $club): self;

    /**
     * Remove club.
     *
     * @param ClubInterface $club
     *  Club
     */
    public function removeClub(ClubInterface $club): self;

    /**
     * Get user posts.
     *
     * @return Collection
     *  Posts
     */
    public function getPosts(): Collection;

    /**
     * Get user liked comments.
     *
     * @return Collection<CommentInterface>
     *  Liked comments
     */
    public function getLikedComments(): Collection;

    /**
     * Add liked comment to list.
     *
     * @param CommentInterface $comment
     *  Comment
     */
    public function addLikedComment(CommentInterface $comment): self;

    /**
     * Remove liked comment from list.
     *
     * @param CommentInterface $comment
     *  Comment
     */
    public function removeLikedComment(CommentInterface $comment): self;
}

