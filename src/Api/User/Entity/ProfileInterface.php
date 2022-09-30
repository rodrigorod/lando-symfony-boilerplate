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

    /**
     * Get user creation date.
     *
     * @return DateTimeInterface
     *  Date
     */
    public function getCreatedAt(): DateTimeInterface;

    /**
     * Get user updated date.
     *
     * @return null|DateTimeInterface
     *  Date
     */
    public function getUpdatedAt(): ?DateTimeInterface;

    /**
     * Set user updated date.
     *
     * @param null|DateTimeInterface $updatedAt
     *  Date
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): self;
}
