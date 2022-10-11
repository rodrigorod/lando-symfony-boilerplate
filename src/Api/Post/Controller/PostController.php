<?php

namespace App\Api\Post\Controller;

use App\Api\Club\Entity\Club;
use App\Api\Post\Entity\Comment;
use App\Api\Post\Entity\Post;
use App\Controller\EndpointController;
use App\DependencyInjection\EntityManagerAwareTrait;
use App\DependencyInjection\LoggerAwareTrait;
use App\DependencyInjection\SecurityAwareTrait;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/post", name="api_post_")
 *
 * @OA\Tag(
 *     name="Post",
 *     description="Post informations",
 * )
 */
class PostController extends EndpointController
{
    use SecurityAwareTrait;
    use LoggerAwareTrait;
    use EntityManagerAwareTrait;

    /**
     * @Route("/{slug}/create", name="create", methods={"POST"})
     *
     * @OA\Post(
     *     operationId="postCreate",
     *     summary="Create new post.",
     *     path="/api/post/create",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          description="Club slug.",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="slug",
     *              example="the-amazing-club",
     *          ),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Post.",
     *          @OA\JsonContent(ref=@Model(type=Post::class, groups={"view"})),
     *     ),
     *     @OA\Response(response="401", description="User must join the club first in order to create a post."),
     *     @OA\Response(response="404", description="E-mail must be confirmed in order to create a post."),
     *     @OA\Response(response="404", description="An error occurred."),
     * )
     *
     * @Security(name="Bearer")
     */
    public function create(Club $club, Request $request): Response
    {
        $user = $this->getUser();
        $requestContent = (array) $this->getContentFromRequest($request);

        if (!$user->isActive()) {
            return $this->buildNotFoundResponse('E-mail must be confirmed in order to create a post.');
        }

        if (!$user->getClubs()->contains($club)) {
            return $this->buildUnauthorizedResponse('User must join the club first in order to create a post.');
        }

        $post = new Post($requestContent);
        $post->setUser($user)
            ->setClub($club)
        ;
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
     * @Route("/{slug}", name="get", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="postGet",
     *     summary="Get post.",
     *     path="/api/post/{slug}",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          description="Post slug.",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="slug",
     *              example="my-amazing-post",
     *          ),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Post.",
     *          @OA\JsonContent(ref=@Model(type=Post::class)),
     *     ),
     * )
     *
     * @Security(name="Bearer")
     */
    public function get(Post $post, Request $request): Response
    {
        return $this->buildEntityResponse($post, $request);
    }

    /**
     * @Route("/{slug}/like", name="like", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="postLike",
     *     summary="Like post.",
     *     path="/api/post/{slug}/like",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          description="Post slug.",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="slug",
     *              example="my-amazing-post",
     *          ),
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Post already liked.",
     *     ),
     *     @OA\Response(
     *          response="202",
     *          description="Post liked.",
     *     ),
     * )
     *
     * @Security(name="Bearer")
     */
    public function like(Post $post, Request $request): Response
    {
        $user = $this->getUser();

        if ($post->getLikes()->contains($user)) {
            return $this->buildNotFoundResponse('Post already liked.');
        }

        $post->addLike($user);
        $user->addLikedPost($post);

        try {
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('CommentController::like - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('PostController::like - User %s liked post : %s', $user->getUserIdentifier(), $post->getId()));

        return $this->buildAcceptedResponse('Post liked.');
    }

    /**
     * @Route("/{slug}/unlike", name="unlike", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="postUnlike",
     *     summary="Unlike post.",
     *     path="/api/post/{slug}/unlike",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          description="Post slug.",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="slug",
     *              example="my-amazing-post",
     *          ),
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Post not liked.",
     *     ),
     *     @OA\Response(
     *          response="202",
     *          description="Post unliked.",
     *     ),
     * )
     *
     * @Security(name="Bearer")
     */
    public function unlike(Post $post, Request $request): Response
    {
        $user = $this->getUser();

        if (!$post->getLikes()->contains($user)) {
            return $this->buildNotFoundResponse('Post not liked.');
        }

        $post->removeLike($user);
        $user->removeLikedPost($post);

        try {
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('PostController::unlike - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('PostController::unlike - User %s unliked post : %s', $user->getUserIdentifier(), $post->getSlug()));

        return $this->buildAcceptedResponse('Post unliked.');
    }
}
