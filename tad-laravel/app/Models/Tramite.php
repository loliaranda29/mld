<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tramite extends Model
{
    use HasFactory;

    protected $table = 'tramites';

    /**
     * Campos permitidos para asignación masiva.
     * Ajustá esta lista si agregás/quitas columnas en la tabla `tramites`.
     */
    protected $fillable = [
        // Básicos
        'nombre',
        'descripcion',
        'tipo',
        'estatus',
        'mensaje',

        // JSON por pestañas
        'general_json',
        'formulario_json',
        'etapas_json',
        'documento_json',
        'config_json',

        // Switches / flags
        'publicado',
        'disponible',
        'mostrar_inicio',
        'acepta_solicitudes',
        'acepta_pruebas',
        'modulo_citas',
        'modulo_inspectores',

        // Jerarquía
        'parent_id',
    ];

    /**
     * Casts para convertir automáticamente al LEER/ESCRIBIR.
     * (Si seteás arrays desde el controlador, Eloquent serializa a JSON solo).
     */
    protected $casts = [
        'general_json'    => 'array',
        'formulario_json' => 'array',
        'etapas_json'     => 'array',
        'documento_json'  => 'array',
        'config_json'     => 'array',

        'publicado'          => 'boolean',
        'disponible'         => 'boolean',
        'mostrar_inicio'     => 'boolean',
        'acepta_solicitudes' => 'boolean',
        'acepta_pruebas'     => 'boolean',
        'modulo_citas'       => 'boolean',
        'modulo_inspectores' => 'boolean',
    ];

    /**
     * Valores por defecto (evita null en JSONs nuevos).
     */
    protected $attributes = [
        'general_json'    => '[]',
        'formulario_json' => '[]',
        'etapas_json'     => '[]',
        'documento_json'  => '[]',
        'config_json'     => '[]',
    ];

    /* =========================================================
     | Relaciones
     * ========================================================= */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function hijos(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function relacionados(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'tramite_relaciones',
            'tramite_id',
            'relacionado_id'
        )->withPivot('tipo')->withTimestamps();
    }

    public function relacionadosComoDestino(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'tramite_relaciones',
            'relacionado_id',
            'tramite_id'
        )->withPivot('tipo')->withTimestamps();
    }

    /**
     * Devuelve las secciones "activables" del builder de formulario.
     */
    public function seccionesActivables(): array
    {
        $json = $this->formulario_json;
        if (is_string($json)) {
            $json = json_decode($json, true);
        }

        $sections = is_array($json['sections'] ?? null) ? $json['sections'] : [];
        return array_values(array_filter($sections, fn ($s) => !empty($s['activable'])));
    }

    /** Vínculos laterales (relación muchos-a-muchos consigo mismo) */
    public function vinculos(): BelongsToMany
    {
        return $this->belongsToMany(
            Tramite::class,
            'tramite_vinculos',      // tabla pivote
            'tramite_id',            // FK a este
            'vinculo_id'             // FK al relacionado
        );
    }

    protected static function booted(): void
    {
        static::deleting(function (Tramite $t) {
            DB::table('tramite_vinculos')
                ->where('tramite_id', $t->id)
                ->orWhere('vinculo_id', $t->id)
                ->delete();
        });
    }
}
