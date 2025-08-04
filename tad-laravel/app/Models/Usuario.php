<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
  use HasFactory;
  use Notifiable;
  // Nombre de la tabla (por si no sigue la convención "usuarios")
  protected $table = 'usuarios';

  // Campos que se pueden asignar masivamente
  protected $fillable = [
    'nombre',
    'apellido',
    'email',
    'cuil',
    'password',
  ];

  public function permiso()
  {
    return $this->belongsTo(Permiso::class, 'permiso_id');
  }
  public function pago()
  {
    return $this->hasMany(Pago::class, 'usuario_id');
  }
}
