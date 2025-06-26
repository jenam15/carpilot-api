<?php

namespace App\Service\User;

use App\DTO\User\ChangePasswordDto;
use App\DTO\User\CreateSellerDto;
use App\DTO\User\SellerResponseDto;
use App\DTO\User\UpdateSellerDto;
use App\Entity\User\Seller;
use App\Mapper\User\UserMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



/**
 * Class UserService
 * Contains all business logic to interact with Seller entities.
 */
class UserService
{
    /**
     * @param UserMapper $mapper The mapper for converting between Seller DTOs and entities.
     * @param UserPasswordHasherInterface $passwordHasher The service for hashing and verifying passwords.
     * @param EntityManagerInterface $em The entity manager for database operations.
     */
    public function __construct(
        private readonly UserMapper $mapper,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $em,
    ) {
    }

    /**
     * Creates a new seller account, hashes the password, and returns a public DTO.
     *
     * @param CreateSellerDto $dto The DTO containing the new seller's data.
     * @return SellerResponseDto The DTO representing the newly created seller.
     */
    public function createSeller(CreateSellerDto $dto): SellerResponseDto
    {
        $seller = $this->mapper->fromCreateSellerDtoToEntity($dto);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $seller,
            $dto->password
        );

        $seller->setPassword($hashedPassword);

        $this->em->persist($seller);
        $this->em->flush();

        return $this->mapper->fromEntityToSellerResponseDto($seller);
    }

    /**
     * Retrieves the public data for a given seller.
     *
     * @param Seller $seller The seller entity.
     * @return SellerResponseDto A DTO containing safe-to-expose seller data.
     */
    public function getSeller(Seller $seller): SellerResponseDto
    {
        return $this->mapper->fromEntityToSellerResponseDto($seller);
    }

    /**
     * Updates an existing seller's profile data.
     *
     * @param Seller $seller The seller entity to update.
     * @param UpdateSellerDto $dto The DTO with the new data.
     * @return SellerResponseDto The updated seller data as a DTO.
     */
    public function updateSeller(Seller $seller, UpdateSellerDto $dto): SellerResponseDto
    {
        $this->mapper->fromUpdateSellerDtoToEntity($dto, $seller);

        $this->em->flush();

        return $this->mapper->fromEntityToSellerResponseDto($seller);
    }

    /**
     * Deletes a seller's account from the database.
     *
     * @param Seller $seller The seller entity to delete.
     */
    public function deleteSeller(Seller $seller): void
    {
        $this->em->remove($seller);
        $this->em->flush();
    }

    /**
     * Changes a seller's password after verifying the current one.
     *
     * @param Seller $seller The seller whose password is to be changed.
     * @param ChangePasswordDto $dto The DTO containing the current and new passwords.
     * @throws UnprocessableEntityHttpException If the current password is invalid.
     */
    public function changePassword(Seller $seller, ChangePasswordDto $dto): void
    {
        if (
            !$this->passwordHasher->isPasswordValid(
                $seller,
                $dto->currentPassword
            )
        ) {
            throw new UnprocessableEntityHttpException('Invalid current password.');
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $seller,
            $dto->newPassword
        );

        $seller->setPassword($hashedPassword);
        $this->em->persist($seller);
        $this->em->flush();
    }
}