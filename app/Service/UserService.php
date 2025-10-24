<?php

namespace App\Service;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserService
{
    protected UserRepositoryInterface $repository;

    private string $id;
    private WalletService $walletService;

    private string $name;
    private string $email;

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

        $this->getWalletService()->insertWallet($userCreated);
    }

    public function loadUser(string $userToken): UserService 
    {
        return $this->repository->loadUser($userToken);
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

    public function getWalletService(): WalletService
    {
        return $this->walletService;
    }

    public function setName(string $name): UserService
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setEmail(string $email): UserService
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function findUserByToken(string $token): UserService
    {
        return $this->repository->findUserByToken($token);
    }

    public function findUserByEmail(string $email): UserService
    {
        return $this->repository->findUserByEmail($email);
    }
}