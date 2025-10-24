<?php

namespace App\Repositories;

use App\Service\WalletService;

interface WalletRepositoryInterface 
{
    public function create(array $walletService): void;

    public function loadWallets(string $userId): WalletService;

    public function updateWallet(WalletService $walletService, float $amount): void;

     public function findWalletById(string $receiverId): WalletService;
}