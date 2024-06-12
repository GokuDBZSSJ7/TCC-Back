<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $messages = [
            'senha.required' => 'O campo de senha é obrigatório',
            'senha.min' => 'O campo de senha deve conter no mínimo 6 caracteres'
        ];

        $valid = Validator::make($request->all(), [
            'email' => 'nullable',
            'senha' => 'required|min:6',
        ], $messages);
        
        if($valid->fails()) {
            return response()->json(['message' => $valid->messages()->first()], 403);
        }

        $credentials = $request->only(['email', 'senha']);

        $token = auth()->attempt($credentials);

        if(!$token) {
            return response()->json(['message' => 'Acesso não autorizado!'], 403);
        }

        $user = auth()->user();

        if($user->status === 0) {
            return response()->json(['message' => 'Acesso bloqueado!'], 403);
        }

        \Log::info($user);
        return $this->respondWithToken($token, $user);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => "Sucesso ao fazer logout"]);
    }

    protected function respondWithToken(string $token, ?User $user = null)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'roles' => empty($user) ? null : $this->buildRoles($user),
            'all_permissions' => empty($user) ? null : $this->buildPermissions($user),
        ]);
    }

    protected function buildRoles(User $user)
    {
        return [];
        return $user->roles->map(function ($item) {
            return $item->makeHidden('permissions');
        });
    }

    protected function buildPermissions(User $user): array
    {
        return [];
        return $user->getUserAllPermissions()->all();
    }

    public function login(Request $request)
    {
        try {
            $messages = [
                "email.required" => "O campo de Email é obrigatório",
                "senha.required" => "O campo de Senha é obrigatório",
                "senha.min" => "O campo de Senha deve conter ao minímo 6 digítos"
            ];

            $validateUser = Validator::make($request->all(), [
                'email' => 'required',
                'senha' => 'required|min:6'
            ], $messages);

            if($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'senha']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Acesso não autorizado.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            if ($user->status === 0) {
                return response()->json(['message' => 'Acesso bloqueado!'], 401);
            }
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'access_token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $newPassaword = 'politicos123';

            $user->update([
                'password' => bcrypt($newPassaword)
            ]);

            return response()->json(['message' => 'SENHA ATUALIZADA COM SUCESSO! NOVA SENHA: '.'politicos123'], 200); 
        } else {
            return response()->json(['message' => 'ERRO AO REDEFINIR SENHA!  '], 400);
        }
    }
}