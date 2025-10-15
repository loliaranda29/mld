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

    protected $casts = [
        'datos'           => 'array',
        'respuestas_json' => 'array',
    ];

    protected $attributes = [
        'estado' => 'iniciado',
    ];

    public function tramite(): BelongsTo { return $this->belongsTo(\App\Models\Tramite::class, 'tramite_id'); }
    public function usuario(): BelongsTo { return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id'); }
}

