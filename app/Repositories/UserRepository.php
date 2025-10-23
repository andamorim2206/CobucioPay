<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Service\UserService;
use App\Service\WalletService;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $user): UserService
    {
        $user = User::create($user);

        return (new UserService(new UserRepository()))
            ->setId($user->id)
        ;
    }
}