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

    public function loadUser(string $token): UserService
    {
        $userData = \DB::table('user_tokens')
            ->join('users', 'users.id', '=', 'user_tokens.user_id')
            ->join('wallets', 'wallets.user_id', '=', 'users.id')
            ->where('user_tokens.token', $token)
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'wallets.id as wallet_id',
                'wallets.balance as wallet_balance'
            )
            ->first();

        $wallet = (new WalletService(new WalletRepository()))
            ->setId($userData->wallet_id)
            ->setBalance($userData->wallet_balance)
        ;

        return (new UserService(new UserRepository()))
            ->setId($userData->user_id)
            ->setName($userData->name)
            ->setEmail($userData->email)
            ->setWalletService($wallet)
        ;
    }

    public function loadAllUsers(): array
    {
        $rows = \DB::table('users')
            ->select('id', 'name', 'email')
            ->get()
        ;

        $usersData = [];

        foreach ($rows as $row) {
            $user = new UserService(new UserRepository());
            $user->setId($row->id);
            $user->setName($row->name);
            $user->setEmail($row->email);

            $usersData[] = $user;
        }

        return $usersData;
    }
}