<?php

namespace App\Repositories;

use App\Service\AuthService;

class AuthRepository implements AuthRepositoryInterface
{
    public function insertToken(AuthService $authService): void
    {
        \DB::table('user_tokens')->insert([
            'user_id' => $authService->getGetUserId(),
            'token' => $authService->getToken(),
            'expires_at' => now()->addMinutes(config('jwt.ttl')), // mesma validade do JWT
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function deleteToken(AuthService $authService): void
    {
        \DB::table('user_tokens')
            ->where('token', $authService->getToken())
            ->delete()
        ;
    }
}