<?php

namespace App\Service;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserService
{
    protected UserRepositoryInterface $repository;

    private string $id;
    private WalletService $walletService;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(Request $request): void
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = $request->only(['name', 'email', 'password']);

        $userCreated = $this->repository->create($user);

        $this->getWalletServide()->insertWallet($userCreated);
    }

    public function setId(string $id): UserService
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setWalletService(WalletService $walletService): UserService
    {
        $this->walletService = $walletService;

        return $this;
    }

    public function getWalletServide(): WalletService
    {
        return $this->walletService;
    }
}