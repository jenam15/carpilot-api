<?php

namespace App\External\MockApiPlate\Mapper;

use App\External\MockApiPlate\Dto\PlateLookupResponseDto;



class PlateLookupMapper
{

    public static function fromArray(array $data): PlateLookupResponseDto
    {
        return new PlateLookupResponseDto(
            $data['plate'] ?? 'UNKNOWN',
            $data['vin'] ?? null,
            $data['brand'] ?? 'UNKNOWN',
            $data['model'] ?? 'UNKNOWN',
            $data['version'] ?? null,
            $data['energy'] ?? null,
            $data['fiscalPower'] ?? null,
            $data['gearBox'] ?? null,
            $data['doors'] ?? null,
            $data['seats'] ?? null,
            $data['bodyType'] ?? null,
            $data['weightKg'] ?? null,
            $data['color'] ?? null,
            $data['registrationDate'] ?? null
        );
    }

}