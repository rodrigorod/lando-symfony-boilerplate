<?php

namespace App\Api\User\Controller;

use App\Api\User\Entity\User;
use App\Api\Club\Entity\Club;
use App\Controller\EndpointController;
use App\DependencyInjection\SecurityAwareTrait;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/user/club", name="api_user_club_")
 *
 * @OA\Tag(
 *     name="User",
 *     description="User informations.",
 * )
 */
class ClubController extends EndpointController
{
    use SecurityAwareTrait;

    /**
     * @Route("/{username}/list", name="external_list", methods={"GET"})
     *
     * @IsGranted("PUBLIC_ACCESS")
     *
     * @OA\Get(
     *     operationId="clubExternalList",
     *     summary="List of club from external user.",
     *     path="/api/user/club/{username}/list",
     *     @OA\Parameter(
     *          name="username",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              example="johndoe",
     *          ),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Club list.",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref=@Model(type=Club::class, groups={"view", "list"}))
     *          ),
     *     ),
     * )
     */
    public function externalList(User $user, Request $request): Response
    {
        $clubs = $user->getClubs();

        return $this->buildEntityResponse($clubs, $request, [], ['view', 'list']);
    }
}
