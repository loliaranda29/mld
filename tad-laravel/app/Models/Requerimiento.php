<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requerimiento extends Model
{
    protected $fillable = [
        'tramite_id','instancia_id',
        'section_key','section_name','form_schema',
        'estado','fecha_limite','mensaje_funcionario',
        'respuestas_json','respondido_at',
        'creado_por','dirigido_a'
    ];

    protected $casts = [
        'form_schema'    => 'array',
        'respuestas_json'=> 'array',
        'fecha_limite'   => 'datetime',
        'respondido_at'  => 'datetime',
    ];

    public function tramite(){ return $this->belongsTo(Tramite::class); }
    public function instancia(){ return $this->belongsTo(Instancia::class); } // ajustá
    public function creador(){ return $this->belongsTo(User::class, 'creado_por'); }
    public function destinatario(){ return $this->belongsTo(User::class, 'dirigido_a'); }
}
