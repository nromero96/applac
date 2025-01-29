<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        // Verificar si la solicitud espera una respuesta en formato JSON
        if (!$request->expectsJson()) {
            return response()->json([
                'error' => 'Unauthorized request. JSON expected.'
            ], 406); // 406 Not Acceptable
        }

        // Obtener todos los países
        $countries = Country::all();

        return response()->json([
            'countries' => $countries,
        ], 200);
    }
}
