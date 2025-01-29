<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        //List all countries
        $countries = Country::all();
        return response()->json([
            'countries' => $countries,
        ], 200);
    }
}
