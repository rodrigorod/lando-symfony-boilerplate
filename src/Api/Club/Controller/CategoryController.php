<?php

namespace App\Api\Club\Controller;

use App\Api\Category\Entity\Category;
use App\Api\Club\Entity\Club;
use App\Controller\EndpointController;
use App\DependencyInjection\EntityManagerAwareTrait;
use App\DependencyInjection\LoggerAwareTrait;
use App\Repository\CategoryRepository;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/club/category", name="api_club_category_")
 *
 * @OA\Tag(
 *     name="Club",
 *     description="Club informations.",
 * )
 */
class CategoryController extends EndpointController
{
    use EntityManagerAwareTrait;
    use LoggerAwareTrait;

    public function __construct(
        protected CategoryRepository $categoryRepository,
    ) {}

    /**
     * @Route("/{slug}/add", name="add", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="clubAddCategory",
     *     summary="Add a category to a club.",
     *     path="/api/club/category/{slug}/add",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="slug",
     *              example="my-amazing-club",
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
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Club.",
     *          @OA\JsonContent(ref=@Model(type=Club::class, groups={"view", "list"})),
     *     ),
     *     @OA\Response(response="404", description="An error occurred."),
     * )
     *
     * @Security(name="Bearer")
     */
    public function add(Club $club, Request $request): Response
    {
        $categorySlug = $request->query->get('category');

        if (is_null($categorySlug)) {
            return $this->buildNotFoundResponse('No category provided.');
        }

        $category = $this->categoryRepository->findOneBy(['slug' => $categorySlug]);
        $club->addCategory($category);

        try {
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('Club\CategoryController::add - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('Club\CategoryController::add - Category %s created', $category->getSlug()));

        return $this->buildEntityResponse($club, $request, [], ['view', 'list']);
    }
}
