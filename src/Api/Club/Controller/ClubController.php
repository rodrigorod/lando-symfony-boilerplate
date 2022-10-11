<?php

namespace App\Api\Club\Controller;

use App\Api\Club\Entity\Club;
use App\Api\Club\Entity\ClubPatchPayload;
use App\Api\Club\Service\ClubService;
use App\Controller\EndpointController;
use App\DependencyInjection\SecurityAwareTrait;
use App\Repository\CategoryRepository;
use App\Repository\ClubRepository;
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
 * @Route("/api/club", name="api_club_")
 *
 * @OA\Tag(
 *     name="Club",
 *     description="Club information."
 * )
 */
class ClubController extends EndpointController
{
    use SecurityAwareTrait;

    public function __construct(
        protected EntityManagerInterface $em,
        protected LoggerInterface $logger,
        protected ClubRepository $clubRepository,
        protected ClubService $clubService,
        protected SerializerInterface $serializer,
        protected CategoryRepository $categoryRepository,
    ) {}

    /**
     * @Route("/list", name="list", methods={"GET"})
     *
     * @IsGranted("PUBLIC_ACCESS")
     *
     * @OA\Get(
     *     operationId="clubListGet",
     *     summary="Get clubs list.",
     *     path="/api/club/list",
     *     @OA\Response(
     *          response="200",
     *          description="Club list.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref=@Model(type=Club::class, groups={"view", "list"})),
     *          ),
     *     ),
     * )
     */
    public function list(Request $request): Response
    {
        $categorySlug = $request->query->get('categories');

        if (is_null($categorySlug)) {
            return $this->buildEntityResponse($this->clubRepository->findAll(), $request);
        }

        $clubs = $this->clubRepository->getClubsByCategory($categorySlug);

        return $this->buildEntityResponse($clubs, $request, [], ['view', 'list']);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     *
     * @OA\Post(
     *     operationId="clubCreate",
     *     summary="Create new club.",
     *     path="/api/club/create",
     *     @OA\Response(
     *          response="200",
     *          description="Club.",
     *          @OA\JsonContent(ref=@Model(type=Club::class, groups={"view", "list", "category"})),
     *     ),
     *     @OA\Response(response="404", description="An error occurred."),
     * )
     */
    public function create(Request $request): Response
    {
        $user = $this->getUser();
        $requestContent = (array) $this->getContentFromRequest($request);

        if (!$user->isActive()) {
            return $this->buildNotFoundResponse('E-mail must be confirmed in order to create a club.');
        }

        $club = new Club($requestContent);
        $club->setOwner($user);

        try {
            $this->em->persist($club);
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('ClubController::create - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('ClubController::create - User %s created club %s', $user->getUserIdentifier(), $club->getId()));

        return $this->buildEntityResponse($club, $request, [], ['view', 'list', 'category']);
    }

    /**
     * @Route("/{slug}", name="get", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="clubGet",
     *     summary="Get club.",
     *     path="/api/club/{slug}",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              description="Club slug.",
     *              example="my-amazing-club",
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Club.",
     *          @OA\JsonContent(ref=@Model(type=Club::class)),
     *     ),
     * )
     */
    public function get(Club $club, Request $request): Response
    {
        return $this->buildEntityResponse($club, $request);
    }

    /**
     * @Route("/update/{slug}", name="update", methods={"PATCH"})
     *
     * @OA\Patch(
     *     operationId="clubUpdate",
     *     summary="Update club.",
     *     path="/api/club/update/{slug}",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              description="Club slug.",
     *              example="my-amazing-club",
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref=@Model(type=ClubPatchPayload::class)),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Updated club.",
     *          @OA\JsonContent(ref=@Model(type=Club::class)),
     *     ),
     *     @OA\Response(response="404", description="An error occurred."),
     * )
     *
     * @Security(name="Bearer")
     */
    public function update(Club $club, Request $request): Response
    {
        $user = $this->getUser();

        if ($club->getOwner()->getId() !== $user->getId()) {
            return $this->buildUnauthorizedResponse();
        }

        $payload = $this->serializer->deserialize(
            $this->getContentFromRequest($request, false),
            ClubPatchPayload::class,
            'json'
        );

        try {
            $updatedClub = $this->clubService->updateClub($payload, $club);
        } catch (Exception $e) {
            $this->logger->error(sprintf('ClubController::update - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        $this->logger->info(sprintf('ClubController::update - User %s updated club %s', $user->getUserIdentifier(), $club->getId()));

        return $this->buildEntityResponse($updatedClub, $request);
    }

    /**
     * @Route("/delete/{slug}", name="delete", methods={"DELETE"})
     *
     * @OA\Delete(
     *     operationId="clubDelete",
     *     summary="Delete club.",
     *     path="/api/club/delete/{slug}",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              description="Club slug.",
     *              example="my-amazing-club",
     *          ),
     *     ),
     *     @OA\Response(response="404", description="An error occurred."),
     *     @OA\Response(response="202", description="Club deleted"),
     * )
     *
     * @Security(name="Bearer")
     */
    public function delete(Club $club, Request $request): Response
    {
        $user = $this->getUser();

        if ($club->getOwner()->getId() !== $user->getId()) {
            return $this->buildUnauthorizedResponse();
        }

        try {
            $this->logger->info(sprintf('ClubController::delete - User %s deleted club %s', $user->getUserIdentifier(), $club->getId()));

            $this->em->remove($club);
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('ClubController::delete - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        return $this->buildAcceptedResponse('Club deleted');
    }

    /**
     * @Route("/join/{slug}", name="join", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="clubJoin",
     *     summary="Join club.",
     *     path="/api/club/join/{slug}",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              description="Club slug.",
     *              example="my-amazing-club",
     *          ),
     *     ),
     *     @OA\Response(response="404", description="User already joined the club."),
     *     @OA\Response(response="202", description="Club joined."),
     * )
     *
     * @Security(name="Bearer")
     */
    public function join(Club $club, Request $request): Response
    {
        $user = $this->getUser();

        if ($user->getClubs()->contains($club)) {
            return $this->buildNotFoundResponse('User already joined the club.');
        }

        $user->addClub($club);
        $club->addMember($user);

        try {
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('ClubController::join - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        return $this->buildAcceptedResponse();
    }

    /**
     * @Route("/leave/{slug}", name="leave", methods={"GET"})
     *
     * @OA\Get(
     *     operationId="clubLeave",
     *     summary="Leave club.",
     *     path="/api/club/leave/{slug}",
     *     @OA\Parameter(
     *          name="slug",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              description="Club slug.",
     *              example="my-amazing-club",
     *          ),
     *     ),
     *     @OA\Response(response="404", description="User already left the club."),
     *     @OA\Response(response="202", description="Club left."),
     * )
     *
     * @Security(name="Bearer")
     */
    public function leave(Club $club, Request $request): Response
    {
        $user = $this->getUser();

        if (!$user->getClubs()->contains($club)) {
            return $this->buildNotFoundResponse('User already left the club.');
        }

        $user->removeClub($club);
        $club->removeMember($user);

        try {
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error(sprintf('ClubController::leave - %s', $e->getMessage()));

            return $this->buildNotFoundResponse('An error occurred.');
        }

        return $this->buildAcceptedResponse();
    }
}
