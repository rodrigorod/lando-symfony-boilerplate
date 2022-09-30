<?php

namespace App\Api\Club\Controller;

use App\Api\Club\Entity\Club;
use App\Api\Club\Entity\ClubPatchPayload;
use App\Api\Club\Service\ClubService;
use App\Controller\EndpointController;
use App\DependencyInjection\SecurityAwareTrait;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/club", name="api_club_")
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
    ) {}

    /**
     * @Route("/list", name="list", methods={"GET"})
     *
     * @IsGranted("PUBLIC_ACCESS")
     */
    public function list(Request $request): Response
    {
        $clubs = $this->clubRepository->findAll();

        return $this->buildEntityResponse($clubs, $request);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
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

        return $this->buildEntityResponse($club, $request, []);
    }

    /**
     * @Route("/{slug}", name="get", methods={"GET"})
     */
    public function get(Club $club, Request $request): Response
    {
        return $this->buildEntityResponse($club, $request);
    }

    /**
     * @Route("/update/{slug}", name="update", methods={"PATCH"})
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

            return $this->buildNotFoundResponse('An error occured.');
        }

        $this->logger->info(sprintf('ClubController::update - User %s updated club %s', $user->getUserIdentifier(), $club->getId()));

        return $this->buildEntityResponse($updatedClub, $request);
    }

    /**
     * @Route("/delete/{slug}", name="delete", methods={"DELETE"})
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
     * @Route("/join/{slug}", name="join")
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
     * @Route("/leave/{slug}", name="leave")
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
