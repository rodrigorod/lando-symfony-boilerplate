<?php

namespace App\Api\Post\Entity;

use App\Api\Club\Entity\Club;
use App\Api\Club\Entity\ClubInterface;
use App\Api\User\Entity\User;
use App\Api\User\Entity\UserInterface;
use App\Repository\PostRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
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
     */
    protected string $mediaPath;

    /**
     * Post likes.
     *
     * @ORM\Column(type="array")
     *
     * @Groups({"view", "profile"})
     */
    protected array $likes = [];

    /**
     * Post comments.
     *
     * @ORM\Column(type="array")
     *
     * @Groups({"view", "profile"})
     */
    protected array $comments = [];

    /**
     * Post creation date.
     *
     * @ORM\Column(type="datetime")
     *
     * @Groups({"view", "profile"})
     */
    protected DateTimeInterface $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="posts")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id")
     *
     * @Groups({"profile"})
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
    public function getLikes(): array
    {
        return $this->likes;
    }

    /**
     * {@inheritDoc}
     */
    public function getComments(): array
    {
        return $this->comments;
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
