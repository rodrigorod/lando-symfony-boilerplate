<?php

namespace App\Api\Category\Entity;

use App\Api\Car\Entity\Car;
use App\Api\Club\Entity\Club;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
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
     */
    protected string $slug;

    /**
     * Category clubs.
     *
     * @ORM\ManyToMany(targetEntity=Club::class, inversedBy="categories")
     *
     * @Groups({"category"})
     */
    protected Collection $clubs;

    /**
     * Category cars.
     *
     * @ORM\ManyToMany(targetEntity=Car::class, inversedBy="categories")
     *
     * @Groups({"category"})
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
