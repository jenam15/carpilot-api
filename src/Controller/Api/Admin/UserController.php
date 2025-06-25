<?php

namespace App\Controller\Api\Admin;

use App\DTO\User\Admin\UserResponseDto;
use App\Service\User\Admin\UserService;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Admin controller for user management.
 */
#[Route('/api/admin/users', name: 'api_admin_user_')]
#[IsGranted('ROLE_ADMIN')]
#[OA\Tag(name: 'Admin - Users')]
#[Security(name: 'bearerAuth')]
final class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/', name: 'list', methods: ['GET'])]
    #[OA\Get(
        summary: "List all users",
        description: "Retrieves a list of all users in the system. Requires ADMIN role."
    )]
    #[OA\Response(
        response: 200,
        description: "Returns the list of users.",
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserResponseDto::class))
        )
    )]
    #[OA\Response(
        response: 401,
        description: "Authentication required. JWT token is missing or invalid."
    )]
    #[OA\Response(
        response: 403,
        description: "Access Denied. User does not have the required role."
    )]
    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();

        return new JsonResponse($users);
    }
}