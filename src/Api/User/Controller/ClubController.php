<?php

namespace App\Api\User\Controller;

use App\Api\User\Entity\User;
use App\Controller\EndpointController;
use App\DependencyInjection\SecurityAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user/club", name="api_user_club_")
 */
class ClubController extends EndpointController
{
    use SecurityAwareTrait;

    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function list(Request $request): Response
    {
        $user = $this->getUser();

        return $this->buildEntityResponse($user, $request, [], ['clubs']);
    }

    /**
     * @Route("/{username}/list", name="external_list", methods={"GET"})
     */
    public function externalList(User $user, Request $request): Response
    {
        $clubs = $user->getClubs();

        return $this->buildEntityResponse($clubs, $request);
    }
}
