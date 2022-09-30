<?php

namespace App\Api\Club\Entity;

class ClubPatchPayload implements ClubPatchPayloadInterface
{
    /**
     * Club name.
     */
    protected ?string $name;

    /**
     * Club banner image.
     */
    protected ?string $bannerImage;

    /**
     * Club image.
     */
    protected ?string $image;

    /**
     * Club description.
     */
    protected ?string $description;

    /**
     * Club location.
     */
    protected ?string $location;

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
    public function getBannerImage(): ?string
    {
        return $this->bannerImage;
    }

    /**
     * {@inheritDoc}
     */
    public function setBannerImage(?string $bannerImage): self
    {
        $this->bannerImage = $bannerImage;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * {@inheritDoc}
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

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
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * {@inheritDoc}
     */
    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }
}
