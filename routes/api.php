<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
//header('Access-Control-Allow-Origin: https://*.mgpapelaria.com.br');

Route::group(['middleware' => 'throttle:api'], function ($router) {

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('oauth/token', [AuthController::class, 'getToken'])->name('auth');
    Route::post('oauth/token/json', [AuthController::class, 'getTokenJson']);
    Route::post('refresh', [AuthController::class, 'refreshToken']);
    Route::post('logout', [AuthController::class, 'logout'] )->middleware('auth:api');
});

//Throttle 1000 requisicoes por minuto
Route::group(['middleware' => ['throttle:600,1']], function ($router) {
    Route::get('check-token', [AuthController::class, 'checkToken']);
});

