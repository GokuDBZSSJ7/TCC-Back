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
}