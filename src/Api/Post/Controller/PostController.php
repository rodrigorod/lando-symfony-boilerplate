<?php

namespace App\Api\Post\Controller;

use App\Api\Club\Entity\Club;
use App\Api\Post\Entity\Post;
use App\Controller\EndpointController;
use App\DependencyInjection\EntityManagerAwareTrait;
use App\DependencyInjection\LoggerAwareTrait;
use App\DependencyInjection\SecurityAwareTrait;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

/**
 * @Route("/api/post", name="api_post_")
 */
class PostController extends EndpointController
{
    use SecurityAwareTrait;
    use LoggerAwareTrait;
    use EntityManagerAwareTrait;

    /**
     * @Route("/{slug}/create", name="create", methods={"POST"})
     */
    public function create(Club $club, Request $request): Response
    {
        $user = $this->getUser();
        $requestContent = (array) $this->getContentFromRequest($request);

        if (!$user->isActive()) {
            return $this->buildNotFoundResponse('E-mail must be confirmed in order to create a post.');
        }

        if (!$user->getClubs()->contains($club)) {
            return $this->buildNotFoundResponse('User must join the club first in order to create a post.');
        }

        $post = new Post($requestContent);
        $post->setUser($user);
        $club->addPost($post);

        try {
            $this->em->persist($post);
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('PostController::create - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('PostController::create - User %s created club %s', $user->getUserIdentifier(), $post->getId()));

        return $this->buildEntityResponse($post, $request, [], ['view']);
    }

    /**
     * @Route("/{slug}", name="get")
     */
    public function get(Post $post, Request $request): Response
    {
        return $this->buildEntityResponse($post, $request);
    }
}
