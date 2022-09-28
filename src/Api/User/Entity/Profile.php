<?php

namespace App\Api\User\Entity;

use App\DependencyInjection\TimerAwareTrait;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Profile.
 */
class Profile implements ProfileInterface
{
    use TimerAwareTrait;

    /**
     * Profile picture.
     *
     * @Assert\Type("string")
     *
     * @Groups({"view"})
     */
    protected string $image;

    /**
     * First name.
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view"})
     */
    protected string $firstName;

    /**
     * Last name.
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view"})
     */
    protected string $lastName;

    /**
     * Clubs.
     *
     * @Assert\Type("array")
     * @Assert\NotNull()
     *
     * @Groups({"view"})
     */
    protected array $clubs = [];

    /**
     * Interests.
     *
     * @Assert\Type("array")
     * @Assert\NotNull()
     *
     * @Groups({"view"})
     */
    protected array $interests = [];

    /**
     * Followers count.
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     *
     * @Groups({"view"})
     */
    protected int $followersCount = 0;

    /**
     * Following count.
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     *
     * @Groups({"view"})
     */
    protected int $followingCount = 0;

    public function __construct(?string $image, string $firstName, string $lastName)
    {
        $this->createdAt = new DateTime();

        $this->image = $image ?? 'default.png';
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * {@inheritDoc}
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * {@inheritDoc}
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * {@inheritDoc}
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * {@inheritDoc}
     */
    public function getClubs(): array
    {
        return $this->clubs;
    }

    /**
     * {@inheritDoc}
     */
    public function getInterests(): array
    {
        return $this->interests;
    }

    /**
     * {@inheritDoc}
     */
    public function getFollowersCount(): int
    {
        return $this->followersCount;
    }

    /**
     * {@inheritDoc}
     */
    public function getFollowingCount(): int
    {
        return $this->followingCount;
    }
}
