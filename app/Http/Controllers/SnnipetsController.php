<?php

namespace App\Http\Controllers;

use App\Snnipets;
use Illuminate\Http\Request;

class SnnipetsController extends Controller
{
    public function getAll()
    {
        try {
            $snnippets = Snnipets::find();
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
            $request->validate([
                'name',
                'code_snnipets',
                'extension'
            ]);
            $body = $request->all();
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
