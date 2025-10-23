<?php

namespace App\Service;
use App\Repositories\AuthRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    private ?string $token = null;
    private string $userId;

    protected AuthRepositoryInterface $repository;

    public function __construct(AuthRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function login(Request $request): bool
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $token = Auth::guard('api')->attempt($request->only('email', 'password'));

        if (!$token) {
            return false;
        }

        $this->setToken($token);
        $this->setUserId(Auth::guard('api')->id());

        $this->repository->insertToken($this);

        return true;
    }

    public function logout(string $token): void
    {
        Auth::guard('api')->logout();

        $this->setToken($token);

        $this->repository->deleteToken($this);
    }

    private function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    private function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getGetUserId(): string
    {
        return $this->userId;
    }
}