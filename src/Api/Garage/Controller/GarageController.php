<?php

namespace App\Api\Garage\Controller;

use App\Api\Car\Entity\Car;
use App\Api\Garage\Entity\Garage;
use App\Api\Garage\Service\GarageService;
use App\Controller\EndpointController;
use App\DependencyInjection\SecurityAwareTrait;
use App\DependencyInjection\ValidatorAwareTrait;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/garage", name="api_garage_")
 */
class GarageController extends EndpointController
{
    use ValidatorAwareTrait;
    use SecurityAwareTrait;

    public function __construct(
        protected LoggerInterface $logger,
        protected EntityManagerInterface $em,
        protected GarageService $garageService,
    ) {}

    /**
     * @Route("/", name="get")
     */
    public function get(Request $request): Response
    {
        $garage = $this->garageService->getCars($this->getUser()->getId());

        return $this->buildEntityResponse($garage, $request, [], ['view']);
    }

    /**
     * @Route("/init", name="init", methods={"POST"})
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
            return $this->buildNotFoundResponse(sprintf('An error occurred : %s', $e->getMessage()));
        }

        $this->logger->info(sprintf('GarageController::create - Garage id : %s ignited for User %s', $garage->getId(), $user->getId()));

        return $this->buildEntityResponse($garage, $request, [], ['create']);
    }
}
