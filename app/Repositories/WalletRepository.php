<?php
namespace App\Repositories;

use App\Models\Wallet;
use App\Repositories\WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{
    public function create(array $walletService): void{
        Wallet::create($walletService);
    }
}