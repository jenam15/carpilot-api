<?php

namespace App\Controller\Api\Vehicle;

use App\DTO\Vehicle\CreateVehicleDto;
use App\DTO\Vehicle\VehicleResponseDto;
use App\Entity\User\Seller;
use App\Service\Vehicle\VehicleService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/**
 * Handles CRUD operations for Seller's vehicles.
 */
#[Route('/api/vehicles', name: 'api_vehicle_')]
#[OA\Tag(name: 'Vehicles')]
#[Security(name: 'bearerAuth')]
class VehicleController extends AbstractController
{
    public function __construct(
        private readonly VehicleService $vehicleService
    ) {
    }


    #[Route('/create', name: 'create', methods: ['POST'])]
    #[OA\Post(
        summary: "Create a new vehicle",
        description: "Allows an authenticated seller to create a new vehicle and link it to their account."
    )]
    #[OA\RequestBody(
        description: "Data required to create a new vehicle",
        required: true,
        content: new Model(type: CreateVehicleDto::class)
    )]
    #[OA\Response(
        response: 201,
        description: "Vehicle created successfully",
        content: new Model(type: VehicleResponseDto::class)
    )]
    #[OA\Response(
        response: 403,
        description: "Access denied. The user is not authenticated or does not have the 'Seller' role."
    )]
    #[OA\Response(
        response: 409,
        description: "Conflict. A vehicle with the same plate or VIN already exists."
    )]
    #[OA\Response(
        response: 422,
        description: "Validation error. The request body is invalid or missing required fields."
    )]
    public function create(
        #[MapRequestPayload] CreateVehicleDto $dto,
        #[CurrentUser] ?Seller $seller
    ): JsonResponse {
        if (!$seller) {
            return new JsonResponse([
                'message' => 'Forbidden access. You must be logged in as a seller.'
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            $responseDto = $this->vehicleService->createVehicle($dto, $seller);
            return $this->json($responseDto, Response::HTTP_CREATED);

        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse([
                'error' => 'Data conflict',
                'message' => 'A vehicle with this license plate already exists.'
            ], Response::HTTP_CONFLICT);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'An unexpected error occurred',
                'message' => 'Could not create the vehicle.',
                'debug' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}