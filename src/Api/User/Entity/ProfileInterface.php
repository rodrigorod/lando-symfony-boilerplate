<?php

namespace App\Api\User\Entity;

use DateTimeInterface;

/**
 * Interface ProfileInterface.
 */
interface ProfileInterface
{
    /**
     * Get user profile picture.
     *
     * @return string
     *  Image
     */
    public function getImage(): string;

    /**
     * Get user first name.
     *
     * @return string
     *  First name
     */
    public function getFirstName(): string;

    /**
     * Get user last name.
     *
     * @return string
     *  Last name
     */
    public function getLastName(): string;

    /**
     * Get user clubs joined.
     *
     * @return array
     *  Clubs
     */
    public function getClubs(): array;

    /**
     * Get user interests.
     *
     * @return array
     *  Interests
     */
    public function getInterests(): array;

    /**
     * Get user followers count.
     *
     * @return int
     *  Followers count
     */
    public function getFollowersCount(): int;

    /**
     * Get user following count.
     *
     * @return int
     *  Following count
     */
    public function getFollowingCount(): int;

    public function getCreatedAt(): DateTimeInterface;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self;
}
