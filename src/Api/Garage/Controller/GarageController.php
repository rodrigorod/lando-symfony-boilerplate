<?php

namespace App\Api\Garage\Controller;

use App\Api\Garage\Service\GarageService;
use App\Controller\EndpointController;
use App\DependencyInjection\EntityManagerAwareTrait;
use App\DependencyInjection\LoggerAwareTrait;
use App\DependencyInjection\SecurityAwareTrait;
use App\DependencyInjection\ValidatorAwareTrait;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Api\Garage\Entity\Garage;

/**
 * @Route("/api/garage", name="api_garage_")
 *
 * @OA\Tag(
 *     name="Garage",
 *     description="Garage informations.",
 * )
 */
class GarageController extends EndpointController
{
    use ValidatorAwareTrait;
    use SecurityAwareTrait;
    use LoggerAwareTrait;
    use EntityManagerAwareTrait;

    public function __construct(
        protected GarageService $garageService,
    ) {}

    /**
     * @Route("/", name="get", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="garageGet",
     *     summary="Get logged-user garage",
     *     path="/api/garage",
     *     @OA\Response(
     *          response="200",
     *          description="Garage.",
     *          @OA\JsonContent(ref=@Model(type=Garage::class, groups={"garage", "view"})),
     *     ),
     * )
     *
     * @Security(name="Bearer")
     */
    public function get(Request $request): Response
    {
        $garage = $this->garageService->getCars($this->getUser()->getId());

        return $this->buildEntityResponse($garage, $request, [], ['garage', 'view']);
    }

    /**
     * @Route("/init", name="init", methods={"POST"})
     *
     * @OA\Post(
     *     operationId="garageInit",
     *     summary="Init logged-user garage",
     *     path="/api/garage/init",
     *     @OA\Response(
     *          response="200",
     *          description="Garage.",
     *          @OA\JsonContent(ref=@Model(type=Garage::class, groups={"create"})),
     *     ),
     *     @OA\Response(response="404", description="An error occurred."),
     * )
     *
     * @Security(name="Bearer")
     */
    public function init(Request $request): Response
    {
        $user = $this->getUser();

        // checks if user already has a garage.
        if (null !== $this->garageService->getGarage($user->getId())) {
            return $this->buildNotFoundResponse('User already has a garage');
        }

        try {
            $garage = $this->garageService->initGarage($this->getUser()->getId());
        } catch (Exception $e) {
            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('GarageController::create - Garage id : %s ignited for User %s', $garage->getId(), $user->getId()));

        return $this->buildEntityResponse($garage, $request, [], ['create']);
    }
}
