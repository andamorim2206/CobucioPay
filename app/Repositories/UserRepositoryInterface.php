<?php
namespace App\Repositories;
interface UserRepositoryInterface
{
    public function create(array $user): void;
}