<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
  use HasFactory;

  // Nombre de la tabla (por si no sigue la convención "usuarios")
  protected $table = 'pagos';

  // Campos que se pueden asignar masivamente
  protected $fillable = [
    'numero_folio',
    'tramite',
    'folio_tramite',
    'numero_transaccion',
    'costo',
    'fecha',
    'cuit',
    'padron_nomenclatura',
    'estado',
  ];
}
