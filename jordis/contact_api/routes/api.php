<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;

// Rutas de autenticación 'Publicas'
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// Rutas protegidas 'Tiene que estar autenticado'
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('contacts', ContactController::class);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
