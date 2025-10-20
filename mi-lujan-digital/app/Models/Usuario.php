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
  protected $table = 'users';

  // Campos que se pueden asignar masivamente
  protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
        'current_team_id',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

  public function permiso()
  {
    return $this->belongsTo(Permiso::class, 'permiso_id');
  }
  public function pago()
  {
    return $this->hasMany(Pago::class, 'user_id');
  }
  // ...
public function solicitudes()
{
    return $this->hasMany(\App\Models\Solicitud::class, 'usuario_id');
}

}
