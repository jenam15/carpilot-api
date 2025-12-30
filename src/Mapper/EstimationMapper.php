<?php

namespace App\Mapper;

use App\DTO\Vehicle\EstimationResponseDto;
use App\Entity\Estimation;

class EstimationMapper
{
    public function fromEntityToResponseDto(Estimation $estimation)
    {
        return new EstimationResponseDto(
            $estimation->getId(),
            $estimation->getStatus()->value,
            $estimation->getEstimatedPrice(),
            $estimation->getOfferPrice(),
            $estimation->getCreatedAt()
        );
    }
}