<?php

namespace App\Api\Category\Controller;

use App\Api\Category\Entity\Category;
use App\Controller\EndpointController;
use App\DependencyInjection\EntityManagerAwareTrait;
use App\DependencyInjection\LoggerAwareTrait;
use App\DependencyInjection\SecurityAwareTrait;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

/**
 * @Route("/api/category", name="api_category_")
 */
class CategoryController extends EndpointController
{
    use LoggerAwareTrait;
    use EntityManagerAwareTrait;
    use SecurityAwareTrait;

    /**
     * @Route("/create", name="create")
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

            return $this->buildNotFoundResponse('An error occurred');
        }

        $this->logger->info(sprintf('CategoryController::create - User : %s created Category : %s', $this->getUser()->getUserIdentifier(), $category->getId()));

        return $this->buildEntityResponse($category, $request, [], ['category']);
    }

    /**
     * @Route("/list", name="list")
     */
    public function list(Request $request, CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();

        return $this->buildEntityResponse($categories, $request, [], ['list']);
    }
}


