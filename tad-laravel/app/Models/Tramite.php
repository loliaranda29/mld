<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    protected $fillable = [
        'nombre','descripcion','publicado','disponible','mostrar_inicio',
        'acepta_solicitudes','acepta_pruebas','modulo_citas','modulo_inspectores',
        'tipo','estatus','mensaje',
        'general_json','formulario_json','etapas_json','documento_json','config_json',
    ];

    protected $casts = [
        'publicado'           => 'boolean',
        'disponible'          => 'boolean',
        'mostrar_inicio'      => 'boolean',
        'acepta_solicitudes'  => 'boolean',
        'acepta_pruebas'      => 'boolean',
        'modulo_citas'        => 'boolean',
        'modulo_inspectores'  => 'boolean',

        'general_json'        => 'array',
        'formulario_json'     => 'array',
        'etapas_json'         => 'array',
        'documento_json'      => 'array',
        'config_json'         => 'array',
    ];
}
