<?php

namespace App\Repositories;

use App\Service\TransactionService;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\Str;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function insertTransaction(TransactionService $transactionService): void
    {
        \DB::table('transactions')->insert([
            'id' => Str::uuid(),
            'type' => $transactionService->getType(),
            'sender_wallet_id' => $transactionService->getSender() ?? null,
            'receiver_wallet_id' => $transactionService->getReceiver() ?? null,
            'amount' => $transactionService->getAmount(),
            'status' => 'completed',
        ]);
    }
}