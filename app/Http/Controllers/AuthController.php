<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\AuthRepository;
use App\Service\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function actionLogin(Request $request)
    {
        try {
            $auth = new AuthService(new AuthRepository());

            $token = $auth->login($request);

            if (!$token) {
                throw ValidationException::withMessages([
                    'email' => ['Credenciais inválidas.'],
                ]);
            }

            return response()->json([
                'access_token' => $auth->getToken(),
                'token_type' => 'bearer',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro de servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function actionLogout(Request $request)
    {
        try {
            $auth = new AuthService(new AuthRepository());

            $token = $request->bearerToken();

            $auth->logout($token);

            return response()->json([
                'status' => true,
                'message' => 'Logout realizado com sucesso',
                'timestamp' => now()->toDateTimeString()
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro de servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}