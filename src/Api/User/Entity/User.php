<?php

namespace App\Api\User\Entity;

use App\Api\Club\Entity\Club;
use App\Api\Club\Entity\ClubInterface;
use App\Api\Garage\Entity\Garage;
use App\Api\Garage\Entity\GarageInterface;
use App\Api\Post\Entity\Post;
use App\DependencyInjection\TimerAwareTrait;
use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * Class User.
 */
class User implements UserInterface
{
    use TimerAwareTrait;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     *
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     *
     * @Groups({"user", "profile", "view"})
     */
    protected Uuid $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     *
     * @Groups({"user", "profile", "view"})
     */
    protected string $username;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Email()
     * @Assert\NotBlank()
     *
     * @Groups({"user", "profile", "view"})
     */
    protected string $email;

    /**
     * @ORM\Column(type="json")
     *
     * @Assert\Type(type="array")
     * @Assert\NotNull()
     *
     * @Groups({"user"})
     */
    protected ?array $roles = [];

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Type(type="string")
     * @Assert\NotNull()
     *
     * @Groups({"user"})
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
     */
    protected ?DateTimeInterface $activatedAt = null;

    /**
     * User garage.
     *
     * @ORM\OneToOne(targetEntity=Garage::class, mappedBy="user", cascade={"persist", "remove"})
     *
     * @Assert\Type(type=GarageInterface::class)
     *
     * @Groups({"user", "view", "profile"})
     */
    protected GarageInterface $garage;

    /**
     * User profile.
     *
     * @ORM\Column(type="object", nullable=true)
     *
     * @Groups({"user", "view", "profile"})
     */
    protected ?ProfileInterface $profile;

    /**
     * @ORM\ManyToMany(targetEntity=Club::class, mappedBy="members")
     * @ORM\JoinTable(name="club_members")
     *
     * @Groups({"user", "clubs"})
     */
    protected Collection $clubs;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user")
     *
     * @Groups({"user", "profile"})
     */
    protected Collection $posts;

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
}
