<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    protected $fillable = [
        'tramite_id','usuario_id','expediente','estado','datos',
    ];

    protected $casts = [
        'datos' => 'array',
        'respuestas_json' => 'array',
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }

    public function usuario()
    {
        // si tu modelo de usuarios se llama Usuario y usa tabla 'users'
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
    
}
