<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapaTramite extends Model
{
    use HasFactory;

    protected $table = 'etapas_tramites';

    protected $fillable = [
        'tramite_id',
        'nombre',
        'descripcion',
        'orden',
        'oficina_id',
        'requiere_firma',
        'requiere_documentacion',
    ];

    protected $casts = [
        'requiere_firma' => 'boolean',
        'requiere_documentacion' => 'boolean',
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }

    public function oficina()
    {
        return $this->belongsTo(Oficina::class);
    }
}
