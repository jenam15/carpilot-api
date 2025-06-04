<?php

namespace App\External\MockApiPlate\Dto;

/**
 * Représente la réponse structurée retournée par la mock API d'immatriculation.
 */
class PlateLookupResponseDto
{
    public function __construct(
        public readonly string $plate,
        public readonly string $brand,
        public readonly string $model,
    ) {
    }
}