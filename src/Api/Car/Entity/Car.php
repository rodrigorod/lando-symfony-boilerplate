<?php

namespace App\Api\Car\Entity;

use App\Api\Garage\Entity\Garage;
use App\Api\Garage\Entity\GarageInterface;
use App\DependencyInjection\TimerAwareTrait;
use App\Repository\CarRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use App\Api\Category\Entity\Category;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
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
     */
    protected ?string $trim = null;

    /**
     * Car modifications.
     *
     * @ORM\Column(type="array")
     *
     * @Assert\Type(type="array")
     *
     * @Groups(groups={"create", "view", "garage"})
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
     * @Groups(groups={"create", "view", "garage"})
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
     */
    protected ?string $description = null;

    /**
     * Car garage.
     *
     * @ORM\ManyToOne(targetEntity=Garage::class, inversedBy="cars")
     */
    protected ?GarageInterface $garage = null;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="cars")
     */
    protected Collection $categories;

    public function __construct(array $values = [])
    {
        $this->createdAt = new DateTime();
        $this->categories = new ArrayCollection();

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

