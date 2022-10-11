<?php

namespace App\Api\Car\Entity;

class ModificationsPatchPayload implements ModificationsPatchPayloadInterface
{
    /**
     * Modifications type.
     */
    protected ?string $type;

    /**
     * Modifications manufacturer name.
     */
    protected ?string $manufacturerName;

    /**
     * Modifications name.
     */
    protected ?string $name;

    /**
     * Modifications horsepower gain.
     */
    protected ?int $horsePowerGain;

    /**
     * Modifications torque gain.
     */
    protected ?int $torqueGain;

    /**
     * Modifications weight gain.
     */
    protected ?int $weightGain;

    /**
     * Modifications description.
     */
    protected ?string $description;

    /**
     * Modifications website url.
     */
    protected ?string $website;

    /**
     * Modifications cost.
     */
    protected ?int $cost;

    /**
     * Modifications labor cost.
     */
    protected ?int $laborCost;

    /**
     * {@inheritDoc}
     */
    public function getManufacturerName(): ?string
    {
        return $this->manufacturerName;
    }

    /**
     * {@inheritDoc}
     */
    public function setManufacturerName(?string $manufacturerName): self
    {
        $this->manufacturerName = $manufacturerName;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getHorsePowerGain(): ?int
    {
        return $this->horsePowerGain;
    }

    /**
     * {@inheritDoc}
     */
    public function setHorsePowerGain(?int $horsePowerGain): self
    {
        $this->horsePowerGain = $horsePowerGain;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTorqueGain(): ?int
    {
        return $this->torqueGain;
    }

    /**
     * {@inheritDoc}
     */
    public function setTorqueGain(?int $torqueGain): self
    {
        $this->torqueGain = $torqueGain;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getWeightGain(): ?int
    {
        return $this->weightGain;
    }

    /**
     * {@inheritDoc}
     */
    public function setWeightGain(?int $weightGain): self
    {
        $this->weightGain = $weightGain;

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
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * {@inheritDoc}
     */
    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCost(): ?int
    {
        return $this->cost;
    }

    /**
     * {@inheritDoc}
     */
    public function setCost(?int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLaborCost(): ?int
    {
        return $this->laborCost;
    }

    /**
     * {@inheritDoc}
     */
    public function setLaborCost(?int $laborCost): self
    {
        $this->laborCost = $laborCost;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
