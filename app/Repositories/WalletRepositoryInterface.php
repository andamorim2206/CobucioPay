<?php

namespace App\Repositories;

interface WalletRepositoryInterface 
{
    public function create(array $walletService): void;

    public function loadWallets(string $userId);
}