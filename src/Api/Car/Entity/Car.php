<?php

namespace App\Api\Car\Entity;

use App\Api\Category\Entity\Category;
use App\Api\Garage\Entity\Garage;
use App\Api\Garage\Entity\GarageInterface;
use App\DependencyInjection\TimerAwareTrait;
use App\Repository\CarRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 * @ORM\HasLifecycleCallbacks()
 *
 * Class Car.
 */
class Car implements CarInterface
{
    use TimerAwareTrait;

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
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     *
     * @Assert\Unique()
     * @Assert\Uuid()
     *
     * @Groups(groups={"create", "view", "garage"})
     *
     * @OA\Property(
     *     property="id",
     *     nullable=false,
     *     type="string",
     *     format="uid",
     *     description="Car unique identifier.",
     *     example="1ed42fe2-16f6-6368-98b6-d93168bb499c",
     * )
     */
    protected Uuid $id;

    /**
     * Car image.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     *
     * @Groups({"garage"})
     *
     * @OA\Property(
     *     property="image",
     *     nullable=true,
     *     type="integer",
     *     description="Car image.",
     *     example="carimage.png",
     * )
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
     * @Groups(groups={"create", "view", "garage"})
     *
     * @OA\Property(
     *     property="ownershipStatus",
     *     nullable=false,
     *     type="string",
     *     enum={
     *          Car::OWNERSHIP_STATUS_CURRENT,
     *          Car::OWNERSHIP_STATUS_FOR_SALE,
     *          Car::OWNERSHIP_STATUS_PREVIOUS,
     *     },
     *     description="Car ownership status.",
     *     example="o",
     * )
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
     * @Groups(groups={"create", "view", "garage"})
     *
     * @OA\Property(
     *     property="brand",
     *     nullable=false,
     *     type="string",
     *     description="Car brand.",
     *     example="nissan",
     * )
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
     * @Groups(groups={"create", "view", "garage"})
     *
     * @OA\Property(
     *     property="model",
     *     nullable=false,
     *     type="string",
     *     description="Car model.",
     *     example="r-34",
     * )
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
     * @Groups(groups={"create", "view", "garage"})
     *
     * @OA\Property(
     *     property="year",
     *     nullable=false,
     *     type="integer",
     *     description="Car year.",
     *     example="1998",
     * )
     */
    protected int $year;

    /**
     * Car trim.
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @Assert\Type(type="string")
     *
     * @Groups(groups={"create", "view", "garage"})
     *
     * @OA\Property(
     *     property="trim",
     *     nullable=true,
     *     type="string",
     *     description="Car model trim.",
     *     example="1.8-coupe",
     * )
     */
    protected ?string $trim = null;

    /**
     * Car modifications.
     *
     * @ORM\OneToMany(targetEntity=Modifications::class, mappedBy="car")
     * @ORM\JoinTable(name="car_modifications")
     *
     * @Assert\Type(type="array")
     *
     * @Groups(groups={"create", "view", "garage"})
     *
     * @OA\Property(
     *     property="modifications",
     *     nullable=false,
     *     type="array",
     *     description="Car modifications.",
     *     @OA\Items(ref=@Model(type=Modifications::class))
     * )
     */
    protected Collection $modifications;

    /**
     * Car horsepower.
     *
     * @ORM\Column(type="integer", length=10)
     *
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     *
     * @Groups(groups={"create", "view", "garage"})
     *
     * @OA\Property(
     *     property="horsePower",
     *     nullable=false,
     *     type="integer",
     *     description="Car horse power.",
     *     example="443",
     * )
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
     * @Groups(groups={"create", "view", "garage"})
     *
     * @OA\Property(
     *     property="torque",
     *     nullable=false,
     *     type="integer",
     *     description="Car torque.",
     *     example="500",
     * )
     */
    protected int $torque;

    /**
     * Car description.
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Type(type="string")
     *
     * @Groups(groups={"create", "view", "garage"})
     *
     * @OA\Property(
     *     property="description",
     *     nullable=true,
     *     type="string",
     *     description="Car description.",
     *     example="My amazing car description ...",
     * )
     */
    protected ?string $description = null;

    /**
     * Car garage.
     *
     * @ORM\ManyToOne(targetEntity=Garage::class, inversedBy="cars")
     *
     * @OA\Property(
     *     property="garage",
     *     nullable=true,
     *     type="object",
     *     allOf={
     *          @OA\Schema(ref=@Model(type=Garage::class)),
     *     },
     * )
     */
    protected ?GarageInterface $garage = null;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="cars")
     * @ORM\JoinTable(name="car_categories")
     *
     * @Groups(groups={"view"})
     *
     * @OA\Property(
     *     property="categories",
     *     nullable=false,
     *     type="array",
     *     @OA\Items(ref=@Model(type=Category::class))
     * )
     */
    protected Collection $categories;

    public function __construct(array $values = [])
    {
        $this->createdAt = new DateTime();
        $this->categories = new ArrayCollection();
        $this->modifications = new ArrayCollection();

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
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTime();
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
    public function getModifications(): Collection
    {
        return $this->modifications;
    }

    /**
     * {@inheritDoc}
     */
    public function addModification(Modifications $modification): self
    {
        if (!$this->modifications->contains($modification)) {
            $this->modifications[] = $modification;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeModification(Modifications $modification): self
    {
        $this->modifications->removeElement($modification);

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

    /**
     * {@inheritDoc}
     */
    public function getGarage(): ?GarageInterface
    {
        return $this->garage;
    }

    /**
     * {@inheritDoc}
     */
    public function setGarage(?GarageInterface $garage): self
    {
        $this->garage = $garage;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * {@inheritDoc}
     */
    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }
}
