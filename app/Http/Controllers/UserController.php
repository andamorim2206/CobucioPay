<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Service\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    public function actionCreate(Request $request)
    {
        try {
            $user = new UserService(new UserRepository());

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
}