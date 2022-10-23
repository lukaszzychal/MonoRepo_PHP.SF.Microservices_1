<?php

namespace App\US\Infrastructure\Http;

use App\US\Infrastructure\TokenRequest\RequiredTokenRequestInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/', name: 'app_index', methods: [Request::METHOD_GET])]
    public function index(): JsonResponse
    {
        return new JsonResponse('Project [P_US] User Story');
    }
}
