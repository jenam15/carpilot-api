<?php

namespace App\Mapper\Vehicle;

use App\DTO\Vehicle\CreateVehicleDto;
use App\DTO\Vehicle\VehicleResponseDto;
use App\Entity\Vehicle;

/**
 * Class VehicleMapper
 * This class is responsible for converting Vehicle DTOs to Entities and vice-versa.
 * It acts as a translation layer between the API data structure and the database entity.
 */
class VehicleMapper
{
    /**
     * Transforms a CreateVehicleDto into a new Vehicle entity.
     *
     * @param CreateVehicleDto $dto The data transfer object from the API request.
     * @return Vehicle The newly created Vehicle entity, ready to be persisted.
     */
    public function fromCreateDtoToEntity(CreateVehicleDto $dto)
    {
        $vehicle = new Vehicle();

        $vehicle->setPlate($dto->plate);
        $vehicle->setVin($dto->vin);
        $vehicle->setBrand($dto->brand);
        $vehicle->setModel($dto->model);
        $vehicle->setVersion($dto->version);
        $vehicle->setEnergy($dto->energy);
        $vehicle->setHorsePower($dto->horsePower);
        $vehicle->setFiscalPower($dto->fiscalPower);
        $vehicle->setGearBox($dto->gearBox);
        $vehicle->setDoors($dto->doors);
        $vehicle->setSeats($dto->seats);
        $vehicle->setBodyType($dto->bodyType);
        $vehicle->setWeightKg($dto->weightKg);
        $vehicle->setColor($dto->color);
        $vehicle->setRegistrationDate(new \DateTimeImmutable($dto->registrationDate));

        return $vehicle;

    }

    /**
     * Transforms a Vehicle entity into a VehicleResponseDto for API responses.
     *
     * @param Vehicle $vehicle The entity coming from the database.
     * @return VehicleResponseDto The response DTO with safe and formatted data.
     */
    public function fromEntityToResponseDto(Vehicle $vehicle)
    {
        return new VehicleResponseDto(
            $vehicle->getId(),
            $vehicle->getPlate(),
            $vehicle->getVin(),
            $vehicle->getBrand(),
            $vehicle->getModel(),
            $vehicle->getVersion(),
            $vehicle->getEnergy(),
            $vehicle->getHorsePower(),
            $vehicle->getSeller()->getId(),
            $vehicle->getFiscalPower(),
            $vehicle->getGearBox(),
            $vehicle->getDoors(),
            $vehicle->getSeats(),
            $vehicle->getBodyType(),
            $vehicle->getWeightKg(),
            $vehicle->getColor(),
            $vehicle->getCreatedAt(),
            $vehicle->getUpdatedAt(),
            $vehicle->getUpdatedAt()
        );
    }
}



