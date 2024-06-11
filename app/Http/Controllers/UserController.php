<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
    }

    public function show(User $user)
    {
        return $user;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'email' => 'required|string|email|unique:usuarios',
            'senha' => 'required|string|min:6',
            'cpf' => 'nullable|string',
            'genero' => 'nullable|string',
            'data_nascimento' => 'nullable',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => bcrypt($request->senha),
            'cpf' => $request->cpf,
            'genero' => $request->genero,
            'data_nascimento' => $request->data_nascimento,
            'id_cidade' => $request->id_cidade,
            'id_estado' => $request->id_estado,
        ]);

        return response()->json(['message' => 'Usuário cadastrado com sucesso'], 201);
    }

    public function update(Request $request, User $user)
    {
        if($request->has('senha')) {
            $user->update($request->all());
        } else {
            $userData = $request->except('senha');

            $user->update($userData);
        }

        return $user;
    }

    
}