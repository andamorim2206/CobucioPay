<?php
namespace App\Repositories;

use App\Service\UserService;
interface UserRepositoryInterface
{
    public function create(array $user): UserService;
    public function findUserByToken(string $token): ?UserService;
    public function findUserByEmail(string $email): ?UserService;
}