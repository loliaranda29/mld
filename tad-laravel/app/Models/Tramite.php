<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tramite extends Model
{
    protected $table = 'tramites';

    protected $fillable = [
        'nombre','descripcion','publicado','disponible','mostrar_inicio',
        'tipo','estatus','mensaje',
        'general_json','formulario_json','etapas_json','documento_json','config_json',
        'parent_id',
    ];

    // --- Relaciones para jerarquía y vínculos ---
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function hijos()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function relacionados()
    {
        return $this->belongsToMany(
            self::class,
            'tramite_relaciones',
            'tramite_id',
            'relacionado_id'
        )->withPivot('tipo')->withTimestamps();
    }

    public function relacionadosComoDestino()
    {
        return $this->belongsToMany(
            self::class,
            'tramite_relaciones',
            'relacionado_id',
            'tramite_id'
        )->withPivot('tipo')->withTimestamps();
    }
    protected $casts = [
    'general_json'    => 'array',
    'formulario_json' => 'array',
    'etapas_json'     => 'array',
    'documento_json'  => 'array',
    'config_json'     => 'array',
];


}