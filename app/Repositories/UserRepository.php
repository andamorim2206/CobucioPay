<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\Service\UserService;
use App\Service\WalletService;
use Illuminate\Validation\ValidationException;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $user): UserService
    {
        $user = User::create($user);

        return (new UserService(new UserRepository()))
            ->setId($user->id)
        ;
    }

    public function findUserByToken(string $token): UserService
    {
        $userId = \DB::table('user_tokens')
            ->where('token', $token)
            ->value('user_id');

        if (!$userId) {
            throw ValidationException::withMessages([
                'token' => 'Token inválido ou expirado.',
            ]);
        }

        return (new UserService(new UserRepository()))
            ->setId($userId)
        ;
    }

    public function findUserByEmail(string $email): UserService
    {
        $userId = \DB::table('users')
            ->where('email', $email)
            ->value('id');

        if (!$userId) {
            throw ValidationException::withMessages([
                'email' => 'Usuário com este e-mail não foi encontrado.',
            ]);
        }

        return (new UserService(new UserRepository()))
            ->setId($userId);
    }
}