<?php

namespace App\Api\Post\Entity;

use App\Api\Club\Entity\Club;
use App\Api\Club\Entity\ClubInterface;
use App\Api\User\Entity\User;
use App\Api\User\Entity\UserInterface;
use App\DependencyInjection\LikesAwareTrait;
use App\DependencyInjection\TimerAwareTrait;
use App\Repository\PostRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Post.
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post implements PostInterface
{
    use LikesAwareTrait;

    /**
     * Post id.
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     *
     * @Assert\Uuid()
     * @Assert\NotNull()
     *
     * @Groups({"view", "profile"})
     *
     * @OA\Property(
     *     property="id",
     *     nullable=false,
     *     type="string",
     *     format="uid",
     *     description="Post unique identifier",
     *     example="1ed4326c-90ed-67f2-a419-6634hd892df",
     * )
     */
    protected Uuid $id;

    /**
     * Post user.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @Assert\NotNull()
     *
     * @Groups({"view"})
     *
     * @OA\Property(
     *     property="user",
     *     nullable=false,
     *     type="object",
     *     allOf={
     *          @OA\Schema(ref=@Model(type=User::class)),
     *     }
     * )
     */
    protected UserInterface $user;

    /**
     * Post name.
     *
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view", "profile"})
     *
     * @OA\Property(
     *     property="name",
     *     nullable=false,
     *     type="string",
     *     description="Post name/title.",
     *     example="My amazing new Post !",
     * )
     */
    protected string $name;

    /**
     * Post slug.
     *
     * @ORM\Column(type="string", length=100, unique=true)
     * @Gedmo\Slug(fields={"name"})
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view", "profile"})
     *
     * @OA\Property(
     *     property="slug",
     *     nullable=false,
     *     type="string",
     *     format="slug",
     *     description="Post slug.",
     *     example="my-amazing-new-post",
     * )
     */
    protected string $slug;

    /**
     * Post media path.
     *
     * @ORM\Column(type="string")
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view", "profile"})
     *
     * @OA\Property(
     *     property="mediaPath",
     *     nullable=false,
     *     type="string",
     *     description="Post image/video.",
     *     example="car.png",
     * )
     */
    protected string $mediaPath;

    /**
     * Post comments.
     *
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     *
     * @Groups({"view", "profile"})
     *
     * @OA\Property(
     *     property="comments",
     *     nullable=false,
     *     type="array",
     *     description="Post comments.",
     *     @OA\Items(ref=@Model(type=Comment::class))
     * )
     */
    protected Collection $comments;

    /**
     * Post creation date.
     *
     * @ORM\Column(type="datetime")
     *
     * @Groups({"view", "profile"})
     *
     * @OA\Property(
     *     property="createdAt",
     *     nullable=false,
     *     type="string",
     *     format="date",
     *     description="Post creation date.",
     *     example="2022-10-04T13:33:00",
     * )
     */
    protected DateTimeInterface $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="posts")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id")
     *
     * @Groups({"profile"})
     *
     * @OA\Property(
     *     property="club",
     *     nullable=false,
     *     type="object",
     *     allOf={
     *          @OA\Schema(ref=@Model(type=Club::class))
     *     }
     * )
     */
    protected ClubInterface $club;


    public function __construct(array $values = []) {
        foreach ([
            'name',
            'mediaPath',
        ] as $property) {
            $this->{$property} = $values[$property];
        }

        $this->createdAt = new DateTime();
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * {@inheritDoc}
     */
    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaPath(): string
    {
        return $this->mediaPath;
    }

    /**
     * {@inheritDoc}
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * {@inheritDoc}
     */
    public function addComment(CommentInterface $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeComment(CommentInterface $comment): self
    {
        $this->comments->removeElement($comment);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getClub(): ClubInterface
    {
        return $this->club;
    }

    /**
     * {@inheritDoc}
     */
    public function setClub(ClubInterface $club): self
    {
        $this->club = $club;

        return $this;
    }
}
