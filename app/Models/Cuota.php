<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cuota extends Model
{
   use HasFactory;

   protected $fillable = [
    'evento_id',
    'tipo_apuesta',
    'cuota'

   ];

       public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}
