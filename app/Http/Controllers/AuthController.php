<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
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

    public function me()
    {
        return response()->json([
            'user' => auth('api')->user(),
        ], 200);
    }


}
