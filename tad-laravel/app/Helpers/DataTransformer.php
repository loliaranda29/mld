<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class DataTransformer
{
  public static function paginarTransformados(Collection $transformados, $paginadorOriginal, Request $request): LengthAwarePaginator
  {
    return new LengthAwarePaginator(
      $transformados,
      $paginadorOriginal->total(),
      $paginadorOriginal->perPage(),
      $paginadorOriginal->currentPage(),
      [
        'path' => $paginadorOriginal->path(),
        'query' => $request->query(),
      ]
    );
  }

  public static function usuarios($usuario)
  {
    return [
      'id' => $usuario->id,
      'nombre' => $usuario->nombre,
      'apellido' => $usuario->apellido,
      'cuil' => $usuario->cuil,
      'email' => $usuario->email,
      'permiso' => $usuario->permiso->nombre ?? 'Sin permiso',
      //'password' => $usuario->password,
    ];
  }

  public static function pagos($pago)
  {
    return [
      "id" => $pago->id,
      "usuario_id" => $pago->usuario_id,
      "numero_folio" => $pago->numero_folio,
      "tramite" => $pago->tramite,
      "folio_tramite" => $pago->folio_tramite,
      "numero_transaccion" => $pago->numero_transaccion,
      "costo" => $pago->costo,
      "fecha" => $pago->fecha,
      "cuit" => $pago->cuit,
      "padron_nomenclatura" => $pago->padron_nomenclatura,
      "estado" => $pago->estado,
    ];
  }
  public static function inspecciones($inspeccion)
  {
    return [
      "id" => $inspeccion->id,
      "folio_inspeccion" => $inspeccion->folio_inspeccion,
      "fecha_inspeccion" => $inspeccion->fecha_inspeccion,
      "estado" => $inspeccion->estado,
      "direccion" => $inspeccion->direccion,
      "inspector" =>  self::inspectores($inspeccion->inspector) ?? null,
    ];
  }
  public static function inspectores($inspector)
  {
    return [
      "id" => $inspector->id,
      "nombre" => $inspector->nombre,
      "apellido" => $inspector->apellido,
      "puesto" => $inspector->puesto,
      "telefono" => $inspector->telefono,
      "email" => $inspector->email,
      "superior" =>  self::superiores($inspector->superior) ?? null,
    ];
  }
  public static function superiores($superior)
  {
    return [
      "id" => $superior->id,
      "nombre" => $superior->nombre,
      "apellido" => $superior->apellido,
      "cargo" => $superior->cargo,
      "telefono" => $superior->telefono,
      "email" => $superior->email,
    ];
  }
  public static function tramites($tramite)
  {
    return [
      "id" => $tramite->id,
      "inmueble" => $tramite->inmueble ?? null,
      "inicio_tramite" => $tramite->inicio_tramite ?? null,
      "escribano" => $tramite->escribano ?? null,
      "transmitente" => $tramite->transmitente ?? null,
      "adquirinte" => $tramite->adquirinte ?? null,
      "documentos" => $tramite->documentos ?? null,
      "expediente" => $tramite->expediente ?? null,
      "fecha_emision" => $tramite->fecha_emision ?? null,
      "tipo" => $tramite->tipo ?? null,
      "estatus" => $tramite->estatus ?? null,
      "etapas" => $tramite->etapas ?? null,
      "mensaje" => $tramite->mensaje ?? null,
    ];
  }
  public static function citas($cita)
  {
    return [
      "id" => $cita->id,
      "tramite" => $cita->tramite->expediente ?? null,
      "tramite_id" => $cita->tramite->id ?? null,
      "fecha" => $cita->fecha ?? null,
      "estado" => $cita->estado ?? null,
    ];
  }
}
