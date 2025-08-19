<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    protected $fillable = [
        'titulo','descripcion','publicado','acepta_solicitudes','acepta_pruebas',
        'modulo_citas','modulo_inspectores',
        'general_json','formulario_json','etapas_json','documento_json','config_json',
        'creado_por'
    ];

    protected $casts = [
        'publicado' => 'boolean',
        'acepta_solicitudes' => 'boolean',
        'acepta_pruebas' => 'boolean',
        'modulo_citas' => 'boolean',
        'modulo_inspectores' => 'boolean',
        'general_json' => 'array',
        'formulario_json' => 'array',
        'etapas_json' => 'array',
        'documento_json' => 'array',
        'config_json' => 'array',
    ];

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}

