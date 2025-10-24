<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Service\TransactionService;
use App\Service\UserService;
use App\Service\WalletService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class TransactionController extends Controller
{
    public function actionTransfer(Request $request)
    {
        try {
            $transaction = (new TransactionService(new TransactionRepository()))
                ->setUser(new UserService(new UserRepository()))
                ->setWallet(new WalletService(new WalletRepository()))
            ;

            $token = $request->bearerToken();

            $transaction->handlerTransfer($token, $request);

            return response()->json([
                'message' => 'Transferencia feita com sucesso!',
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'messages' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro de servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}