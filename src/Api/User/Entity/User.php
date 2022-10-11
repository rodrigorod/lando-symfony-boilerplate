<?php

namespace App\Api\User\Entity;

use App\Api\Club\Entity\Club;
use App\Api\Club\Entity\ClubInterface;
use App\Api\Garage\Entity\Garage;
use App\Api\Garage\Entity\GarageInterface;
use App\Api\Post\Entity\CommentInterface;
use App\Api\Post\Entity\Post;
use App\Api\Post\Entity\PostInterface;
use App\DependencyInjection\TimerAwareTrait;
use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use App\Api\Post\Entity\Comment;

/**
 * Class User.
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    use TimerAwareTrait;

    /**
     * User unique identifier.
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     *
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     *
     * @Groups({"user", "profile", "view"})
     *
     * @OA\Property(
     *     property="id",
     *     nullable=false,
     *     type="string",
     *     format="uid",
     *     description="Unique identifier.",
     *     example="1ed42fe2-16f6-6368-98b6-d93168bb499c",
     * )
     */
    protected Uuid $id;

    /**
     * User username.
     *
     * @ORM\Column(type="string", length=50, unique=true)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     *
     * @Groups({"user", "profile", "view", "comment"})
     *
     * @OA\Property(
     *     property="username",
     *     nullable=false,
     *     type="string",
     *     description="Username.",
     *     example="johndoe",
     * )
     */
    protected string $username;

    /**
     * User e-mail address.
     *
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Email()
     * @Assert\NotBlank()
     *
     * @Groups({"user", "profile", "view"})
     *
     * @OA\Property(
     *     property="email",
     *     nullable=false,
     *     type="string",
     *     description="User e-mail address.",
     *     example="john.doe@mail.com",
     * )
     */
    protected string $email;

    /**
     * User roles.
     *
     * @ORM\Column(type="json")
     *
     * @Assert\Type(type="array")
     * @Assert\NotNull()
     *
     * @Groups({"user"})
     *
     * @OA\Property(
     *     property="roles",
     *     nullable=true,
     *     type="array",
     *     description="User roles.",
     *     example="ROLE_USER",
     *     default="ROLE_USER",
     *     @OA\Items(type="string")
     * )
     */
    protected ?array $roles = [];

    /**
     * User password.
     *
     * @ORM\Column(type="string")
     *
     * @Assert\Type(type="string")
     * @Assert\NotNull()
     *
     * @Groups({"user"})
     *
     * @OA\Property(
     *     property="password",
     *     nullable=false,
     *     type="string",
     *     description="User password.",
     *     example="password",
     * )
     */
    protected string $password;

    /**
     * Active status switcher.
     *
     * @Assert\Type("bool")
     * @ORM\Column(type="boolean", options={"default":"0"})
     *
     * @Assert\Type(type="boolean")
     * @Assert\NotNull()
     *
     * @Groups({"user"})
     *
     * @OA\Property(
     *     property="active",
     *     nullable=false,
     *     type="boolean",
     *     description="Whether user has been activated by confirming his e-mail address or not.",
     *     default="false",
     * )
     */
    protected bool $active = false;

    /**
     * Activation date (Format: ISO 8601).
     * Let us detect if user have already been activated.
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type(type=DateTimeInterface::class)
     * @Assert\LessThanOrEqual("now")
     *
     * @Groups({"user"})
     *
     * @OA\Property(
     *     property="activatedAt",
     *     nullable=true,
     *     type="string",
     *     format="date",
     *     description="User activation date.",
     *     example="2022-10-04T13:33:00",
     * )
     */
    protected ?DateTimeInterface $activatedAt = null;

    /**
     * User garage.
     *
     * @ORM\OneToOne(targetEntity=Garage::class, mappedBy="user", cascade={"persist", "remove"})
     *
     * @Assert\Type(type=GarageInterface::class)
     *
     * @Groups({"user", "profile"})
     *
     * @OA\Property(
     *     property="garage",
     *     nullable=false,
     *     type="object",
     *     allOf={
     *          @OA\Schema(ref=@Model(type=Garage::class))
     *     },
     * )
     */
    protected GarageInterface $garage;

    /**
     * User profile.
     *
     * @ORM\Column(type="object", nullable=true)
     *
     * @Groups({"user", "profile"})
     *
     * @OA\Property(
     *     property="profile",
     *     nullable=true,
     *     type="object",
     *     allOf={
     *          @OA\Schema(ref=@Model(type=Profile::class))
     *     },
     * )
     */
    protected ?ProfileInterface $profile;

    /**
     * User clubs.
     *
     * @ORM\ManyToMany(targetEntity=Club::class, inversedBy="members")
     *
     * @Groups({"user", "clubs"})
     *
     * @OA\Property(
     *     property="clubs",
     *     nullable=false,
     *     type="array",
     *     @OA\Items(ref=@Model(type=Club::class)),
     * )
     */
    protected Collection $clubs;

    /**
     * User posts.
     *
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user")
     *
     * @Groups({"user", "profile"})
     *
     * @OA\Property(
     *     property="posts",
     *     nullable=false,
     *     type="array",
     *     @OA\Items(ref=@Model(type=Post::class)),
     * )
     */
    protected Collection $posts;

    /**
     * @ORM\ManyToMany(targetEntity=Comment::class, mappedBy="likes")
     *
     * @OA\Property(
     *     property="likedComments",
     *     nullable=false,
     *     type="array",
     *     @OA\Items(ref=@Model(type=Comment::class)),
     * )
     */
    protected Collection $likedComments;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, mappedBy="likes")
     *
     * @OA\Property(
     *     property="likedPosts",
     *     nullable=false,
     *     type="array",
     *     @OA\Items(ref=@Model(type=Post::class)),
     * )
     */
    protected Collection $likedPosts;

    /**
     * User constructor.
     *
     * @param array $values
     *  User data
     */
    public function __construct(array $values = [])
    {
        foreach ([
            'username',
            'email',
        ] as $property) {
            $this->{$property} = $values[$property];
        }

        $this->clubs = new ArrayCollection();
        $this->likedComments = new ArrayCollection();
        $this->likedPosts = new ArrayCollection();
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
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * {@inheritDoc}
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * {@inheritDoc}
     */
    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritDoc}
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * {@inheritDoc}
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * {@inheritDoc}
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getActivatedAt(): ?DateTimeInterface
    {
        return $this->activatedAt;
    }

    /**
     * {@inheritDoc}
     */
    public function setActivatedAt(?DateTimeInterface $activatedAt): self
    {
        $this->activatedAt = $activatedAt;

        return $this;
    }

    public function getGarage(): ?GarageInterface
    {
        return $this->garage;
    }

    public function setGarage(Garage $garage): self
    {
        // set the owning side of the relation if necessary
        if ($garage->getUser() !== $this) {
            $garage->setUser($this);
        }

        $this->garage = $garage;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getProfile(): ?ProfileInterface
    {
        return $this->profile;
    }

    /**
     * {@inheritDoc}
     */
    public function setProfile(?ProfileInterface $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    /**
     * {@inheritDoc}
     */
    public function addClub(ClubInterface $club): self
    {
        if (!$this->clubs->contains($club)) {
            $this->clubs[] = $club;
            $club->addMember($this);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeClub(ClubInterface $club): self
    {
        if ($this->clubs->removeElement($club)) {
            $club->removeMember($this);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * {@inheritDoc}
     */
    public function getLikedComments(): Collection
    {
        return $this->likedComments;
    }

    /**
     * {@inheritDoc}
     */
    public function addLikedComment(CommentInterface $comment): self
    {
        if (!$this->likedComments->contains($comment)) {
            $this->likedComments[] = $comment;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeLikedComment(CommentInterface $comment): self
    {
        $this->likedComments->removeElement($comment);

        return $this;
    }

    public function getLikedPosts(): Collection
    {
        return $this->likedPosts;
    }

    public function addLikedPost(PostInterface $post): self
    {
        if (!$this->likedPosts->contains($post)) {
            $this->likedPosts[] = $post;
        }

        return $this;
    }

    public function removeLikedPost(PostInterface $post): self
    {
        $this->likedPosts->removeElement($post);

        return $this;
    }
}
