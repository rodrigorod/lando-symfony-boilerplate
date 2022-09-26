<?php

namespace App\Api\Car\Entity;

/**
 * Class CarPatchPayload.
 */
class CarPatchPayload
{
    /**
     * Car ownership status.
     */
    protected ?string $ownershipStatus;

    /**
     * Car brand.
     */
    protected ?string $brand;

    /**
     * Car model.
     */
    protected ?string $model;

    /**
     * Car year.
     */
    protected ?int $year;

    /**
     * Car modifications.
     */
    protected ?array $modifications;

    /**
     * Car horsepower.
     */
    protected ?int $horsePower;

    /**
     * Car torque.
     */
    protected ?int $torque;

    /**
     * Car image.
     */
    protected ?string $image;

    /**
     * Car trim.
     */
    protected ?string $trim;

    /**
     * Car description.
     */
    protected ?string $description;

    public function getOwnershipStatus(): ?string
    {
        return $this->ownershipStatus;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function getModifications(): ?array
    {
        return $this->modifications;
    }

    public function getHorsePower(): ?int
    {
        return $this->horsePower;
    }

    public function setOwnershipStatus(?string $ownershipStatus): CarPatchPayload
    {
        $this->ownershipStatus = $ownershipStatus;

        return $this;
    }

    public function setBrand(?string $brand): CarPatchPayload
    {
        $this->brand = $brand;

        return $this;
    }

    public function setModel(?string $model): CarPatchPayload
    {
        $this->model = $model;

        return $this;
    }

    public function setYear(?int $year): CarPatchPayload
    {
        $this->year = $year;

        return $this;
    }

    public function setModifications(?array $modifications): CarPatchPayload
    {
        $this->modifications = $modifications;

        return $this;
    }

    public function setHorsePower(?int $horsePower): CarPatchPayload
    {
        $this->horsePower = $horsePower;

        return $this;
    }

    public function setTorque(?int $torque): CarPatchPayload
    {
        $this->torque = $torque;

        return $this;
    }

    public function setImage(?string $image): CarPatchPayload
    {
        $this->image = $image;

        return $this;
    }

    public function setTrim(?string $trim): CarPatchPayload
    {
        $this->trim = $trim;

        return $this;
    }

    public function setDescription(?string $description): CarPatchPayload
    {
        $this->description = $description;

        return $this;
    }

    public function getTorque(): ?int
    {
        return $this->torque;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getTrim(): ?string
    {
        return $this->trim;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
