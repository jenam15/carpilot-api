<?php

namespace App\Controller\Api\Admin;

use App\Service\User\Admin\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api/admin/users', name: 'api_admin_user_')]
final class UserController extends AbstractController
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    #[Route('/', name: 'list')]
    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();

        return new JsonResponse($users, JsonResponse::HTTP_OK);

    }
}
