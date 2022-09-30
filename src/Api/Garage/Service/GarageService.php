<?php

namespace App\Api\Garage\Service;

use App\Api\Garage\Entity\Garage;
use App\DependencyInjection\ValidatorAwareTrait;
use App\Repository\CarRepository;
use App\Repository\GarageRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class GarageService.
 */
class GarageService
{
    use ValidatorAwareTrait;

    public function __construct(
        protected SerializerInterface $serializer,
        protected GarageRepository $garageRepository,
        protected CarRepository $carRepository,
        protected UserRepository $userRepository,
        protected EntityManagerInterface $em,
    ) {}

    /**
     * Inits a garage & persist it into database.
     *
     * @param string $userId
     *  User unique identifier
     *
     * @throws Exception
     *
     * @return Garage
     *  Garage
     */
    public function initGarage(string $userId): Garage
    {
        $garage = new Garage();
        $garage->setUser($this->userRepository->find($userId));

        try {
            $this->em->persist($garage);
            $this->em->flush();
        } catch (Exception $e) {
            throw new Exception(sprintf('An error occurred : %s', $e->getMessage()));
        }

        return $garage;
    }

    /**
     * Get user garage.
     */
    public function getGarage(string $userId): ?Garage
    {
        return $this->garageRepository->findOneBy(['user' => $userId]);
    }

    /**
     * Get user garage with cars.
     *
     * @param string $userId
     *  User unique identifier
     *
     * @return Collection
     *  Garage
     */
    public function getCars(string $userId): Collection
    {
        $garage = $this->garageRepository->findOneBy(['user' => $userId]);
        $cars = $this->carRepository->findBy(['garage' => $garage->getId()]);

        foreach ($cars as $car) {
            $garage->addCar($car);
        }

        return $garage->getCars();
    }
}
