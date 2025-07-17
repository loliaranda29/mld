<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspeccion extends Model
{
  use HasFactory;

  // Nombre de la tabla (por si no sigue la convención "usuarios")
  protected $table = 'inspecciones';

  // Campos que se pueden asignar masivamente
  protected $fillable = [
    'folio_inspeccion',
    'fecha_inspeccion',
    'estado',
    'direccion',
  ];

  public function inspector()
  {
    return $this->belongsTo(Inspector::class, 'inspector_id');
  }
}
