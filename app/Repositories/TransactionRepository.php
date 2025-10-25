<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Service\TransactionService;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
            'created_at' => now(),
        ]);
    }

    public function loadExtract(string $walletId): array
    {
        $rows = \DB::table('transactions')
            ->select('id', 'type', 'sender_wallet_id', 'receiver_wallet_id', 'amount', 'status', 'created_at')
            ->where('sender_wallet_id', $walletId)
            ->orWhere('receiver_wallet_id', $walletId)
            ->orderBy('created_at', 'desc')
            ->get();


        $transactions = [];

        foreach ($rows as $row) {
            $tx = new TransactionService(new TransactionRepository());
            $tx->setId($row->id);
            $tx->setType($row->type);
            $tx->setSender($row->sender_wallet_id);
            $tx->setReceiver($row->receiver_wallet_id);
            $tx->setAmount($row->amount);
            $tx->setStatus($row->status);

            $transactions[] = $tx;
        }

        return $transactions;
    }

    public function findTransactionForReversal(string $walletId, string $transactionId): TransactionService
    {
        $row = \DB::table('transactions')
            ->select('id', 'type', 'sender_wallet_id', 'receiver_wallet_id', 'amount', 'status')
            ->where('sender_wallet_id', $walletId)
            ->where('id', $transactionId)
            ->where('type', 'transfer')
            ->first()
        ;
        if (!$row) {
            throw ValidationException::withMessages([
                'email' => 'Transaction não encontrada.',
            ]);
        }

        return (new TransactionService(new TransactionRepository()))
            ->setId($row->id)
            ->setType($row->type)
            ->setSender($row->sender_wallet_id)
            ->setReceiver($row->receiver_wallet_id)
            ->setAmount($row->amount)
            ->setStatus($row->status)
        ;
    }

    public function updateIsReversal(string $transactionId): void
    {
        $test2 = \DB::table('transactions')->where('id', $transactionId)->update(['is_reversal' => true]);
    } 

    public function isTransactionReversal(): void
    {
        $row = \DB::table('transactions')
            ->where('is_reversal', 1)
            ->exists()
        ;


        if ($row) {
            throw ValidationException::withMessages([
                'messagem' => 'Estorno feito nessa transaction.',
            ]);
        }

    }
}