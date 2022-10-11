<?php

namespace App\Api\Category\Controller;

use App\Api\Category\Entity\Category;
use App\Controller\EndpointController;
use App\DependencyInjection\EntityManagerAwareTrait;
use App\DependencyInjection\LoggerAwareTrait;
use App\DependencyInjection\SecurityAwareTrait;
use App\Repository\CategoryRepository;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/category", name="api_category_")
 *
 * @OA\Tag(
 *     name="Category",
 *     description="Category informations.",
 * )
 */
class CategoryController extends EndpointController
{
    use LoggerAwareTrait;
    use EntityManagerAwareTrait;
    use SecurityAwareTrait;

    /**
     * @Route("/create", name="create", methods={"POST"})
     *
     * @OA\Post(
     *     operationId="categoryCreate",
     *     summary="Create category.",
     *     path="/api/category/create",
     *     @OA\Response(
     *          response="200",
     *          description="Category",
     *          @OA\JsonContent(ref=@Model(type=Category::class, groups={"category"})),
     *     ),
     *     @OA\Response(response="404", description="An error occurred.")
     * )
     *
     * @Security(name="Bearer")
     */
    public function create(Request $request): Response
    {
        $requestContent = $this->getContentFromRequest($request);

        $category = new Category($requestContent);

        try {
            $this->em->persist($category);
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('CategoryController::create - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('CategoryController::create - User : %s created Category : %s', $this->getUser()->getUserIdentifier(), $category->getId()));

        return $this->buildEntityResponse($category, $request, [], ['category']);
    }

    /**
     * @Route("/list", name="list", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="categoryList",
     *     summary="Category list.",
     *     path="/api/category/list",
     *     @OA\Response(
     *          response="200",
     *          description="Category list.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref=@Model(type=Category::class, groups={"list"}))
     *          ),
     *     ),
     * )
     *
     * @Security(name="Bearer")
     */
    public function list(Request $request, CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();

        return $this->buildEntityResponse($categories, $request, [], ['list']);
    }
}


