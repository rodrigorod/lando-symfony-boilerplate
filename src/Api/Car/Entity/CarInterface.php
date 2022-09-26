<?php

namespace App\Api\Car\Entity;

use App\Api\Garage\Entity\GarageInterface;

/**
 * Interface CarInterface.
 */
interface CarInterface
{
    /**
     * Car id.
     *
     * @return int
     *  Unique identifier
     */
    public function getId(): int;

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
     * @return array
     *  Modifications
     */
    public function getModifications(): array;

    /**
     * Set car modifications.
     *
     * @param array $modifications
     *  Modifications
     */
    public function setModifications(array $modifications): self;

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
}
