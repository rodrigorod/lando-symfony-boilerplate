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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/car", name="api_car_")
 *
 * @OA\Tag(
 *     name="Car",
 *     description="Car informations."
 * )
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
     * @Route("/list", name="list", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="carList",
     *     summary="Car list.",
     *     path="/api/car/list",
     *     @OA\Response(
     *          response="200",
     *          description="Car list.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref=@Model(type=Car::class, groups={"view"}))
     *          ),
     *     ),
     * )
     */
    public function list(Request $request): Response
    {
        $cars = $this->carRepository->findAll();

        return $this->buildEntityResponse($cars, $request, [], ['view']);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     *
     * @OA\Post(
     *     operationId="carCreate",
     *     summary="Create car.",
     *     path="/api/car/create",
     *     @OA\Response(
     *          response="200",
     *          description="Car",
     *          @OA\JsonContent(ref=@Model(type=Car::class, groups={"create"})),
     *     ),
     *     @OA\Response(response="404", description="An error occurred.")
     * )
     */
    public function create(Request $request): Response
    {
        $requestContent = (array) $this->getContentFromRequest($request);

        try {
            $car = $this->carService->createCar($requestContent);
        } catch (Exception $e) {
            return $this->buildNotFoundResponse('An error occurred');
        }

        $this->logger->info(sprintf('CarController::create - Car id : %s created for Garage : %s', $car->getId(), $car->getGarage()->getId()));

        return $this->buildEntityResponse($car, $request, [], ['create']);
    }

    /**
     * @Route("/{id}", name="get", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="carGet",
     *     summary="Get car.",
     *     path="/api/car/{id}",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="uid",
     *              description="Car unique identifier.",
     *              example="1ed42fe2-16f6-6368-98b6-d93168bb499c",
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Car.",
     *          @OA\JsonContent(ref=@Model(type=Car::class, groups={"view", "list"}))
     *     ),
     * )
     */
    public function get(Car $car, Request $request): Response
    {
        $categorySlug = $request->query->get('categories');

        $cars = $this->carRepository->getCarsByCategory($categorySlug);

        return $this->buildEntityResponse($cars, $request, [], ['view', 'list']);
    }

    /**
     * @Route("/update/{id}", name="update", methods={"PATCH"})
     *
     * @OA\Patch(
     *     operationId="carUpdate",
     *     summary="Update car.",
     *     path="/api/car/update/{id}",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="uid",
     *              description="Car unique identifier.",
     *              example="1ed42fe2-16f6-6368-98b6-d93168bb499c",
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref=@Model(type=CarPatchPayload::class)),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Updated club.",
     *          @OA\JsonContent(ref=@Model(type=Car::class)),
     *     ),
     *     @OA\Response(response="401", description="Access denied."),
     * )
     *
     * @Security(name="Bearer")
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
     *
     * @OA\Delete(
     *     operationId="carDelete",
     *     summary="Delete car.",
     *     path="/api/car/delete/{id}",
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
     *     @OA\Response(response="401", description="Access denied."),
     *     @OA\Response(response="202", description="Car deleted"),
     * )
     *
     * @Security(name="Bearer")
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
