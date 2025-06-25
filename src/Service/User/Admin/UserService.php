<?php

namespace App\Service\User\Admin;

use App\Repository\UserRepository;
use App\Entity\User\User;
use App\Mapper\User\Admin\UserMapper;



class UserService
{
    private UserRepository $userRepository;
    private UserMapper $UserMapper;

    public function __construct(
        UserRepository $userRepository,
        UserMapper $UserMapper
    ) {
        $this->userRepository = $userRepository;
        $this->UserMapper = $UserMapper;
    }


    public function getAllUsers(): array
    {


    }
}

