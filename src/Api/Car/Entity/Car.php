<?php

namespace App\Api\Car\Entity;

use App\Api\Garage\Entity\Garage;
use App\Api\Garage\Entity\GarageInterface;
use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 *
 * Class Car.
 */
class Car implements CarInterface
{
    /**
     * Car currently owned by user.
     */
    public const OWNERSHIP_STATUS_CURRENT = 'c';

    /**
     * Car previously owned by user.
     */
    public const OWNERSHIP_STATUS_PREVIOUS = 'p';

    /**
     * Car put for sale by user.
     */
    public const OWNERSHIP_STATUS_FOR_SALE = 's';

    /**
     * Car id.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Assert\Unique()
     * @Assert\Type(type="integer")
     *
     * @Groups(groups={"create", "view"})
     */
    protected int $id;

    /**
     * Car image.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     */
    protected ?string $image = null;

    /**
     * Car ownership status.
     *
     * @ORM\Column(type="string", length=1)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     *
     * @Groups(groups={"create", "view"})
     */
    protected string $ownershipStatus;

    /**
     * Car brand.
     *
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     *
     * @Groups(groups={"create", "view"})
     */
    protected string $brand;

    /**
     * Car model.
     *
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     *
     * @Groups(groups={"create", "view"})
     */
    protected string $model;

    /**
     * Car year.
     *
     * @ORM\Column(type="integer", length=4)
     *
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     *
     * @Groups(groups={"create", "view"})
     */
    protected int $year;

    /**
     * Car trim.
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @Assert\Type(type="string")
     *
     * @Groups(groups={"create", "view"})
     */
    protected ?string $trim = null;

    /**
     * Car modifications.
     *
     * @ORM\Column(type="array")
     *
     * @Assert\Type(type="array")
     *
     * @Groups(groups={"create", "view"})
     */
    protected array $modifications = [];

    /**
     * Car horsepower.
     *
     * @ORM\Column(type="integer", length=10)
     *
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     *
     * @Groups(groups={"create", "view"})
     */
    protected int $horsePower;

    /**
     * Car torque.
     *
     * @ORM\Column(type="integer", length=10)
     *
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     *
     * @Groups(groups={"create", "view"})
     */
    protected int $torque;

    /**
     * Car description.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Type(type="string")
     *
     * @Groups(groups={"create", "view"})
     */
    protected ?string $description = null;

    /**
     * Car garage.
     *
     * @ORM\ManyToOne(targetEntity=Garage::class, inversedBy="cars")
     */
    protected ?GarageInterface $garage = null;

    public function __construct(array $values = [])
    {
        foreach ([
            'ownershipStatus',
            'brand',
            'model',
            'year',
            'modifications',
            'horsePower',
            'torque',
        ] as $property) {
            if (isset($values[$property])) {
                $this->{$property} = $values[$property];
            }
        }

        // nullable properties
        foreach ([
            'image',
            'trim',
            'description',
            'garage',
        ] as $property) {
            $this->{$property} = $values[$property] ?? null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * {@inheritDoc}
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getOwnershipStatus(): string
    {
        return $this->ownershipStatus;
    }

    /**
     * {@inheritDoc}
     */
    public function setOwnershipStatus(string $ownershipStatus): self
    {
        $this->ownershipStatus = $ownershipStatus;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * {@inheritDoc}
     */
    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * {@inheritDoc}
     */
    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * {@inheritDoc}
     */
    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTrim(): ?string
    {
        return $this->trim;
    }

    /**
     * {@inheritDoc}
     */
    public function setTrim(?string $trim): self
    {
        $this->trim = $trim;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getModifications(): array
    {
        return $this->modifications;
    }

    /**
     * {@inheritDoc}
     */
    public function setModifications(array $modifications): self
    {
        $this->modifications = $modifications;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHorsePower(): int
    {
        return $this->horsePower;
    }

    /**
     * {@inheritDoc}
     */
    public function setHorsePower(int $horsePower): self
    {
        $this->horsePower = $horsePower;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTorque(): int
    {
        return $this->torque;
    }

    /**
     * {@inheritDoc}
     */
    public function setTorque(int $torque): self
    {
        $this->torque = $torque;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGarage(): ?GarageInterface
    {
        return $this->garage;
    }

    public function setGarage(?GarageInterface $garage): self
    {
        $this->garage = $garage;

        return $this;
    }
}
