<?php

namespace App\External\MockApiPlate\Mapper;

use App\External\MockApiPlate\Dto\PlateLookupResponseDto;



class PlateLookupMapper
{

    public static function fromArray($data)
    {
        return new PlateLookupResponseDto(
            $data['immat'] ?? 'UNKNOWN',
            $data['brand'] ?? 'UNKNOWN',
            $data['model'] ?? 'UNKNOWN',
        );
    }

}