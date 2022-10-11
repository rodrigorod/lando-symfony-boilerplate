<?php

namespace App\Api\User\Entity;

use App\DependencyInjection\TimerAwareTrait;
use DateTime;
use OpenApi\Annotations as OA;
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
     * @Groups({"profile"})
     *
     * @OA\Property(
     *     property="image",
     *     nullable=false,
     *     type="string",
     *     description="Profile image.",
     *     example="default.png",
     * )
     */
    protected string $image;

    /**
     * First name.
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"profile"})
     *
     * @OA\Property(
     *     property="firstName",
     *     nullable=false,
     *     type="string",
     *     description="Profile first name.",
     *     example="John",
     * )
     */
    protected string $firstName;

    /**
     * Last name.
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"profile"})
     *
     * @OA\Property(
     *     property="lastName",
     *     nullable=false,
     *     type="string",
     *     description="Profile last name.",
     *     example="Doe",
     * )
     */
    protected string $lastName;

    /**
     * Interests.
     *
     * @Assert\Type("array")
     * @Assert\NotNull()
     *
     * @Groups({"profile"})
     *
     * @OA\Property(
     *     property="interests",
     *     nullable=false,
     *     type="array",
     *     description="Profile interests.",
     *     example="'toto'",
     *     @OA\Items(type="string"),
     * )
     */
    protected array $interests = [];

    /**
     * Followers count.
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     *
     * @Groups({"profile"})
     *
     * @OA\Property(
     *     property="followersCount",
     *     nullable=false,
     *     type="integer",
     *     description="Followers count.",
     *     example="1000",
     *     default="0",
     * )
     */
    protected int $followersCount = 0;

    /**
     * Following count.
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     *
     * @Groups({"profile"})
     *
     * @OA\Property(
     *     property="followingCount",
     *     nullable=false,
     *     type="integer",
     *     description="Following count.",
     *     example="1000",
     *     default="0",
     * )
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
