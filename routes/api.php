<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['message' => 'API funcionando!']);
});

Route::post('/api/cadastro', [UserController::class, 'actionCreate']);
Route::post('/api/login', [AuthController::class, 'actionLogin']);

Route::middleware('auth:api')->group(function () {
    Route::get('/api/usuario', [UserController::class, 'actionUser']);
    Route::post('/api/logout', [AuthController::class, 'actionLogout']);
    Route::patch('/api/transferencia', [TransactionController::class, 'actionTransfer']);
    Route::patch('/api/estorno', [TransactionController::class, 'actionReversal']);
    Route::get('/api/extrato', [TransactionController::class, 'actionExtract']);
});