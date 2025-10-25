<?php 
namespace App\Repositories;

use App\Service\TransactionService;

interface TransactionRepositoryInterface 
{
    public function insertTransaction(TransactionService $transactionService): void;
    public function loadExtract(string $walletId): array;
    public function findTransactionForReversal(string $walletId, string $transactionId): TransactionService;
    public function updateIsReversal(string $token): void;
    public function isTransactionReversal(): void;
}