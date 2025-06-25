<?php

namespace App\Service\User;

use App\Mapper\User\UserMapper;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\User\CreateSellerDto;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



/**
 * Class UserService
 * Contains all logic to interact with Seller entities.
 */
class UserService
{
    public function __construct(
        private readonly UserMapper $mapper,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function createSeller(CreateSellerDto $dto)
    {
        $seller = $this->mapper->fromCreateSellerDtoToEntity($dto);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $seller,
            $dto->password
        );

        $seller->setPassword($hashedPassword);

        $this->em->persist($seller);
        $this->em->flush();

        $responseDto = $this->mapper->fromEntityToSellerResponseDto($seller);

        return $responseDto;
    }

}