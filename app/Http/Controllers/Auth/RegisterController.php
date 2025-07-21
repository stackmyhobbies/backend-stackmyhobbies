<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//TODO ARREGLAR TODO LOS SERVICIOS, CONTROLLERS Y RESPOSITORIES
class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'message' => 'User registered successfully'
        ], 201);
    }
}
