<?php
namespace App\Repositories;

use App\Service\AuthService;

interface AuthRepositoryInterface 
{
    public function insertToken(AuthService $authService): void;
    public function deleteToken(AuthService $authService): void;
}