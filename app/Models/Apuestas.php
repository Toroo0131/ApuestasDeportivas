<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
 * Modelo que representa la tabla "apuestas" en la base de datos.
 * Este modelo gestiona la información de las apuestas realizadas
 * por los usuarios en los distintos eventos deportivos.
 *
 * Campos principales:
 * - user_id: usuario que realiza la apuesta
 * - evento_id: evento deportivo asociado
 * - cuota_id: cuota utilizada en la apuesta
 * - monto: dinero apostado
 * - ganancia_posible: posible ganancia calculada
 * - estado: estado de la apuesta (pendiente, ganada, perdida)
 *
 * Relaciones:
 * - Una apuesta pertenece a un usuario
 * - Una apuesta pertenece a un evento
 * - Una apuesta pertenece a una cuota
 */


class Apuestas extends Model
{
    use HasFactory;

    //nombre de la tabla asociada al modelo

    protected $table = 'apuestas';

    //campos que pueden ser asignados masivamente
    protected $fillable = [
        'user_id',
        'evento_id',
        'cuota_id',
        'monto',
        'ganancia_posible',
        'estado',

    ];

    /*
    relacion con el usuario que realizo la apuesta

    una apuesta pertenece a un usuario
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //relacion con el evento asociado a la apuesta
    //una apuesta pertenece a un evento

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    //relacion con la cuota utilizada en la apuesta
    //una apuesta pertenece a una cuota

    public function cuota()
    {
        return $this->belongsTo(Cuota::class);
    }
}
