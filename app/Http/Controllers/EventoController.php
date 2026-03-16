<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
Controlador encargado de gestionar los evntos deportivos
dentro del sistema de apuestas

permite
listar eventos
crear eventos
consultar un evento especifico
actualizar eventos
eliminar eventos
resolver eventos y procesar las apuestas relacionadas
*/

class EventoController extends Controller
{
//mostrar el listado de los eventos relacionados
public function index()
    {
        $eventos = Evento::all();

        return response()->json([
            'message' => 'Listado de eventos',
            'data' => $eventos
        ], 200);
    }

    //registrar un nuevo evento deportivo
    // valida los datos recibidos y crea un nuevo evento
    //con estado "pendiente" por defecto
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deporte' => 'required|string|max:100',
            'equipo_local' => 'required|string|max:100',
            'equipo_visitante' => 'required|string|max:100',
            'fecha_evento' => 'required|date',
            'estado' => 'nullable|in:pendiente,finalizado,cancelado',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $evento = Evento::create([
            'deporte' => $request->deporte,
            'equipo_local' => $request->equipo_local,
            'equipo_visitante' => $request->equipo_visitante,
            'fecha_evento' => $request->fecha_evento,
            'estado' => $request->estado ?? 'pendiente',
        ]);

        return response()->json([
            'message' => 'Evento creado correctamente',
            'data' => $evento
        ], 201);
    }

    //mostrar el detalle de un evento en especifico
    public function show(string $id)
    {
        $evento = Evento::find($id);

        if (! $evento) {
            return response()->json([
                'message' => 'Evento no encontrado'
            ], 404);
        }

        return response()->json([
            'message' => 'Detalle del evento',
            'data' => $evento
        ], 200);
    }

    //actualizar los datos de un evento existente
    //solo se actualizan los campos enviados en la peticion
    public function update(Request $request, string $id)
    {
        $evento = Evento::find($id);

        if (! $evento) {
            return response()->json([
                'message' => 'Evento no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'deporte' => 'sometimes|string|max:100',
            'equipo_local' => 'sometimes|string|max:100',
            'equipo_visitante' => 'sometimes|string|max:100',
            'fecha_evento' => 'sometimes|date',
            'estado' => 'sometimes|in:pendiente,finalizado,cancelado',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $evento->update($request->only([
            'deporte',
            'equipo_local',
            'equipo_visitante',
            'fecha_evento',
            'estado',
        ]));

        return response()->json([
            'message' => 'Evento actualizado correctamente',
            'data' => $evento
        ], 200);
    }

    //eliminar un evento del sistema
    public function destroy(string $id)
    {
        $evento = Evento::find($id);

        if (! $evento) {
            return response()->json([
                'message' => 'Evento no encontrado'
            ], 404);
        }

        $evento->delete();

        return response()->json([
            'message' => 'Evento eliminado correctamente'
        ], 200);
        
    }

    /*
    Resolver un evento deportivo.
     *
     * Este método:
     * - Define el resultado del evento (local, empate, visitante)
     * - Cambia el estado del evento a "finalizado"
     * - Procesa todas las apuestas relacionadas
     * - Determina si cada apuesta fue ganada o perdida
     * - Acredita las ganancias al saldo del usuario ganador

    */
    
    public function resolver(Request $request, string $id)
{
    $validator = Validator::make($request->all(), [
        'resultado' => 'required|in:local,empate,visitante',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    $evento = Evento::find($id);

    if (! $evento) {
        return response()->json([
            'message' => 'Evento no encontrado'
        ], 404);
    }

    if ($evento->estado === 'finalizado') {
        return response()->json([
            'message' => 'El evento ya fue resuelto'
        ], 400);
    }

    $evento->estado = 'finalizado';
    $evento->resultado = $request->resultado;
    $evento->save();

    $apuestas = \App\Models\Apuesta::with(['cuota', 'user'])
        ->where('evento_id', $evento->id)
        ->get();

    foreach ($apuestas as $apuesta) {

        if ($apuesta->cuota && $apuesta->cuota->tipo_apuesta === $request->resultado) {

            $apuesta->estado = 'ganada';
            $apuesta->save();

            $apuesta->user->saldo += $apuesta->ganancia_posible;
            $apuesta->user->save();

        } else {

            $apuesta->estado = 'perdida';
            $apuesta->save();
        }
    }

    return response()->json([
        'message' => 'Evento resuelto correctamente',
        'evento' => $evento,
        'apuestas_procesadas' => $apuestas->count()
    ], 200);
}


}