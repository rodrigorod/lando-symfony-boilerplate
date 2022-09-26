<?php

namespace App\Api\User\Entity;

use App\Api\Garage\Entity\Garage;
use App\Api\Garage\Entity\GarageInterface;
use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * Class User.
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     *
     * @Groups({"view"})
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     *
     * @Groups({"view"})
     */
    protected string $username;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Email()
     * @Assert\NotBlank()
     *
     * @Groups({"view"})
     */
    protected string $email;

    /**
     * @ORM\Column(type="json")
     *
     * @Assert\Type(type="array")
     * @Assert\NotNull()
     *
     * @Groups({"view"})
     */
    protected ?array $roles = [];

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Type(type="string")
     * @Assert\NotNull()
     *
     * @Groups({"view"})
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
     * @Groups({"view"})
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
     * @Groups({"view"})
     */
    protected ?DateTimeInterface $activatedAt = null;

    /**
     * User garage.
     *
     * @ORM\OneToOne(targetEntity=Garage::class, mappedBy="user", cascade={"persist", "remove"})
     *
     * @Assert\Type(type=GarageInterface::class)
     */
    protected GarageInterface $garage;

    /**
     * {@inheritDoc}
     */
    public function getId(): ?int
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
     * {@inheritdoc}
     */
    public function getActivatedAt(): ?DateTimeInterface
    {
        return $this->activatedAt;
    }

    /**
     * {@inheritdoc}
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
}
