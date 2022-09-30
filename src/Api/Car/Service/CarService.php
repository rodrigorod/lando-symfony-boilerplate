<?php

namespace App\Api\Car\Service;

use App\Api\Car\Entity\Car;
use App\Api\Car\Entity\CarPatchPayload;
use App\Api\Garage\Service\GarageService;
use App\DependencyInjection\SecurityAwareTrait;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CarService.
 */
class CarService
{
    use SecurityAwareTrait;

    public function __construct(
        protected SerializerInterface $serializer,
        protected EntityManagerInterface $em,
        protected GarageService $garageService,
    ) {}

    /**
     * Creates a car from given data & persist it into database.
     *
     * @param array $data
     *  Data
     *
     * @throws Exception
     *
     * @return Car
     *  Car
     */
    public function createCar(array $data): Car
    {
        $car = new Car($data);

        // bind car to garage
        $garage = $this->garageService->getGarage($this->getUser()->getId());
        $car->setGarage($garage);

        try {
            $this->em->persist($car);
            $this->em->flush();
        } catch (Exception $e) {
            throw new Exception(sprintf('An error occurred : %s', $e->getMessage()));
        }

        return $car;
    }

    /**
     * Update Car.
     *
     * @param carPatchPayload $payload
     *  Payload data
     * @param car $car
     *  Car to update
     * @throws Exception
     */
    public function updateCar(CarPatchPayload $payload, Car $car): Car
    {
        $car
            ->setOwnershipStatus($payload->getOwnershipStatus() ?? $car->getOwnershipStatus())
            ->setBrand($payload->getBrand() ?? $car->getBrand())
            ->setModel($payload->getModel() ?? $car->getModel())
            ->setYear($payload->getYear() ?? $car->getYear())
            ->setModifications($payload->getModifications() ?? $car->getModifications())
            ->setHorsePower($payload->getHorsePower() ?? $car->getHorsePower())
            ->setTorque($payload->getTorque() ?? $car->getTorque())
            ->setImage($payload->getImage() ?? $car->getImage())
            ->setTrim($payload->getTrim() ?? $car->getTrim())
            ->setDescription($payload->getDescription() ?? $car->getDescription())
        ;

        try {
            $this->em->flush();
        } catch (Exception $e) {
            throw new Exception(sprintf('An error occurred : %s', $e->getMessage()));
        }

        return $car;
    }
}
