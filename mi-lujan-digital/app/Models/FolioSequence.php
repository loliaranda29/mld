<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolioSequence extends Model
{
    protected $fillable = ['tramite_id','scope','current'];

    public function tramite() {
        return $this->belongsTo(Tramite::class);
    }

    public function folioSequences() { return $this->hasMany(FolioSequence::class); }

}
