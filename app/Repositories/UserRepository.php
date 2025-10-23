<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $user): void {
        User::create($user);
    }
}