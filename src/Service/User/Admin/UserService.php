<?php

namespace App\Service\User\Admin;

use App\Repository\UserRepository;
use App\Entity\User\User;
use App\Mapper\User\Admin\ListUsersMapper;



class UserService
{
    private UserRepository $userRepository;
    private ListUsersMapper $ListUsersMapper;

    public function __construct(
        UserRepository $userRepository,
        ListUsersMapper $ListUsersMapper
    ) {
        $this->userRepository = $userRepository;
        $this->ListUsersMapper = $ListUsersMapper;
    }


    public function getAllUsers(): array
    {

        $users = $this->userRepository->findAll();

        $dtos = [];
        foreach ($users as $user) {
            if ($user instanceof User) {
                $dtos[] = $this->ListUsersMapper->map($user);
            }
        }

        return $dtos;

    }
}

