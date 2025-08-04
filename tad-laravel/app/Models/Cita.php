<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
  use HasFactory;

  // Nombre de la tabla (por si no sigue la convención "usuarios")
  protected $table = 'citas';

  // Campos que se pueden asignar masivamente
  protected $fillable = [
    'fecha',
    'estado',
  ];

  public function tramite()
  {
    return $this->belongsTo(Tramite::class, 'tramite_id');
  }
}
