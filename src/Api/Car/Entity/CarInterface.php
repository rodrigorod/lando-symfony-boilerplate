<?php

namespace App\Api\Car\Entity;

use App\Api\Category\Entity\Category;
use App\Api\Garage\Entity\GarageInterface;
use Doctrine\Common\Collections\Collection;

/**
 * Interface CarInterface.
 */
interface CarInterface
{
    /**
     * Car id.
     *
     * @return string
     *  Unique identifier
     */
    public function getId(): string;

    /**
     * Car image.
     *
     * @return null|string
     *  Image path
     */
    public function getImage(): ?string;

    /**
     * Set car image.
     *
     * @param null|string $image
     *  Image path
     */
    public function setImage(?string $image): self;

    /**
     * Ownership status.
     *
     * @return string
     *  Status
     */
    public function getOwnerShipStatus(): string;

    /**
     * Set ownership status.
     *
     * @param string $status
     *  Status
     */
    public function setOwnerShipStatus(string $status): self;

    /**
     * Car brand.
     *
     * @return string
     *  Brand
     */
    public function getBrand(): string;

    /**
     * Set car brand.
     *
     * @param string $brand
     *  Brand
     */
    public function setBrand(string $brand): self;

    /**
     * Car model.
     *
     * @return string
     *  Model
     */
    public function getModel(): string;

    /**
     * Set car model.
     *
     * @param string $model
     *  Model
     */
    public function setModel(string $model): self;

    /**
     * Car year.
     *
     * @return int
     *  Year
     */
    public function getYear(): int;

    /**
     * Set car year.
     *
     * @param int $year
     *  Year
     */
    public function setYear(int $year): self;

    /**
     * Car trim.
     *
     * @return null|string
     *  Trim
     */
    public function getTrim(): ?string;

    /**
     * Set car trim.
     *
     * @param null|string $trim
     *  Trim
     */
    public function setTrim(?string $trim): self;

    /**
     * Car modifications.
     *
     * @return Collection<ModificationsInterface>
     *  Modifications
     */
    public function getModifications(): Collection;

    /**
     * Add modifications.
     *
     * @param Modifications $modification
     *  Modification
     */
    public function addModification(Modifications $modification): self;

    /**
     * Remove modification.
     *
     * @param Modifications $modification
     *  Modification
     */
    public function removeModification(Modifications $modification): self;

    /**
     * Car horsepower.
     *
     * @return int
     *  Horsepower
     */
    public function getHorsePower(): int;

    /**
     * Set car horsepower.
     *
     * @param int $horsePower
     *  Horsepower
     */
    public function setHorsePower(int $horsePower): self;

    /**
     * Car torque.
     *
     * @return int
     *  Torque
     */
    public function getTorque(): int;

    /**
     * Set car torque.
     *
     * @param int $torque
     *  Torque
     */
    public function setTorque(int $torque): self;

    /**
     * Car description.
     *
     * @return null|string
     *  Description
     */
    public function getDescription(): ?string;

    /**
     * Set car description.
     *
     * @param null|string $description
     *  Description
     */
    public function setDescription(?string $description): self;

    /**
     * Car garage.
     *
     * @return null|GarageInterface
     *  Garage
     */
    public function getGarage(): ?GarageInterface;

    /**
     * Set car garage.
     *
     * @param null|GarageInterface $garage
     *  Garage
     */
    public function setGarage(?GarageInterface $garage): self;

    /**
     * Get car categories.
     *
     * @return Collection
     *  Categories
     */
    public function getCategories(): Collection;

    /**
     * Add category.
     *
     * @param Category $category
     *  Category
     */
    public function addCategory(Category $category): self;

    /**
     * Remove category.
     *
     * @param Category $category
     *  Category
     */
    public function removeCategory(Category $category): self;
}
