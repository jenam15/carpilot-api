<?php

namespace App\External\MockApiPlate\Controller;

use App\External\MockApiPlate\Data\MockPlateDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\Attribute\Security;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'External mock API')]
class PlateLookupController extends AbstractController
{
    public function __construct
    (
        private readonly MockPlateDataProvider $dataProvider
    ) {
    }

    #[Route(path: '/mock/plate-lookup/{plate}', name: 'mock_plate', methods: ['GET'])]
    public function _invoke($plate)
    {
        if (empty($plate)) {
            return $this->json([
                'error' => true,
                'message' => 'Missing plate.'
            ], 400);
        }

        $vehicle = $this->dataProvider->findByPlate($plate);

        if ($vehicle->plate === 'UNKNOWN') {
            return $this->json([
                'error' => true,
                'message' => 'Plate not recognized',
                'data' => $vehicle
            ]);
        }

        return $this->json([
            'error' => false,
            'data' => $vehicle
        ]);
    }


}