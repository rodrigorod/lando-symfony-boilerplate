<?php

namespace App\Api\Car\Entity;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

/**
 * Class CarPatchPayload.
 */
class CarPatchPayload
{
    /**
     * Car ownership status.
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
     *     description="New car ownership status.",
     *     example="s",
     * )
     */
    protected ?string $ownershipStatus;

    /**
     * Car brand.
     *
     * @OA\Property(
     *     property="brand",
     *     nullable=true,
     *     type="string",
     *     description="New car brand.",
     *     example="toyota",
     * )
     */
    protected ?string $brand;

    /**
     * Car model.
     *
     * @OA\Property(
     *     property="model",
     *     nullable=true,
     *     type="string",
     *     description="New car model.",
     *     example="supra",
     * )
     */
    protected ?string $model;

    /**
     * Car year.
     *
     * @OA\Property(
     *     property="year",
     *     nullable=true,
     *     type="integer",
     *     description="New car year.",
     *     example="1997",
     * )
     */
    protected ?int $year;

    /**
     * Car modifications.
     *
     * @OA\Property(
     *     property="modifications",
     *     nullable=true,
     *     type="array",
     *     description="Car modifications.",
     *     @OA\Items(ref=@Model(type=Modifications::class))
     * )
     */
    protected ?array $modifications;

    /**
     * Car horsepower.
     *
     * @OA\Property(
     *     property="horsePower",
     *     nullable=true,
     *     type="integer",
     *     description="New car horse power.",
     *     example="443",
     * )
     */
    protected ?int $horsePower;

    /**
     * Car torque.
     *
     * @OA\Property(
     *     property="torque",
     *     nullable=true,
     *     type="integer",
     *     description="New car torque.",
     *     example="500",
     * )
     */
    protected ?int $torque;

    /**
     * Car image.
     *
     * @OA\Property(
     *     property="image",
     *     nullable=true,
     *     type="integer",
     *     description="New car image.",
     *     example="carimage.png",
     * )
     */
    protected ?string $image;

    /**
     * Car trim.
     *
     * @OA\Property(
     *     property="trim",
     *     nullable=true,
     *     type="string",
     *     description="Car model trim.",
     *     example="1.8-break",
     * )
     */
    protected ?string $trim;

    /**
     * Car description.
     *
     * @OA\Property(
     *     property="description",
     *     nullable=true,
     *     type="string",
     *     description="Car description.",
     *     example="My amazing car description ...",
     * )
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
