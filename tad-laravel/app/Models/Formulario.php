<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    protected $fillable = ['tramite_id', 'estructura'];

    public function tramite()
    {
        return $this->belongsTo(Tramite::class);
    }
}
