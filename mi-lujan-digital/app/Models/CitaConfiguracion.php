<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaConfiguracion extends Model
{
  use HasFactory;

  // Nombre de la tabla (por si no sigue la convención "usuarios")
  protected $table = 'citas_configuraciones';

  // Campos que se pueden asignar masivamente
  protected $fillable = [
    'tramite_id',
    'fecha_inicio',
    'fecha_fin',
    'dias_atencion',
    'hora_inicio',
    'hora_fin',
    'hora_inicio_2',
    'hora_fin_2',
    'dividir_horario',
    'duracion_bloque',
    'cupo_por_bloque',
    'estado',
    'todo_el_anio',
  ];

  public function tramite()
  {
    return $this->belongsTo(Tramite::class, 'tramite_id');
  }
}
