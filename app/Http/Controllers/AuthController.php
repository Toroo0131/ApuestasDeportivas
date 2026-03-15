<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/* Controlador encargado de gestionar la autenticacion de usuraios
*Este controlador, permite
-registrar usuarios
-Iniciar sesión
-Generar y verificar codigo OTP
-Obtenerla informacion del usuario autenticado

*/

class AuthController extends Controller

{
    /*Registrar un nuevo usuario en el sistema
    -Valida los datos recibidos desde la peticion HTTP y crea
    un nuevo registro en la base de datos. Si no se especfica
    un rol, se asigna autenticamente el rol "usuario"


    */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email'=> 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'nullable|in:admin,usuario',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=> $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? User::ROLE_USUARIO,
            'saldo' => 0,
        ]);

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user' => $user
        ], 201);
    }

    /*Autenticar un usuario en el sistema
    -Verifica las credenciales del usuario. Si son correctas
    se genera un codigo OTP temporal que debe ser verificado
    antes de emitir el token JWT


    */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (! auth('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $user = auth('api')->user();

        $otp = rand(100000, 999999);

        $user->otp_code = (string) $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        auth('api')->logout();

        return response()->json([
            'message' => 'Credenciales correctas. OTP generado.',
            'email' => $user->email,
            'otp_demo' => $otp
        ], 200);
    }

    /*Verificar el codigo OTP enviado al usuario

    si el OTP es valido y no ha expirado, se genera un token
    JWT que permitira acceder a las rutas protegudas de la API

    */

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        if ($user->otp_code !== $request->otp_code) {
            return response()->json([
                'message' => 'Código OTP incorrecto'
            ], 401);
        }

        if (! $user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
            return response()->json([
                'message' => 'El código OTP ha expirado'
            ], 401);
        }

        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'OTP verificado correctamente',
            'token' => $token,
            'user' => $user
        ], 200);

    }
    /*Obtener la informacion del usuario atenticado

    Este EndPoind devuelve los datos del usuario asociado
    al token JWT enviado en la peticion

    */

    public function me()
    {
        return response()->json([
            'user' => auth('api')->user(),
        ], 200);
    }


}
