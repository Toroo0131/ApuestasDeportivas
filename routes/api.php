<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\CuotaController;   

Route::get('/eventos', [EventoController::class, 'index']);
Route::post('/eventos', [EventoController::class, 'store']);
Route::get('/eventos/{id}', [EventoController::class, 'show']);
Route::put('/eventos/{id}', [EventoController::class, 'update']);
Route::delete('/eventos/{id}', [EventoController::class, 'destroy']);

Route::get('/cuotas', [CuotaController::class, 'index']);
Route::post('/cuotas', [CuotaController::class, 'store']);
Route::get('/cuotas/{id}', [CuotaController::class, 'show']);
Route::put('/cuotas/{id}', [CuotaController::class, 'update']);
Route::delete('/cuotas/{id}', [CuotaController::class, 'destroy']);

Route::get('/apuestas', [ApuestaController::class, 'index']);
Route::post('/apuestas', [ApuestaController::class, 'store']);
Route::get('/apuestas/{id}', [ApuestaController::class, 'show']);