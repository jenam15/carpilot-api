<?php

namespace App\Service\Admin;

use App\Repository\UserRepository;
use App\Mapper\UserMapper;



class SellerService
{

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserMapper $UserMapper
    ) {
    }

    public function getPaginatedSellers(int $page, int $limit)
    {
        $paginator = $this->userRepository->findPaginatedSellers($page, $limit);
        return $this->createPaginatedResponse($paginator, $page, $limit);
    }


    public function createPaginatedResponse($paginator, int $page, int $limit)
    {
        $userDtos = array_map(
            fn($user) => $this->UserMapper->fromEntityToResponseDto($user),
            iterator_to_array($paginator)
        );

        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        return [
            'data' => $userDtos,
            'meta' => [
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalItems' => $totalItems,
                'limit' => $limit
            ]
        ];
    }
}

