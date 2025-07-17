<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Superior extends Model
{
  use HasFactory;

  // Nombre de la tabla (por si no sigue la convención "usuarios")
  protected $table = 'superiores';

  // Campos que se pueden asignar masivamente
  protected $fillable = [
    'nombre',
    'apellido',
    'cargo',
    'telefono',
    'email',
  ];

  public function inspector()
  {
    return $this->hasMany(Inspector::class, 'superior_id');
  }
}
