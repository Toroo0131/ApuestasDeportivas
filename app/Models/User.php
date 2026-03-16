<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/* 
 * Modelo que representa a los usuarios del sistema de apuestas.
 * Extiende de Authenticatable para manejar autenticación en Laravel
 * e implementa JWTSubject para permitir autenticación mediante JWT.
 *
 * Roles disponibles:
 * - admin: administrador del sistema
 * - usuario: usuario que realiza apuestas
 *
 * Campos principales:
 * - name: nombre del usuario
 * - email: correo electrónico del usuario
 * - password: contraseña del usuario
 * - role: rol dentro del sistema
 * - saldo: dinero disponible para apostar
 * - otp_code: código de verificación temporal
 * - otp_expires_at: fecha de expiración del código OTP
 *
 * Relaciones:
 * - Un usuario puede tener muchas apuestas
*/
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
   
    //rol de admin
    const ROLE_ADMIN = 'admin';

    //rol user normal
    const ROLE_USUARIO = 'usuario';

    //campos que pueden asignarse 

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'saldo',
        'otp_code',
        'otp_expires_at',
    ];

    //campos que deben ocultarse en la respuesta del JSON

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    //Conversion automatica de tipos de datos

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
    ];

    //obtener el identificador que se almacenara en el  jwt

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    //obtener los claims personalizados para el JWT

    public function getJWTCustomClaims()
    {
        return [];
    }

    //Relaxion con las apuestas realizadas por el usuario
    //un usuario puede realizar muchas apuestas

    public function apuestas()
    {
        return $this->hasMany(Apuestas::class);
    }
}
