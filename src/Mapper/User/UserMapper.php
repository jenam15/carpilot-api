<?php

namespace App\Mapper\User;

use App\Entity\User\Seller;
use App\DTO\User\CreateSellerDto;
use App\DTO\User\UpdateSellerDto;
use App\DTO\User\SellerResponseDto;
use App\Mapper\Vehicle\VehicleMapper;


/**
 * Class UserMapper
 *
 * This class is responsible for converting User entities into a detailed
 * UserResponseDto for the seller panel.
 */
class UserMapper
{

    public function __construct(
        private readonly VehicleMapper $vehicleMapper
    ) {
    }
    /**
     * Transforms a Seller entity into a SellerResponseDto.
     * This DTO is a safe representation of the seller's data, intended for API responses.
     * It excludes sensitive information like the password.
     *
     * @param Seller $seller The Seller entity coming from the database.
     * @return SellerResponseDto The DTO ready to be sent as a JSON response.
     */
    public function fromEntityToSellerResponseDto(Seller $seller): SellerResponseDto
    {
        $vehicleDtos = [];
        foreach ($seller->getVehicles() as $vehicle) {
            $dto = $this->vehicleMapper->fromEntityToResponseDto($vehicle);

            $vehicleDtos[] = $dto;
        }

        return new SellerResponseDto(
            $seller->getId(),
            $seller->getFirstName(),
            $seller->getLastName(),
            $seller->getEmail(),
            $seller->getPhone(),
            $seller->getAddress(),
            $seller->getCity(),
            $seller->getPostalCode(),
            $seller->getCountry(),
            $vehicleDtos,
            $seller->getCreatedAt()
        );
    }

    /**
     * Transforms a CreateSellerDto into a new Seller entity.
     * This method prepares the entity to be persisted. 
     *
     * @param CreateSellerDto $dto The data transfer object from the API request.
     * @return Seller The newly created Seller entity, ready for further processing.
     */
    public function fromCreateSellerDtoToEntity(CreateSellerDto $dto): Seller
    {
        $seller = new Seller();

        $seller->setFirstName($dto->firstName);
        $seller->setLastName($dto->lastName);
        $seller->setEmail($dto->email);
        $seller->setPhone($dto->phone);
        $seller->setAddress($dto->address);
        $seller->setCity($dto->city);
        $seller->setPostalCode($dto->postalCode);
        $seller->setCountry($dto->country);

        return $seller;
    }

    /**
     * Applies changes from an UpdateSellerDto to an existing Seller entity.
     * This method only updates fields that are not null in the DTO,
     * allowing for partial updates of a seller's profile.
     *
     * @param UpdateSellerDto $dto The DTO containing the data to update.
     * @param Seller $seller The existing Seller entity loaded from the database.
     * @return Seller The same Seller entity instance, now modified.
     */
    public function fromUpdateSellerDtoToEntity(UpdateSellerDto $dto, Seller $seller): Seller
    {
        if ($dto->firstName !== null) {
            $seller->setFirstName($dto->firstName);
        }

        if ($dto->lastName !== null) {
            $seller->setLastName($dto->lastName);
        }

        if ($dto->email !== null) {
            $seller->setEmail($dto->email);
        }

        if ($dto->phone !== null) {
            $seller->setPhone($dto->phone);
        }

        if ($dto->address !== null) {
            $seller->setAddress($dto->address);
        }

        if ($dto->city !== null) {
            $seller->setCity($dto->city);
        }

        if ($dto->postalCode !== null) {
            $seller->setPostalCode($dto->postalCode);
        }

        if ($dto->country !== null) {
            $seller->setCountry($dto->country);
        }

        return $seller;
    }
}
