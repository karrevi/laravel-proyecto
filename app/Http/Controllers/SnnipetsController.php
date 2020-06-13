<?php

namespace App\Http\Controllers;

use App\Snnipets;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SnnipetsController extends Controller
{
    public function getAll()
    {
        try {
            $snnippets = Snnipets::with('user')->get();
            return response($snnippets);
        } catch (\Exception $e) {
            return response([
                'error' => $e
            ], 500);
        }
    }
    public function insert(Request $request)
    {
        try {
            $user_id = Auth::id();
            $body = ['name' => $request->name, 'code_snnipets' => $request->code_snnipets, 'extension' => $request->extension, 'user_id' => $user_id];
            $snnippets = Snnipets::create($body);
            return response($snnippets, 201);
        } catch (\Exception $e) {
            return response([
                'error' => $e->getMessage(),
                'message' => 'Se produjo un problema al crear el snnipet'
            ], 500);
        }
    }
}
