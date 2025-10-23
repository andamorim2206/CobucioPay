<?php

namespace App\Service;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserService
{
    protected UserRepositoryInterface $repository;
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

        $this->repository->create($user);
    }
}