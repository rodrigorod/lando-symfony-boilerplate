<?php

namespace App\Api\Car\Entity;

interface ModificationsPatchPayloadInterface
{
    /**
     * Get manufacturer name.
     *
     * @return null|string
     *  Name
     */
    public function getManufacturerName(): ?string;

    /**
     * Set manufacturer name.
     *
     * @param null|string $manufacturerName
     *  Name
     */
    public function setManufacturerName(?string $manufacturerName): self;

    /**
     * Get modification name.
     *
     * @return null|string
     *  Name
     */
    public function getName(): ?string;

    /**
     * Set modification name.
     *
     * @param null|string $name
     *  Name
     */
    public function setName(?string $name): self;

    /**
     * Get horsepower gain.
     *
     * @return null|int
     *  Horsepower
     */
    public function getHorsePowerGain(): ?int;

    /**
     * Set horsepower gain.
     *
     * @param null|int $horsePowerGain
     *  Horsepower
     */
    public function setHorsePowerGain(?int $horsePowerGain): self;

    /**
     * Get torque gain.
     *
     * @return null|int
     *  Torque
     */
    public function getTorqueGain(): ?int;

    /**
     * Set torque gain.
     *
     * @param null|int $torqueGain
     *  Torque
     */
    public function setTorqueGain(?int $torqueGain): self;

    /**
     * Get weight gain.
     *
     * @return null|int
     *  Weight
     */
    public function getWeightGain(): ?int;

    /**
     * Set weight gain.
     *
     * @param null|int $weightGain
     *  Weight
     */
    public function setWeightGain(?int $weightGain): self;

    /**
     * Get modification description.
     *
     * @return null|string
     *  Description
     */
    public function getDescription(): ?string;

    /**
     * Set modification description.
     *
     * @param null|string $description
     *  Description
     */
    public function setDescription(?string $description): self;

    /**
     * Get website.
     *
     * @return null|string
     *  Website
     */
    public function getWebsite(): ?string;

    /**
     * Set website.
     *
     * @param null|string $website
     *  Website
     */
    public function setWebsite(?string $website): self;

    /**
     * Get modification cost.
     *
     * @return null|int
     *  Cost
     */
    public function getCost(): ?int;

    /**
     * Set modification cost.
     *
     * @param null|int $cost
     *  Cost
     */
    public function setCost(?int $cost): self;

    /**
     * Get labor cost.
     *
     * @return null|int
     *  Cost
     */
    public function getLaborCost(): ?int;

    /**
     * Set labor cost.
     *
     * @param null|int $laborCost
     *  Cost
     */
    public function setLaborCost(?int $laborCost): self;

    /**
     * Get modification type.
     *
     * @return null|string
     *  Type
     *
     * @see ModificationType
     */
    public function getType(): ?string;

    /**
     * Set modification type.
     *
     * @param null|string $type
     *  Type
     */
    public function setType(?string $type): self;
}
