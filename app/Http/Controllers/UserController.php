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
                'message' => 'Has cerrado sesión',
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => 'Error al intentar desconectarte',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request)
    {
        try {
            $body = $request->validate([
                'name' => 'string',
                'email' => 'string',
                'password' => 'string'
            ]);
            $id = Auth::id();
            $user = User::find($id);
            if ($request->has('password')) {
                $body['password'] = Hash::make($body['password']);
            }
            $user->update($body);
            return response([
                'user' => $user,
                'message' => 'El usuario ha sido actualizado'
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => 'Hubo un error al actualizar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getUserInfo()
    {
        try {
            $user = Auth::user();
            return response($user);
        } catch (\Exception $e) {
            return response([
                'message' => 'Hubo un error al mostrar la información',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function uploadImage(Request $request, $id)
    {
        try {
            $request->validate(['img' => 'required|image']);
            $user = User::find($id);
            $imageName = time() . '-' . request()->img->getClientOriginalName();
            request()->img->move('images/users', $imageName);
            $user->update(['image_path' => $imageName]);
            return response([
                'user' => $user,
                'message' => 'La imagen ha sido cambiada'
            ]);
        } catch (\Exception $e) {
            return response([
                'error' => $e,
            ], 500);
        }
    }
}
