<?php

namespace App\External\MockApiPlate\Dto;

use OpenApi\Attributes as OA;

/**
 * Représente la réponse structurée retournée par la mock API d'immatriculation.
 */

#[OA\Schema(
    title: "PlateLookupResponseDto",
    description: "Structured response for mock license plate lookup"
)]
class PlateLookupResponseDto
{
    #[OA\Property(type: "string", example: "AA123BB")]
    public string $plate;

    #[OA\Property(type: "string", example: "Renault")]
    public string $brand;

    #[OA\Property(type: "string", example: "Clio")]
    public string $model;
    public function __construct(
        string $plate,
        string $brand,
        string $model
    ) {
        $this->plate = $plate;
        $this->brand = $brand;
        $this->model = $model;
    }
}