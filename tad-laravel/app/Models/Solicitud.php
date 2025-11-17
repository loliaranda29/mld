<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    protected $fillable = [
        'tramite_id',
        'usuario_id',
        'expediente',
        'estado',
        'datos',
        'respuestas_json',
    ];

    // 👇 Estos sí deben ser 'array' (no '[]')
    protected $casts = [
        'datos'           => 'array',
        'respuestas_json' => 'array',
    ];

    // Valores por defecto seguros
    protected $attributes = [
        'estado'          => 'iniciado',
        'datos'           => '[]',
        'respuestas_json' => '[]',
    ];

    public function tramite(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Tramite::class, 'tramite_id');
    }

    public function usuario(): BelongsTo
    {
        // 🔧 Antes apuntaba a App\Models\User (no existe en tu repo)
        return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id');
    }
}
