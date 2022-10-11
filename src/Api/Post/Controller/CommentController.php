<?php

namespace App\Api\Post\Controller;

use App\Api\Post\Entity\Comment;
use App\Api\Post\Entity\Post;
use App\Controller\EndpointController;
use App\DependencyInjection\EntityManagerAwareTrait;
use App\DependencyInjection\LoggerAwareTrait;
use App\DependencyInjection\SecurityAwareTrait;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/post/{slug}/comment", name="api_post_comment_")
 *
 * @ParamConverter(name="post", class=Post::class, options={"mapping": {"slug": "slug"}})
 *
 * @OA\Tag(
 *     name="Post",
 *     description="Post informations."
 * )
 */
class CommentController extends EndpointController
{
    use SecurityAwareTrait;
    use EntityManagerAwareTrait;
    use LoggerAwareTrait;

    /**
     * @Route("/list", name="list", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="postCommentList",
     *     summary="List post comments.",
     *     path="/api/post/{slug}/comment/list",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              description="Post slug.",
     *              example="my-amazing-post-slug"
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Comments list.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref=@Model(type=Comment::class, groups={"comment"}))
     *          )
     *     )
     * )
     *
     * @IsGranted("PUBLIC_ACCESS")
     */
    public function list(Post $post, Request $request): Response
    {
        return $this->buildEntityResponse($post->getComments(), $request, [], ['comment']);
    }

    /**
     * @Route("/add", name="add", methods={"POST"})
     *
     * @OA\Post(
     *     operationId="postCommentAdd",
     *     summary="Add post comment.",
     *     path="/api/post/{slug}/comment/add",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              description="Post slug.",
     *              example="my-amazing-post-slug"
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Comment informations.",
     *          @OA\JsonContent(ref=@Model(type=Comment::class))
     *     ),
     *     @OA\Response(response="404", description="An error occurred.")
     * )
     */
    public function add(Post $post, Request $request): Response
    {
        $requestContent = (array) $this->getContentFromRequest($request);
        $comment = new Comment($requestContent['message']);

        $comment
            ->setAuthor($this->getUser())
            ->setPost($post)
        ;

        $post->addComment($comment);

        try {
            $this->em->persist($comment);
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('CommentController::add - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf(
            'CommentController::add - User %s added comment %s',
            $this->getUser()->getUserIdentifier(),
            $comment->getId()
        ));

        return $this->buildEntityResponse($comment, $request);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     *
     * @OA\Delete(
     *     operationId="postCommentDelete",
     *     summary="Delete post comment.",
     *     path="/api/post/{slug}/comment/delete/{id}",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              description="Post slug.",
     *              example="my-amazing-post-slug"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              format="uuid",
     *              description="Comment unique identifier.",
     *              example="1ed42fe2-16f6-6368-98b6-d93168bb499c"
     *          )
     *     ),
     *     @OA\Response(
     *          response="202",
     *          description="Comment deleted."
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="An error occurred."
     *     )
     * )
     */
    public function delete(Post $post, Comment $comment, Request $request): Response
    {
        $user = $this->getUser();

        if ($user->getId() !== $comment->getAuthor()->getId()) {
            return $this->buildUnauthorizedResponse();
        }

        try {
            $post->removeComment($comment);

            $this->em->remove($comment);
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('CommentController::delete - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        return $this->buildAcceptedResponse('Comment deleted.');
    }

    /**
     * @Route("/{id}/like", name="like", methods={"GET"})
     */
    public function like(Post $post, Comment $comment, Request $request): Response
    {
        $user = $this->getUser();

        if ($comment->getLikes()->contains($user)) {
            return $this->buildNotFoundResponse('Comment already liked.');
        }

        $comment->addLike($user);
        $user->addLikedComment($comment);

        try {
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('CommentController::like - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('CommentController::like - User %s liked comment : %s', $user->getUserIdentifier(), $comment->getId()));

        return $this->buildAcceptedResponse('Comment liked.');
    }

    /**
     * @Route("/{id}/unlike", name="unlike", methods={"GET"})
     */
    public function unlike(Post $post, Comment $comment, Request $request): Response
    {
        $user = $this->getUser();

        if (!$comment->getLikes()->contains($user)) {
            return $this->buildNotFoundResponse('Comment not liked.');
        }

        $comment->removeLike($user);
        $user->removeLikedComment($comment);

        try {
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('CommentController::unlike - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('CommentController::unlike - User %s unliked comment : %s', $user->getUserIdentifier(), $comment->getId()));

        return $this->buildAcceptedResponse('Comment unliked.');
    }
}


