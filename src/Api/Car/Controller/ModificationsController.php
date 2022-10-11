<?php

namespace App\Api\Car\Controller;

use App\Api\Car\Entity\Car;
use App\Api\Car\Entity\Modifications;
use App\Api\Car\Entity\ModificationsPatchPayload;
use App\Api\Car\Service\CarService;
use App\Controller\EndpointController;
use App\DependencyInjection\EntityManagerAwareTrait;
use App\DependencyInjection\LoggerAwareTrait;
use App\DependencyInjection\SecurityAwareTrait;
use App\DependencyInjection\SerializerAwareTrait;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/car/modifications", name="api_car_modifications_")
 *
 * @OA\Tag(
 *     name="Car",
 *     description="Car informations.",
 * )
 */
class ModificationsController extends EndpointController
{
    use SecurityAwareTrait;
    use EntityManagerAwareTrait;
    use SerializerAwareTrait;
    use LoggerAwareTrait;

    public function __construct(
        protected CarService $carService,
    ) {}

    /**
     * @Route("/add/{id}", name="add", methods={"POST"})
     *
     * @OA\Post(
     *     operationId="carModificationsAdd",
     *     summary="Add modifications.",
     *     path="/api/car/modifications/add/{id}",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="uid",
     *              description="Car unique identifier.",
     *              example="1ed42fe2-16f6-6368-98b6-d93168bb499c",
     *          ),
     *     ),
     * )
     */
    public function add(Car $car, Request $request): Response
    {
        $user = $this->getUser();
        $requestContent = (array) $this->getContentFromRequest($request);

        if ($user->getId() !== $car->getGarage()->getUser()->getId()) {
            return $this->buildUnauthorizedResponse();
        }

        $modifications = new Modifications($requestContent);
        $modifications->setCar($car);
        $car->addModification($modifications);

        try {
            $this->em->persist($modifications);
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('ModificationsController::add - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('ModificationsController::add - User %s added modifications for car %s', $user->getUserIdentifier(), $car->getId()));

        return $this->buildEntityResponse($modifications, $request);
    }

    /**
     * @Route("/update/{id}", name="update", methods={"PATCH"})
     *
     * @OA\Patch(
     *     operationId="carModificationsUpdate",
     *     summary="Update modifications.",
     *     path="/api/car/modifications/update/{id}",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="uid",
     *              description="Modifications unique identifier.",
     *              example="1ed42fe2-16f6-6368-98b6-d93168bb499c",
     *          )
     *     )
     * )
     */
    public function update(Modifications $modification, Request $request): Response
    {
        $user = $this->getUser();

        if ($user->getId() !== $modification->getCar()->getGarage()->getUser()->getId()) {
            return $this->buildUnauthorizedResponse();
        }

        $payload = $this->serializer->deserialize(
            $this->getContentFromRequest($request, false),
            ModificationsPatchPayload::class,
            'json'
        );

        try {
            $updatedModification = $this->carService->updateModification($payload, $modification);
        } catch (Exception $e) {
            $this->logger->error(sprintf('ModificationsController::update - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('ModificationsController::update - User %s updated modifications for car %s', $user->getUserIdentifier(), $updatedModification->getCar()->getId()));

        return $this->buildEntityResponse($updatedModification, $request);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     *
     * @OA\Delete(
     *     operationId="carModificationsDelete",
     *     summary="Delete modifications.",
     *     path="/api/car/modifications/delete/{id}",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="uid",
     *              description="Modifications unique identifier.",
     *              example="1ed42fe2-16f6-6368-98b6-d93168bb499c",
     *          )
     *     )
     * )
     */
    public function delete(Modifications $modification): Response
    {
        $user = $this->getUser();

        if ($user->getId() !== $modification->getCar()->getGarage()->getUser()->getId()) {
            return $this->buildUnauthorizedResponse();
        }

        $this->logger->info(sprintf('ModificationsController::delete - User %s attempted deleting modification for car %s', $user->getUserIdentifier(), $modification->getCar()->getId()));

        try {
            $this->em->remove($modification);
        } catch (Exception $e) {
            $this->logger->error(sprintf('ModificationsController::delete - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        return $this->buildAcceptedResponse('Modification deleted.');
    }
}

