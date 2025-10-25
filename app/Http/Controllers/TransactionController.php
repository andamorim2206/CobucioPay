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

    public function actionExtract(Request $request)
    {
        try {
            $transactionService = (new TransactionService(new TransactionRepository()))
                ->setUser(new UserService(new UserRepository()))
                ->setWallet(new WalletService(new WalletRepository()));

            $token = $request->bearerToken();

            $extract = $transactionService->loadExtract($token);

            if (!$extract || count($extract) === 0) {
                return response()->json([
                    'message' => 'Nenhuma transação encontrada.',
                    'data' => []
                ], 200);
            }

            $data = [];
            foreach ($extract as $tx) {
                $data[] = [
                    'id' => $tx->getId(),
                    'type' => $tx->getType(),
                    'amount' => $tx->getAmount(),
                    'status' => $tx->getStatus(),
                    'sender_wallet_id' => $tx->getSender(),
                    'receiver_wallet_id' => $tx->getReceiver(),
                    'user_receiver_id' => $tx->getUserIdReceive(),
                ];
            }

            return response()->json([
                'message' => 'Extrato carregado com sucesso!',
                'total' => count($data),
                'transactions' => $data
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

    public function actionReversal(Request $request)
    {
        try {
            $token = $request->bearerToken();

            (new TransactionService(new TransactionRepository()))
                ->setUser(new UserService(new UserRepository()))
                ->setWallet(new WalletService(new WalletRepository()))
                ->handlerReversal($token, $request);
            ;

             return response()->json([
                'message' => 'Estorno feita com sucesso!',
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