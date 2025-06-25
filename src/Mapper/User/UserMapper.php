<?php

namespace App\Mapper\User;

use App\Entity\User\Seller;
use App\DTO\User\CreateSellerDto;
use App\DTO\User\SellerResponseDto;


/**
 * Class UserMapper
 *
 * This class is responsible for converting User entities into a detailed
 * UserResponseDto for the seller panel.
 */
class UserMapper
{
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
}
