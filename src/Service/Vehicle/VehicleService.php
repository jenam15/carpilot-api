<?php

namespace App\Service\Vehicle;

use App\DTO\Vehicle\UpdateVehicleDto;
use App\Entity\Vehicle;
use App\Entity\User\Seller;
use App\DTO\Vehicle\CreateVehicleDto;
use App\Mapper\Vehicle\VehicleMapper;
use App\Repository\VehicleRepository;
use App\DTO\Vehicle\VehicleResponseDto;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class VehicleService
 * Contains all logic to interact with Vehicle entities.
 */
class VehicleService
{
    /**
     * @param EntityManagerInterface $em The entity manager for database writes (persist, flush, remove).
     * @param VehicleRepository $repository The repository for database reads (find, findBy).
     * @param VehicleMapper $mapper The mapper to translate between DTOs and Entities.
     */
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly VehicleRepository $repository,
        private readonly VehicleMapper $mapper
    ) {
    }

    /**
     * Creates a new vehicle for a given seller and returns its corresponding Response DTO.
     *
     * @param CreateVehicleDto $dto The DTO containing the new vehicle's data.
     * @param Seller $seller The owner of the new vehicle.
     * @return VehicleResponseDto The DTO representing the newly created vehicle.
     */
    public function createVehicle(CreateVehicleDto $dto, Seller $seller): VehicleResponseDto
    {
        $vehicle = $this->mapper->fromCreateDtoToEntity($dto);
        $vehicle->setSeller($seller);

        $this->em->persist($vehicle);
        $this->em->flush();

        return $this->mapper->fromEntityToResponseDto($vehicle);
    }


    public function findVehiclesBySeller(Seller $seller)
    {
        $vehicles = $this->repository->findBy(['seller' => $seller], ['createdAt' => 'DESC']);

        $responseDtos = [];

        foreach ($vehicles as $vehicle) {
            $responseDtos[] = $this->mapper->fromEntityToResponseDto($vehicle);
        }

        return $responseDtos;
    }


    public function findVehiclebyId(Vehicle $vehicle)
    {
        return $this->mapper->fromEntityToResponseDto($vehicle);
    }

    public function updateVehicle(Vehicle $vehicle, UpdateVehicleDto $dto)
    {
        $this->mapper->fromUpdateDtoToEntity($vehicle, $dto);

        $this->em->flush();

        return $this->mapper->fromEntityToResponseDto($vehicle);
    }

    public function deleteVehicle(Vehicle $vehicle)
    {
        $this->em->remove($vehicle);
        $this->em->flush();
    }

}