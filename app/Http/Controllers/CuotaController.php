<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
Controlador encargado de gestionar las cuotas de los eventos
dentro del sistema de apuestas

permite
listar cuotas
crear nuevas cuotas
consultar una cuota especifica
actualizar cuotas
eliminar cuotas
*/

class CuotaController extends Controller
{

/*
mostrar listado de todas las cuotas registradas
incluye la relacion con el evento asociado
*/

    public function index()
    {
        $cuotas = Cuota::with('evento')->get();

        return response()->json([
            'message' => 'Listado de cuotas',
            'data' => $cuotas
        ]);
    }

    /*
    registrar una nueva cuota al sistema

    valida los datos recibidos
    evento_id debe existir en la tabla eventos
    tipo_aouesta debe ser un string
    cuota debe ser numerica
    */

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

    /*
    mostrar informacion de una cuota especifica

    busca la cuota por su ID
    */
    public function show($id)
    {
        $cuota = Cuota::find($id);

        if(!$cuota){
            return response()->json(['message'=>'Cuota no encontrada'],404);
        }

        return response()->json($cuota);
    }

    /*
    Actualizar los datos de una cuota existente

    permite modicar los campos de la cuota usando 
    los datos enviados en la peticion

    */

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

    /*
    eliminar una cuota del sistema

    busca la couta por su ID y la elimina de la base de datos
    */
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