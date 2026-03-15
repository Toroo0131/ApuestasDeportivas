<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\ApuestasController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);


/*
|--------------------------------------------------------------------------
| Rutas protegidas con JWT
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {

    // Consultas disponibles para usuarios autenticados
    Route::get('/eventos', [EventoController::class, 'index']);
    Route::get('/eventos/{id}', [EventoController::class, 'show']);

    Route::get('/cuotas', [CuotaController::class, 'index']);
    Route::get('/cuotas/{id}', [CuotaController::class, 'show']);

    Route::get('/apuestas', [ApuestasController::class, 'index']);
    Route::post('/apuestas', [ApuestasController::class, 'store']);
    Route::get('/apuestas/{id}', [ApuestasController::class, 'show']);

    Route::get('/mis-apuestas', [ApuestasController::class, 'misApuestas']);



    /*
    |--------------------------------------------------------------------------
    | Solo administradores
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        Route::post('/eventos', [EventoController::class, 'store']);
        Route::put('/eventos/{id}', [EventoController::class, 'update']);
        Route::delete('/eventos/{id}', [EventoController::class, 'destroy']);
        Route::post('/eventos/{id}/resolver', [EventoController::class, 'resolver']);

        Route::post('/cuotas', [CuotaController::class, 'store']);
        Route::put('/cuotas/{id}', [CuotaController::class, 'update']);
        Route::delete('/cuotas/{id}', [CuotaController::class, 'destroy']);

    });

});
