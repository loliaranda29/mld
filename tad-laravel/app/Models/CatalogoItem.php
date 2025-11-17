<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogoItem extends Model
{
    use SoftDeletes;

    protected $table = 'catalogo_items';

    protected $fillable = [
        'catalogo_id',
        'nombre',
        'codigo',
        'orden',
        'activo',
        'meta',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'meta'   => 'array',
    ];

    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class, 'catalogo_id');
    }
}

