<?php

namespace App\Api\Category\Entity;

use App\Api\Car\Entity\CarInterface;
use App\Api\Club\Entity\ClubInterface;
use Doctrine\Common\Collections\Collection;

/**
 * Interface CategoryInterface.
 */
interface CategoryInterface
{
    /**
     * Get category id.
     *
     * @return string
     *  Unique identifier
     */
    public function getId(): string;

    /**
     * Get category name.
     *
     * @return string
     *  Name
     */
    public function getName(): string;

    /**
     * Get category slug.
     *
     * @return string
     *  Slug
     */
    public function getSlug(): string;

    /**
     * Get category clubs.
     *
     * @return Collection<ClubInterface>
     *  Clubs
     */
    public function getClubs(): Collection;

    /**
     * Get category cars.
     *
     * @return Collection<CarInterface>
     *  Cars
     */
    public function getCars(): Collection;
}
