<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'tramite_id',
        'usuario_id',
        'expediente',
        'estado',
        'datos',
    ];

    protected $casts = [
        'datos' => 'array',
    ];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
