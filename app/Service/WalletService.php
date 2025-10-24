<?php
namespace App\Service;

use App\Repositories\WalletRepositoryInterface;

class WalletService
{
    protected WalletRepositoryInterface $repository;
    private UserService $userService;
    private string $id;
    private float $balance;

     private float $transferValue = 0;


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

    public function loadWallets(string $userId)
    {
        return $this->repository->loadWallets($userId);
    }

    public function updateWallet(WalletService $walletService, float $amount): void
    {
        $this->repository->updateWallet($walletService, $amount);
    }

    public function findWalletId(string $receiverId): WalletService
    {
        return $this->repository->findWalletById($receiverId);
    }

     public function setUser(UserService $userService): self
    {
        $this->userService = $userService;

        return $this;
    }

    public function getUser(): UserService
    {
        return $this->userService;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

     public function setTransferValue(float $transferValue): self
    {
        $this->transferValue = $transferValue;

        return $this;
    }

    public function getTransferValue(): float
    {
        return $this->transferValue;
    }
}