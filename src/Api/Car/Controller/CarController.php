<?php

namespace App\Api\Car\Controller;

use App\Api\Car\Entity\Car;
use App\Api\Car\Entity\CarPatchPayload;
use App\Api\Car\Service\CarService;
use App\Api\Garage\Service\GarageService;
use App\Controller\EndpointController;
use App\DependencyInjection\SecurityAwareTrait;
use App\DependencyInjection\ValidatorAwareTrait;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/car", name="api_car_")
 */
class CarController extends EndpointController
{
    use ValidatorAwareTrait;
    use SecurityAwareTrait;

    public function __construct(
        protected CarService $carService,
        protected GarageService $garageService,
        protected CarRepository $carRepository,
        protected LoggerInterface $logger,
        protected EntityManagerInterface $em,
        protected SerializerInterface $serializer,
    ) {}

    /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $requestContent = $this->getContentFromRequest($request, false);

        try {
            $car = $this->carService->createCar($requestContent);
        } catch (Exception $e) {
            return $this->buildNotFoundResponse(sprintf('An error occurred %s', $e->getMessage()));
        }

        $this->logger->info(sprintf('CarController::create - Car id : %s created for Garage : %s', $car->getId(), $car->getGarage()->getId()));

        return $this->buildEntityResponse($car, $request, [], ['create']);
    }

    /**
     * @Route("/update/{id}", name="update", methods={"PATCH"})
     */
    public function update(Car $car, Request $request): Response
    {
        $user = $this->getUser();
        $requestContent = $this->getContentFromRequest($request, false);
        $garage = $this->garageService->getGarage($user->getId());

        if ($garage->getId() !== $car->getGarage()->getId()) {
            return $this->buildUnauthorizedResponse();
        }

        $payload = $this->serializer->deserialize(
            $requestContent,
            CarPatchPayload::class,
            'json',
            []
        );

        try {
           $car = $this->carService->updateCar($payload, $car);
        } catch (Exception $e) {
            return $this->buildNotFoundResponse(sprintf('An error occurred : %s', $e->getMessage()));
        }

        return $this->buildEntityResponse($car, $request);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Car $car, Request $request): Response
    {
        $user = $this->getUser();
        $this->logger->info(sprintf('CarController::delete - Deleting Car id : %s from Garage : %s', $car->getId(), $car->getGarage()->getId()));
        $garage = $this->garageService->getGarage($user->getId());

        if ($garage->getId() !== $car->getGarage()->getId()) {
            return $this->buildUnauthorizedResponse();
        }

        try {
            $this->carRepository->remove($car);
            $this->em->flush();
        } catch (Exception $e) {
            return $this->buildNotFoundResponse(sprintf('An error occurred : %s', $e->getMessage()));
        }

        return $this->buildAcceptedResponse('Car deleted');
    }
}
