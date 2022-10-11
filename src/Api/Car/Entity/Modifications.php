<?php

namespace App\Api\Car\Entity;

use App\DependencyInjection\TimerAwareTrait;
use App\Repository\ModificationsRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * Class Modifications.
 *
 * @ORM\Entity(repositoryClass=ModificationsRepository::class)
 */
class Modifications implements ModificationsInterface
{
    use TimerAwareTrait;

    /**
     * Modifications unique identifier.
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     *
     * @Groups({"view"})
     */
    protected Uuid $id;

    /**
     * Modifications type.
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     *
     * @OA\Property(
     *     property="type",
     *     nullable=false,
     *     type="string",
     *     description="Modification type.",
     *     enum={
     *          ModificationType::ENGINE,
     *          ModificationType::DRIVE_TRAIN,
     *          ModificationType::HANDLING,
     *          ModificationType::EXTERIOR,
     *          ModificationType::INTERIOR,
     *          ModificationType::EXHAUST,
     *          ModificationType::TUNE,
     *     },
     *     example="handling",
     * )
     *
     * @Groups({"view"})
     */
    protected string $type;

    /**
     * Modification manufacturer name.
     *
     * @ORM\Column(type="string", length=150)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     *
     * @OA\Property(
     *     property="manufacturerName",
     *     nullable=false,
     *     type="string",
     *     description="Manufacturer name.",
     *     example="garrett",
     * )
     *
     * @Groups({"view"})
     */
    protected string $manufacturerName;

    /**
     * Modification name.
     *
     * @ORM\Column(type="string", length=150)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     *
     * @OA\Property(
     *     property="name",
     *     nullable=false,
     *     type="string",
     *     description="Modification name.",
     *     example="Garrett Turbo GX702B",
     * )
     *
     * @Groups({"view"})
     */
    protected string $name;

    /**
     * Modification slug.
     *
     * @ORM\Column(type="string", unique=true, length=255)
     *
     * @Gedmo\Slug(fields={"manufacturerName", "name"})
     *
     * @Groups({"view"})
     */
    protected string $slug;

    /**
     * Modification horsepower gain in (hp).
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type(type="integer")
     *
     * @OA\Property(
     *     property="horsePowerGain",
     *     nullable=true,
     *     type="integer",
     *     description="Modification horse power gain in (HP).",
     *     example="10",
     * )
     *
     * @Groups({"view"})
     */
    protected ?int $horsePowerGain;

    /**
     * Modification torque gain in (nM).
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type(type="integer")
     *
     * @OA\Property(
     *     property="torqueGain",
     *     nullable=true,
     *     type="integer",
     *     description="Modification torque gain in (nM).",
     *     example="50",
     * )
     *
     * @Groups({"view"})
     */
    protected ?int $torqueGain;

    /**
     * Modification weight gain in (kg).
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(0)
     *
     * @OA\Property(
     *     property="weightGain",
     *     nullable=true,
     *     type="integer",
     *     description="Modification weight gain in (kg).",
     *     example="50",
     * )
     *
     * @Groups({"view"})
     */
    protected ?int $weightGain;

    /**
     * Modification description.
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Type(type="string")
     *
     * @OA\Property(
     *     property="description",
     *     nullable=true,
     *     type="string",
     *     description="Modification description.",
     *     example="My Garrett Turbo with 2psi of boost.",
     * )
     *
     * @Groups({"view"})
     */
    protected ?string $description;

    /**
     * Modification website URL.
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Url()
     *
     * @OA\Property(
     *     property="website",
     *     nullable=true,
     *     type="string",
     *     description="Modification website url.",
     *     example="https://www.garrettmotion.com/",
     * )
     *
     * @Groups({"view"})
     */
    protected ?string $website;

    /**
     * Modification cost in (USD).
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(0)
     *
     * @OA\Property(
     *     property="cost",
     *     nullable=true,
     *     type="integer",
     *     description="Modification cost in (USD).",
     *     example="1562",
     * )
     *
     * @Groups({"view"})
     */
    protected ?int $cost;

    /**
     * Modification labor cost in (USD).
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(0)
     *
     * @OA\Property(
     *     property="laborCost",
     *     nullable=true,
     *     type="integer",
     *     description="Modification labor cost in (USD).",
     *     example="2612",
     * )
     *
     * @Groups({"view"})
     */
    protected ?int $laborCost;

    /**
     * Modification car.
     *
     * @ORM\ManyToOne(targetEntity=Car::class, inversedBy="modifications")
     */
    protected Car $car;

    public function __construct(array $values = [])
    {
        $this->createdAt = new DateTime();

        foreach ([
            'manufacturerName',
            'name',
        ] as $property) {
            if (isset($values[$property])) {
                $this->{$property} = $values[$property];
            }
        }

        // nullable properties
        foreach ([
            'horsePowerGain',
            'torqueGain',
            'weightGain',
            'description',
            'website',
            'cost',
            'laborCost',
        ] as $property) {
            $this->{$property} = $values[$property] ?? null;
        }
    }

    public function preUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }

    /**
     * Get modifications unique identifier.
     *
     * @return string
     *  Unique identifier
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getManufacturerName(): ?string
    {
        return $this->manufacturerName;
    }

    /**
     * {@inheritDoc}
     */
    public function setManufacturerName(string $manufacturerName): self
    {
        $this->manufacturerName = $manufacturerName;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get modifications slug.
     *
     * @return string
     *  Slug
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * {@inheritDoc}
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHorsePowerGain(): ?int
    {
        return $this->horsePowerGain;
    }

    /**
     * {@inheritDoc}
     */
    public function setHorsePowerGain(?int $horsePowerGain): self
    {
        $this->horsePowerGain = $horsePowerGain;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTorqueGain(): ?int
    {
        return $this->torqueGain;
    }

    /**
     * {@inheritDoc}
     */
    public function setTorqueGain(?int $torqueGain): self
    {
        $this->torqueGain = $torqueGain;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getWeightGain(): ?int
    {
        return $this->weightGain;
    }

    /**
     * {@inheritDoc}
     */
    public function setWeightGain(?int $weightGain): self
    {
        $this->weightGain = $weightGain;

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

    /**
     * {@inheritDoc}
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * {@inheritDoc}
     */
    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCost(): ?int
    {
        return $this->cost;
    }

    /**
     * {@inheritDoc}
     */
    public function setCost(?int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLaborCost(): ?int
    {
        return $this->laborCost;
    }

    /**
     * {@inheritDoc}
     */
    public function setLaborCost(?int $laborCost): self
    {
        $this->laborCost = $laborCost;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get modifications car.
     *
     * @return CarInterface
     *  Car
     */
    public function getCar(): CarInterface
    {
        return $this->car;
    }

    /**
     * Set modifications car.
     *
     * @param CarInterface $car
     *  Car
     */
    public function setCar(CarInterface $car): self
    {
        $this->car = $car;

        return $this;
    }
}
