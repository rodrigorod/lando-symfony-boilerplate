<?php

namespace App\DependencyInjection;

use App\Api\User\Entity\User;
use App\Api\User\Entity\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait LikesAwareTrait
{
    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     *
     * @Assert\NotNull()
     * @Groups({"view", "comment", "post"})
     *
     * @OA\Property(
     *     property="likes",
     *     nullable=false,
     *     type="object",
     *     allOf={
     *          @OA\Schema(ref=@Model(type=User::class))
     *     }
     * )
     */
    protected Collection $likes;

    /**
     * Likes count.
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\NotNull()
     *
     * @Groups({"view", "comment", "post"})
     *
     * @OA\Property(
     *     property="likesCount",
     *     nullable=false,
     *     type="integer",
     *     description="Likes count.",
     *     example="12"
     * )
     */
    protected int $likesCount = 0;

    /**
     * Get likes list.
     *
     * @return Collection<UserInterface>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    /**
     * Get comment likesCount.
     *
     * @return int
     *  Likes count
     */
    public function getLikesCount(): int
    {
        return $this->likesCount;
    }

    /**
     * Add user to likes list.
     *
     * @param UserInterface $user
     *  User
     */
    public function addLike(UserInterface $user): self
    {
        if (!$this->likes->contains($user)) {
            $this->likes[] = $user;
        }

        $this->incrementLikes();

        return $this;
    }

    /**
     * Remove user to likes list.
     *
     * @param UserInterface $user
     *  User
     */
    public function removeLike(UserInterface $user): self
    {
        $this->likes->removeElement($user);

        $this->decrementLikes();

        return $this;
    }

    /**
     * Decrement number of likesCount by 1.
     */
    public function decrementLikes(): void
    {
        if (0 !== $this->likesCount) {
            --$this->likesCount;
        }
    }

    /**
     * Increment number of likesCount by 1.
     */
    private function incrementLikes(): void
    {
        ++$this->likesCount;
    }
}
