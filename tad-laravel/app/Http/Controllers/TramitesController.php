<?php


namespace App\Http\Controllers;

use App\Helpers\DataTransformer;
use App\Models\Tramite;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TramitesController extends Controller
{
    public function catalogo(Request $request)
    {
        $q = Tramite::query();

        // Mostrar sólo trámites habilitados para iniciar (si existen esas columnas)
        if (Schema::hasColumn('tramites', 'publicado'))          $q->where('publicado', 1);
        if (Schema::hasColumn('tramites', 'disponible'))         $q->where('disponible', 1);
        if (Schema::hasColumn('tramites', 'acepta_solicitudes')) $q->where('acepta_solicitudes', 1);

        if ($s = $request->get('q')) {
            $q->where(function($w) use ($s){
                $w->where('nombre','like',"%{$s}%")->orWhere('descripcion','like',"%{$s}%");
            });
        }

        $plantillas = $q->orderBy('nombre')->paginate(12);

        // Fallback de desarrollo: si no hay, muestra todos para poder probar
        if (!$plantillas->count()) $plantillas = Tramite::orderBy('nombre')->paginate(12);

        return view('pages.profile.ciudadano.catalogo', [
            'active'     => 'tramites',
            'plantillas' => $plantillas,
        ]);
    }

    // app/Http/Controllers/TramitesController.php
public function ficha(Tramite $tramite)
{
    $config  = json_decode($tramite->config_json ?? '[]', true) ?: [];
    $etapas  = json_decode($tramite->etapas_json ?? '[]', true) ?: [];

    // Si usás tabla de requisitos, traelos; si no, podés leerlos de documento_json
    $requisitos = \DB::table('requerimientos')
        ->where('tramite_id', $tramite->id)
        ->orderBy('id')
        ->pluck('nombre'); // ajustá el campo

    $puedeIniciar = (bool) ($tramite->acepta_solicitudes && ($tramite->disponible ?? 1));

    return view('pages.profile.ciudadano.ficha', compact(
        'tramite','config','etapas','requisitos','puedeIniciar'
    ));
}


    private function getFormularioSchema(\App\Models\Tramite $tramite): array
{
    // Busca en 'formularios' por tramite_id (ajustá el nombre de la columna con JSON)
    $form = DB::table('formularios')
        ->where('tramite_id', $tramite->id)
        ->latest('id')->first();

    if ($form) {
        foreach (['schema_json','formulario_json','json','config_json','data_json'] as $col) {
            if (!empty($form->$col)) {
                $arr = is_array($form->$col) ? $form->$col : json_decode($form->$col, true);
                if (is_array($arr)) return $arr;
            }
        }
    }

    // Fallback: si está embebido en 'tramites'
    foreach (['formulario_json','documento_json'] as $col) {
        $raw = data_get($tramite, $col);
        if ($raw) {
            $arr = is_array($raw) ? $raw : json_decode($raw, true);
            if (is_array($arr)) return $arr;
        }
    }

    return ['sections' => []];
}

public function iniciar(\App\Models\Tramite $tramite)
{
    $schema = $this->getFormularioSchema($tramite);

    $correlativo = (int)(DB::table('solicitudes')->max('id') ?? 0) + 1;
    $prefijo = sprintf('TRAM-%d-%s-%03d', auth()->id(), now()->format('Ymd'), $correlativo);

    $solicitud = \App\Models\Solicitud::create([
        'tramite_id' => $tramite->id,
        'usuario_id' => auth()->id(),
        'expediente' => $prefijo,
        'estado'     => 'iniciado',
        'datos'      => $schema,   // ← acá guardamos el formulario
    ]);

    return redirect()->route('profile.solicitudes.show', $solicitud->id)
        ->with('info', 'Solicitud iniciada.');
}

    private function json($value): array
    {
        if (is_array($value)) return $value;
        if (is_string($value) && trim($value) !== '') {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    
}
