<?php
namespace App\Controller\Api\Public;


use App\DTO\Public\EstimationRequestDto;
use App\DTO\Public\PlateLookupRequestDto;
use App\Service\Public\EstimationService;
use App\Service\Public\VehicleLookupService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;

/**
 * Controller responsible for handling estimation-related API requests.
 *
 * This class provides endpoints for public estimation operations.
 */
#[Route('/api/estimations', 'api_public_estimation_')]
final class EstimationController extends AbstractController
{
    public function __construct(
        private readonly VehicleLookupService $lookupService,
        private readonly EstimationService $estimationService
    ) {
    }


    #[Route('/lookup-by-plate', 'plate_lookup', methods: ['POST'])]
    public function lookupByPlate(#[MapRequestPayload] PlateLookupRequestDto $dto)
    {
        try {
            $vehicleData = $this->lookupService->lookupByPlate($dto->plate);

            return new JsonResponse($vehicleData);
        } catch (\Exception $e) {
            return new JsonResponse(status: 503);
        }



    }

    #[Route('/calculate', 'calculate', methods: ['POST'])]

    public function calculate(#[MapRequestPayload] EstimationRequestDto $dto)
    {
        $token = $this->estimationService->calculateAndCache($dto);

        return new JsonResponse([
            'estimation_token' => $token
        ]);

    }




}