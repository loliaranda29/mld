<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
  use HasFactory;

  // Nombre de la tabla (por si no sigue la convención "usuarios")
  protected $table = 'tramites';

  // Campos que se pueden asignar masivamente
  protected $fillable = [
    'expediente',
    'fecha_emision',
    'tipo',
    'estatus',
    'etapas',
    'mensaje',
  ];
  public function cita()
  {
    return $this->hasMany(Cita::class, 'tramite_id');
  }
}
