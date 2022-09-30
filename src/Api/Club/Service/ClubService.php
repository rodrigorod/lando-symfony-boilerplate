<?php

namespace App\Api\Club\Service;

use App\Api\Club\Entity\Club;
use App\Api\Club\Entity\ClubPatchPayload;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class ClubService.
 */
class ClubService
{
    public function __construct(
        protected EntityManagerInterface $em
    ) {}

    /**
     * Update club.
     *
     * @param clubPatchPayload $payload
     *  Payload data
     * @param Club $club
     *  Club to update
     *
     * @throws Exception
     */
    public function updateClub(ClubPatchPayload $payload, Club $club): Club
    {
        $club
            ->setBannerImage($payload->getBannerImage() ?? $club->getBannerImage())
            ->setImage($payload->getImage() ?? $club->getImage())
            ->setName($payload->getName() ?? $club->getName())
            ->setDescription($payload->getDescription() ?? $club->getDescription())
            ->setLocation($payload->getLocation() ?? $club->getLocation())
        ;

        try {
            $this->em->flush();
        } catch (Exception $e) {
            throw new Exception(sprintf('An error occurred : %s', $e->getMessage()));
        }

        return $club;
    }
}
