<?php 
namespace App\Repositories;

use App\Service\TransactionService;

interface TransactionRepositoryInterface 
{
    public function insertTransaction(TransactionService $transactionService): void;   
}