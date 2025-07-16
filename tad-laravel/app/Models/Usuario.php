<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
  use HasFactory;

  // Nombre de la tabla (por si no sigue la convención "usuarios")
  protected $table = 'usuarios';

  // Campos que se pueden asignar masivamente
  protected $fillable = [
    'nombre',
    'apellido',
    'email',
    'cuil',
  ];
}
