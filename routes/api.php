<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuotationController;
use App\Http\Controllers\Api\CountryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Ruta listar paises
Route::get('/countries', [CountryController::class, 'index']);

//Ruta para manejar solicitudes Quotations
Route::middleware('validate.web.quotation.api.token')->post('/web-quotation-store', [QuotationController::class, 'store']);