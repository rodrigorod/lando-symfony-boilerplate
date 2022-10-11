<?php

namespace App\Api\Category\Entity;

use App\Api\Car\Entity\Car;
use App\Api\Club\Entity\Club;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category.
 *
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category implements CategoryInterface
{
    /**
     * Category id.
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     *
     * @Assert\Unique()
     * @Assert\Type("integer")
     *
     * @Groups({"category", "list"})
     *
     * @OA\Property(
     *     property="id",
     *     nullable=false,
     *     type="string",
     *     format="uid",
     *     description="Category unique identifier.",
     *     example="1ed4326c-90ed-67f2-a419-6634hd892df",
     * )
     */
    protected Uuid $id;

    /**
     * Category name.
     *
     * @ORM\Column(type="string", length=10, unique=true)
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"category", "list"})
     *
     * @OA\Property(
     *     property="name",
     *     nullable=false,
     *     type="string",
     *     description="Category name.",
     *     example="American Muscle",
     * )
     */
    protected string $name;

    /**
     * Category slug.
     *
     * @ORM\Column(type="string", length=10, unique=true)
     * @Gedmo\Slug(fields={"name"})
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"category", "list"})
     *
     * @OA\Property(
     *     property="slug",
     *     nullable=false,
     *     type="string",
     *     format="slug",
     *     description="Category slug.",
     *     example="american-muscle",
     * )
     */
    protected string $slug;

    /**
     * Category clubs.
     *
     * @ORM\ManyToMany(targetEntity=Club::class, inversedBy="categories")
     *
     * @Groups({"category"})
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
     * Category cars.
     *
     * @ORM\ManyToMany(targetEntity=Car::class, inversedBy="categories")
     *
     * @Groups({"category"})
     *
     * @OA\Property(
     *     property="cars",
     *     nullable=false,
     *     type="array",
     *     @OA\Items(ref=@Model(type=Car::class)),
     * )
     */
    protected Collection $cars;

    public function __construct(array $values = []) {
        $this->clubs = new ArrayCollection();
        $this->cars = new ArrayCollection();

        foreach ([
            'name',
        ] as $property) {
            $this->{$property} = $values[$property];
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
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    /**
     * {@inheritDoc}
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }
}
