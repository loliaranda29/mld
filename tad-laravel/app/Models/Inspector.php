<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspector extends Model
{
  use HasFactory;

  // Nombre de la tabla (por si no sigue la convención "usuarios")
  protected $table = 'inspectores';

  // Campos que se pueden asignar masivamente
  protected $fillable = [
    'nombre',
    'apellido',
    'puesto',
    'telefono',
    'email',
  ];

  public function inspeccion()
  {
    return $this->hasMany(Inspeccion::class, 'inspector_id');
  }
  public function superior()
  {
    return $this->belongsTo(Superior::class, 'superior_id');
  }
}
