<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
 * Modelo que representa la tabla "eventos" en la base de datos.
 * Un evento corresponde a un partido o encuentro deportivo
 * en el cual los usuarios pueden realizar apuestas.
 *
 * Campos principales:
 * - deporte: tipo de deporte del evento
 * - equipo_local: equipo que juega como local
 * - equipo_visitante: equipo que juega como visitante
 * - fecha_evento: fecha y hora en que se realizará el evento
 * - estado: estado del evento (pendiente, finalizado, cancelado)
 * - resultado: resultado final del evento (local, empate, visitante)
 *
 * Relaciones:
 * - Un evento puede tener muchas apuestas asociadas
 */

class Evento extends Model
{
    use HasFactory;

    //nombre de la tabla asociada al modelo

    protected $table = 'eventos';

    // campos que pueden ser asignados masivamente

    protected $fillable = [
        'deporte',
        'equipo_local',
        'equipo_visitante',
        'fecha_evento',
        'estado',
        'resultado',

    ];

    protected $casts = [
        'fecha_evento' => 'datetime',
    ];

    //relacion con las apuestas asociadas al evento

    public function apuestas()
    {
        return $this->hasMany(Apuestas::class);
    }
}
