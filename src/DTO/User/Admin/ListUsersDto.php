<?php

namespace App\DTO\User\Admin;

class ListUsersDto
{

    public function __construct(

        public readonly int $id,
        public readonly string $email,
        public readonly string $firstName,
        public readonly string $lastName,
    ) {
    }

}