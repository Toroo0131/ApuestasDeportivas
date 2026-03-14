<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CuotaController extends Controller
{

    public function index()
    {
        $cuotas = Cuota::with('evento')->get();

        return response()->json([
            'message' => 'Listado de cuotas',
            'data' => $cuotas
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evento_id' => 'required|exists:eventos,id',
            'tipo_apuesta' => 'required|string',
            'cuota' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $cuota = Cuota::create($request->all());

        return response()->json([
            'message' => 'Cuota creada correctamente',
            'data' => $cuota
        ]);
    }

    public function show($id)
    {
        $cuota = Cuota::find($id);

        if(!$cuota){
            return response()->json(['message'=>'Cuota no encontrada'],404);
        }

        return response()->json($cuota);
    }

    public function update(Request $request, $id)
    {
        $cuota = Cuota::find($id);

        if(!$cuota){
            return response()->json(['message'=>'Cuota no encontrada'],404);
        }

        $cuota->update($request->all());

        return response()->json([
            'message'=>'Cuota actualizada',
            'data'=>$cuota
        ]);
    }

    public function destroy($id)
    {
        $cuota = Cuota::find($id);

        if(!$cuota){
            return response()->json(['message'=>'Cuota no encontrada'],404);
        }

        $cuota->delete();

        return response()->json([
            'message'=>'Cuota eliminada'
        ]);
    }

}