<?php

namespace App\Mapper\User\Admin;

use App\DTO\User\Admin\ListUsersDto;
use App\Entity\User\User;


class ListUsersMapper
{

    public function map(User $user)
    {
        return new ListUsersDto(
            $user->getId(),
            $user->getEmail(),
            $user->getFirstName(),
            $user->getLastName()
        );
    }
}
