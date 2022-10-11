<?php

namespace App\Api\Car\Controller;

use App\Api\Car\Entity\Car;
use App\Controller\EndpointController;
use App\DependencyInjection\EntityManagerAwareTrait;
use App\DependencyInjection\LoggerAwareTrait;
use App\Repository\CarRepository;
use App\Repository\CategoryRepository;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/car/category", name="api_car_category_")
 *
 * @OA\Tag(
 *     name="Car",
 *     description="Car information."
 * )
 */
class CategoryController extends EndpointController
{
    use LoggerAwareTrait;
    use EntityManagerAwareTrait;

    public function __construct(
        protected CategoryRepository $categoryRepository,
    ) {}

    /**
     * @Route("/{id}/add", name="add", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="clubAddCategory",
     *     summary="Add a category to a club.",
     *     path="/api/club/category/{slug}/add",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="uid",
     *              description="Car unique identifier",
     *              example="1ed42fe2-16f6-6368-98b6-d93168bb499c",
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="category",
     *          in="query",
     *          description="Category slug.",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="slug",
     *              example="amazing-category",
     *          ),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Car.",
     *          @OA\JsonContent(ref=@Model(type=Car::class, groups={"view", "list"})),
     *     ),
     *     @OA\Response(response="404", description="An error occurred."),
     * )
     *
     * @Security(name="Bearer")
     */
    public function add(Car $car, Request $request): Response
    {
        $categorySlug = $request->query->get('category');

        if (is_null($categorySlug)) {
            return $this->buildNotFoundResponse('No category provided.');
        }

        $category = $this->categoryRepository->findOneBy(['slug' => $categorySlug]);
        $car->addCategory($category);

        try {
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('Car\CategoryController::add - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('Car\CategoryController::add - Category %s created', $category->getSlug()));

        return $this->buildEntityResponse($car, $request, [], ['view', 'list']);
    }
}
