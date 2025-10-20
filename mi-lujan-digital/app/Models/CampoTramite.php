<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampoTramite extends Model
{
    use HasFactory;

    protected $table = 'campo_tramites';

    protected $fillable = [
        'tramite_id',
        'nombre',
        'etiqueta',
        'tipo',
        'requerido',
        'orden',
        'valores',
        'condicional',
    ];

    protected $casts = [
        'valores' => 'array',
        'condicional' => 'array',
        'requerido' => 'boolean',
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }
}
