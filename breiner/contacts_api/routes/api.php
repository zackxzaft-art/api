<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\UsuarioController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users/{id}/contactos', [UsuarioController::class, 'index'])->middleware('auth:sanctum');
Route::get('/users/{user}/contacts', [UsuarioController::class, 'listar_contactos'])->middleware('auth:sanctum');
Route::apiResource('contacts',ContactsController::class)->middleware('auth:sanctum');
Route::post('/contacts', [ContactsController::class, 'store']);
Route::post('register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::put('/users/{user}', [UsuarioController::class, 'update'])->middleware('auth:sanctum');