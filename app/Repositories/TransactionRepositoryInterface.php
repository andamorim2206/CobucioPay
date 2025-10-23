<?php 
namespace App\Repositories;

interface TransactionRepositoryInterface 
{
    public function transfer(): void;   
}