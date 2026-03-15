<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\ApuestasController;   


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

Route::get('/apuestas', [ApuestasController::class, 'index']);
Route::post('/apuestas', [ApuestasController::class, 'store']);
Route::get('/apuestas/{id}', [ApuestasController::class, 'show']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::post('/eventos/{id}/resolver', [EventoController::class, 'resolver']);

