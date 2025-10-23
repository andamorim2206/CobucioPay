<?php
namespace App\Service;

use App\Repositories\WalletRepositoryInterface;

class WalletService
{
    protected WalletRepositoryInterface $repository;

    public function __construct(WalletRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function insertWallet(UserService $userService)
    {
        $array = [
            'user_id' => $userService->getId(),
            'balance' => 10000.000
        ];

        $this->repository->create($array);
    }
}