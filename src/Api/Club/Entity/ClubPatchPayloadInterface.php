<?php

namespace App\Api\Club\Entity;

/**
 * Interface ClubPatchPayloadInterface.
 */
interface ClubPatchPayloadInterface
{
    /**
     * Club name.
     */
    public function getName(): ?string;

    /**
     * Set name.
     *
     * @param string $name
     *  Name
     */
    public function setName(string $name): self;

    /**
     * Club banner image.
     */
    public function getBannerImage(): ?string;

    /**
     * Set bannerImage.
     *
     * @param string $bannerImage
     *  BannerImage
     */
    public function setBannerImage(string $bannerImage): self;

    /**
     * Club image.
     */
    public function getImage(): ?string;

    /**
     * Set image.
     *
     * @param string $image
     *  Image
     */
    public function setImage(string $image): self;

    /**
     * Club Description.
     */
    public function getDescription(): ?string;

    /**
     * Set description.
     *
     * @param string $description
     *  Description
     */
    public function setDescription(string $description): self;

    /**
     * Club location.
     */
    public function getLocation(): ?string;

    /**
     * Set location.
     *
     * @param string $location
     *  Location
     */
    public function setLocation(string $location): self;

}
