<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Service\UserService;
use App\Service\WalletService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    public function actionCreate(Request $request)
    {
        try {
            $user = (new UserService(new UserRepository()))
                ->setWalletService(new WalletService(new WalletRepository()))
            ;

            $user->create($request);

            return response()->json([
                'message' => 'Usuário cadastrado com sucesso!',
                'user' => $user,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Erro de validação',
                'details' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro ao cadastrar usuário',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function actionUser(Request $request)
    {
        try {

            $token = $request->bearerToken();

            $user = (new UserService(new UserRepository()))
                ->setWalletService(new WalletService(new WalletRepository()))
                ->loadUser($token)
            ;

            if (!$user) {
                return response()->json([
                    'error' => 'Usuário não encontrado'
                ], 404);
            }

            $data = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'wallet' => $user->getWalletService() ? [
                    'id' => $user->getWalletService()->getId(),
                    'balance' => $user->getWalletService()->getBalance(),
                ] : null,
            ];

            return response()->json([
                'user' => $data
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Erro de validação',
                'details' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro ao cadastrar usuário',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}