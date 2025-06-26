<?php

namespace App\Service\Vehicle;

use App\DTO\Vehicle\CreateVehicleDto;
use App\DTO\Vehicle\UpdateVehicleDto;
use App\DTO\Vehicle\VehicleResponseDto;
use App\Entity\User\Seller;
use App\Entity\Vehicle;
use App\Mapper\Vehicle\VehicleMapper;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class VehicleService
 * Contains all business logic to interact with Vehicle entities.
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

    /**
     * Finds all vehicles belonging to a specific seller, ordered by creation date.
     *
     * @param Seller $seller The seller whose vehicles to find.
     * @return VehicleResponseDto[] An array of vehicle response DTOs.
     */
    public function findVehiclesBySeller(Seller $seller): array
    {
        $vehicles = $this->repository->findBy(['seller' => $seller], ['createdAt' => 'DESC']);

        $responseDtos = [];

        foreach ($vehicles as $vehicle) {
            $responseDtos[] = $this->mapper->fromEntityToResponseDto($vehicle);
        }

        return $responseDtos;
    }

    /**
     * Finds a single vehicle by its entity and returns its DTO representation.
     *
     * @param Vehicle $vehicle The vehicle entity.
     * @return VehicleResponseDto The DTO representation of the vehicle.
     */
    public function findVehiclebyId(Vehicle $vehicle): VehicleResponseDto
    {
        return $this->mapper->fromEntityToResponseDto($vehicle);
    }

    /**
     * Updates a vehicle's data from a DTO.
     *
     * @param Vehicle $vehicle The vehicle entity to update.
     * @param UpdateVehicleDto $dto The DTO containing the new data.
     * @return VehicleResponseDto The updated vehicle data as a DTO.
     */
    public function updateVehicle(Vehicle $vehicle, UpdateVehicleDto $dto): VehicleResponseDto
    {
        $this->mapper->fromUpdateDtoToEntity($vehicle, $dto);

        $this->em->flush();

        return $this->mapper->fromEntityToResponseDto($vehicle);
    }

    /**
     * Deletes a vehicle from the database.
     *
     * @param Vehicle $vehicle The vehicle entity to delete.
     */
    public function deleteVehicle(Vehicle $vehicle): void
    {
        $this->em->remove($vehicle);
        $this->em->flush();
    }
}
