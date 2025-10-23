<?php
namespace App\Repositories;

use App\Service\UserService;
interface UserRepositoryInterface
{
    public function create(array $user): UserService;
}