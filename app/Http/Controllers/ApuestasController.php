<?php

namespace App\Http\Controllers;

use App\Models\Apuestas;
use App\Models\Cuota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApuestasController extends Controller
{
    public function index()
    {
        $apuestas = Apuestas::with(['user', 'evento', 'cuota'])->get();

        return response()->json([
            'message' => 'Listado de apuestas',
            'data' => $apuestas
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'cuota_id' => 'required|exists:cuotas,id',
            'monto' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $cuota = Cuota::with('evento')->find($request->cuota_id);
        $user = User::find($request->user_id);

        if (!$cuota) {
            return response()->json([
                'message' => 'Cuota no encontrada'
            ], 404);
        }

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $gananciaPosible = $request->monto * $cuota->cuota;

        DB::beginTransaction();

        try {
            $apuesta = Apuestas::create([
                'user_id' => $user->id,
                'evento_id' => $cuota->evento->id,
                'cuota_id' => $cuota->id,
                'monto' => $request->monto,
                'ganancia_posible' => $gananciaPosible,
                'estado' => 'pendiente'
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Apuesta registrada correctamente',
                'data' => $apuesta
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al registrar apuesta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $apuesta = Apuestas::with(['user', 'evento', 'cuota'])->find($id);

        if (!$apuesta) {
            return response()->json([
                'message' => 'Apuesta no encontrada'
            ], 404);
        }

        return response()->json([
            'message' => 'Detalle de la apuesta',
            'data' => $apuesta
        ], 200);
    }
}

