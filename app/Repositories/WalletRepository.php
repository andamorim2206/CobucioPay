<?php
namespace App\Repositories;

use App\Models\Wallet;
use App\Repositories\WalletRepositoryInterface;
use App\Service\UserService;
use App\Service\WalletService;

class WalletRepository implements WalletRepositoryInterface
{
    public function create(array $walletService): void
    {
        Wallet::create($walletService);
    }

    public function loadWallets(string $userId): WalletService
    {
        $record = Wallet::where('user_id', $userId)->lockForUpdate()->first();

        $user = (new UserService(new UserRepository()))
            ->setId($record->user_id)
        ;

        return (new WalletService(new WalletRepository()))
            ->setId($record->id)
            ->setBalance($record->balance)
            ->setUser($user)
        ;
    }
}