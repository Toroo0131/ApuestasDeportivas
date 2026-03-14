<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apuesta extends Model
{
    protected $fillable = [
        'user_id',
        'evento_id',
        'cuota_id',
        'monto',
        'ganancia_posible',
        'estado',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function cuota()
    {
        return $this->belongsTo(Cuota::class);
    }
}
