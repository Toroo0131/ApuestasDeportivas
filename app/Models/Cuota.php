<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/*
 * Modelo que representa la tabla "cuotas" en la base de datos.
 * Las cuotas definen las probabilidades o multiplicadores de pago
 * asociados a un evento deportivo dentro del sistema de apuestas.
 *
 * Campos principales:
 * - evento_id: evento al que pertenece la cuota
 * - tipo_apuesta: tipo de resultado apostado (local, empate, visitante)
 * - cuota: valor multiplicador de la apuesta
 *
 * Relaciones:
 * - Una cuota pertenece a un evento
 * - Una cuota puede tener muchas apuestas asociadas
 */
class Cuota extends Model
{
   use HasFactory;

   //campos que pueden ser asignados masivamente

   protected $fillable = [
    'evento_id',
    'tipo_apuesta',
    'cuota'

   ];

   //relacion con el evento asociado a la cuota
   //una cuota pertenece a un evento deportivo

       public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    //relacion con las apuestas que utilizan esta cuota
    //una cuota puede tener muchas apuestas asociadas

    public function apuestas()
    {
        return $this->hasMany(Apuesta::class);
    }
}
