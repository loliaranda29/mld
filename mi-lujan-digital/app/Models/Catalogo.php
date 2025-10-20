<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalogo extends Model
{
    use SoftDeletes;

    protected $table = 'catalogos';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'icono',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(CatalogoItem::class, 'catalogo_id');
    }
}
