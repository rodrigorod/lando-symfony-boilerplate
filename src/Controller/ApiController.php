<?php

namespace App\Controller;

use App\Api\Car\Entity\ModificationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     *
     * @IsGranted("PUBLIC_ACCESS")
     */
    public function index(): Response
    {
        $modifications = new ModificationType();

        return new JsonResponse([
            'hello' => $modifications->all(),
        ]);
    }
}
