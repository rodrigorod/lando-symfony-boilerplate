<?php

namespace App\Api\Club\Entity;

use OpenApi\Annotations as OA;

/**
 * Class ClubPatchPayload.
 */
class ClubPatchPayload implements ClubPatchPayloadInterface
{
    /**
     * Club name.
     *
     * @OA\Property(
     *     property="name",
     *     nullable=true,
     *     type="string",
     *     description="New club name.",
     *     example="My new amazing club name",
     * )
     */
    protected ?string $name;

    /**
     * Club banner image.
     *
     * @OA\Property(
     *     property="bannerImage",
     *     nullable=true,
     *     type="string",
     *     description="New club banner image.",
     *     example="newimage.png",
     * )
     */
    protected ?string $bannerImage;

    /**
     * Club image.
     *
     * @OA\Property(
     *     property="image",
     *     nullable=true,
     *     type="string",
     *     description="New club image.",
     *     example="newimage.png",
     * )
     */
    protected ?string $image;

    /**
     * Club description.
     *
     * @OA\Property(
     *     property="description",
     *     nullable=false,
     *     type="string",
     *     description="New club description.",
     *     example="My club new description.",
     * )
     */
    protected ?string $description;

    /**
     * Club location.
     *
     * @OA\Property(
     *     property="location",
     *     nullable=true,
     *     type="string",
     *     description="New club location.",
     *     example="fribourg",
     * )
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
