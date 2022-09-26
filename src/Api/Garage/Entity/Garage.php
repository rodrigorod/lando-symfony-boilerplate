<?php

namespace App\Api\Garage\Entity;

use App\Api\Car\Entity\Car;
use App\Api\Car\Entity\CarInterface;
use App\Api\User\Entity\User;
use App\Api\User\Entity\UserInterface;
use App\Repository\GarageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GarageRepository::class)
 *
 * Class Garage.
 */
class Garage implements GarageInterface
{
    /**
     * Garage id.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Assert\Unique()
     * @Assert\Type("integer")
     *
     * @Groups(groups={"create", "view"})
     */
    protected int $id;

    /**
     * Garage car list.
     *
     * @ORM\OneToMany(targetEntity=Car::class, mappedBy="garage")
     *
     * @Groups(groups={"create", "view"})
     */
    protected ?Collection $cars;

    /**
     * Garage user.
     *
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="garage", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups(groups={"create", "view"})
     */
    protected UserInterface $user;

    public function __construct(UserInterface $user) {
        $this->cars = new ArrayCollection();
        $this->user = $user;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCars(): ?Collection
    {
        return $this->cars;
    }

    /**
     * {@inheritDoc}
     */
    public function setCars(?Collection $cars): self
    {
        $this->cars = $cars;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addCar(Car $car): self
    {
        if (!$this->cars->contains($car)) {
            $this->cars[] = $car;
            $car->setGarage($this);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeCar(Car $car): self
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getGarage() === $this) {
                $car->setGarage(null);
            }
        }

        return $this;
    }
}
