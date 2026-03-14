<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::all();

        return response()->json([
            'message' => 'Listado de eventos',
            'data' => $eventos
        ], 200);
    }

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
}