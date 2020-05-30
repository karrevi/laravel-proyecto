<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $body = $request->except('role');
            $body['password'] = Hash::make($body['password']);
            $user = User::create($body);
            return response($user, 201);
        } catch (\Exception $e) {
            return response([
                'message' => 'Error al registrarte',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function login(Request $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response([
                    'message' => 'Credenciales han fallado'
                ], 400);
            }
            $user = Auth::user();
            $token = $user->createToken('authToken')->accessToken;
            $user->token = $token;
            return response([
                'user' => $user,
                'token' => $token
            ]);
            dd($token);
        } catch (\Exception $e) {
            return response([
                'message' => 'Hubo un problema al loguearte',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function logout()
    {
        try {
            Auth::user()->token()->revoke();
            return response([
                'message'=>'Has cerrado sesión',
            ]);
        } catch (\Exception $e) {
            return response ([
                'message'=> 'Error al intentar desconectarte',
                'error'=>$e->getMessage()
            ], 500);
        }
    }
}
