<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    protected $fillable = [
        'deporte',
        'equipo_local',
        'equipo_visitante',
        'fecha_evento',
        'estado',

    ];

    protected $casts = [
        'fecha_evento' => 'datetime',
    ];
}
