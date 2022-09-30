<?php

namespace App\Api\Garage\Entity;

use App\Api\Car\Entity\Car;
use App\Api\Car\Entity\CarInterface;
use App\Api\User\Entity\UserInterface;
use Doctrine\Common\Collections\Collection;

interface GarageInterface
{
    /**
     * Get garage id.
     *
     * @return string
     *  Unique identifier
     */
    public function getId(): string;

    /**
     * Get garage cars.
     *
     * @return null|Collection<Car>
     *  Car list
     */
    public function getCars(): ?Collection;

    /**
     * Set garage car list.
     *
     * @param null|Collection $cars
     *  Car list
     */
    public function setCars(?Collection $cars): self;

    /**
     * Add a car to the garage.
     *
     * @param Car $car
     *  Car
     */
    public function addCar(Car $car): self;

    /**
     * Remove a car from the garage.
     *
     * @param Car $car
     *  Car
     */
    public function removeCar(Car $car): self;

    /**
     * Get garage user.
     *
     * @return UserInterface
     *  User
     */
    public function getUser(): UserInterface;

    /**
     * Set garage user.
     *
     * @param UserInterface $user
     *  User
     */
    public function setUser(UserInterface $user): self;
}
