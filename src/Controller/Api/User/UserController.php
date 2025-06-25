<?php

namespace App\Controller\Api\User;


use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Attribute\Model;
use App\DTO\User\SellerResponseDto;
use App\Service\User\UserService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\DTO\User\CreateSellerDto;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[OA\Tag(name: 'Sellers')]
#[Route('/api/sellers', 'api_sellers_')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }


    #[Route('', 'create', methods: ['POST'])]
    #[OA\Post(
        summary: "Create a new seller account",
        description: "Registers a new user with the 'seller' role."
    )]
    #[OA\RequestBody(
        description: "Data needed to create a new seller.",
        required: true,
        content: new Model(type: CreateSellerDto::class)
    )]
    #[OA\Response(
        response: 201,
        description: "Seller created successfully. Returns the new seller's public data.",
        content: new Model(type: SellerResponseDto::class)
    )]
    #[OA\Response(
        response: 409,
        description: "Conflict. The email address is already in use.",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'error', type: 'string', example: 'Data conflict'),
                new OA\Property(property: 'message', type: 'string', example: 'This email is already used.')
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: "Validation Error. The data provided in the request body is invalid (e.g., blank field, invalid email format)."
    )]
    #[OA\Response(
        response: 400,
        description: "Bad Request. A generic error occurred."
    )]
    public function create(#[MapRequestPayload] CreateSellerDto $dto): JsonResponse
    {
        try {
            $responseDto = $this->userService->createSeller($dto);
            return $this->json($responseDto, Response::HTTP_CREATED);
        } catch (UniqueConstraintViolationException $e) {
            return $this->json([
                'error' => 'Data conflict',
                'message' => 'This email is already used.'
            ], Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'An unexpected error occurred',
                'message' => 'Could not create seller.',
                'debug' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}