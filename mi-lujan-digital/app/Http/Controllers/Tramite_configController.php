<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\Catalogo;
use App\Models\CatalogoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Tramite_configController extends Controller
{
    /* =========================================================
     |  Listado
     * ========================================================= */
    public function indexFuncionario()
    {
        $tramites = Tramite::with(['parent','hijos','relacionados'])
            ->orderBy('created_at','desc')
            ->get();

        return view('pages.profile.funcionario.tramite_config', compact('tramites'));
    }

    /* =========================================================
     |  Crear
     * ========================================================= */
    public function create()
    {
        $tramite          = new Tramite();
        $tramitesListado  = Tramite::orderBy('nombre')->pluck('nombre','id');

        return view('pages.profile.funcionario.tramite_create', [
            'tramite'                   => $tramite,
            'tramitesListado'           => $tramitesListado,
            'relacionadosSeleccionados' => [],
            'hijosSeleccionados'        => [],
            'etapas'                    => [],

            // Catálogos que alimentan la pestaña General
            'dependencias'              => $this->catOptions('dependencias'),
            'categorias'                => $this->catOptions('categorias'),
            'oficinas'                  => $this->catOptions('oficinas'),
            'ubicaciones'               => $this->catOptions('ubicaciones'),
        ]);
    }

    /* =========================================================
     |  Guardar
     * ========================================================= */
    public function store(Request $request)
{
    $data = $this->validateData($request);

    // General
    $graw = $this->validateGeneral($request);
    $data['general_json'] = $this->buildGeneralJson($graw);

    // Normalizar JSONs de pestañas (¡clave!)
    $data = $this->coerceJsonFields($request, $data);

    // Booleans + blindaje
    $data = $this->normalizeBooleans($request, $data);
    $data = $this->stripMissingColumns($data);

    Tramite::create($data);

    return redirect()->route('funcionario.tramite_config')
        ->with('success', 'Trámite creado con éxito.');
}


    /* =========================================================
     |  Editar
     * ========================================================= */
    public function edit($id)
    {
        $tramite = Tramite::with(['parent','hijos','relacionados'])->findOrFail($id);

        $tramitesListado           = Tramite::orderBy('nombre')->pluck('nombre','id');
        $relacionadosSeleccionados = $tramite->relacionados->pluck('id')->all();
        $hijosSeleccionados        = $tramite->hijos->pluck('id')->all();
        $etapas                    = $tramite->etapas_json ?? [];

        return view('pages.profile.funcionario.tramite_create', [
            'tramite'                   => $tramite,
            'tramitesListado'           => $tramitesListado,
            'relacionadosSeleccionados' => $relacionadosSeleccionados,
            'hijosSeleccionados'        => $hijosSeleccionados,
            'etapas'                    => $etapas,

            // Catálogos para la pestaña General
            'dependencias'              => $this->catOptions('dependencias'),
            'categorias'                => $this->catOptions('categorias'),
            'oficinas'                  => $this->catOptions('oficinas'),
            'ubicaciones'               => $this->catOptions('ubicaciones'),
        ]);
    }

    /* =========================================================
     |  Actualizar
     * ========================================================= */
    public function update(Request $request, $id)
{
    $tramite = Tramite::findOrFail($id);

    $data = $request->validate([
        'nombre'          => 'required|string|max:255',
        'descripcion'     => 'nullable|string',
        'formulario_json' => 'nullable',
        'etapas_json'     => 'nullable',
        'documento_json'  => 'nullable',
        'config_json'     => 'nullable',
    ]);

    // General
    $graw = $this->validateGeneral($request);
    $data['general_json'] = $this->buildGeneralJson($graw);

    // JSONs de pestañas (¡clave!)
    $data = $this->coerceJsonFields($request, $data);

    // Booleans + blindaje
    $data['publicado']          = (bool) $request->input('publicado',          $tramite->publicado);
    $data['disponible']         = (bool) $request->input('disponible',         $tramite->disponible);
    $data['mostrar_inicio']     = (bool) $request->input('mostrar_inicio',     $tramite->mostrar_inicio);
    $data['acepta_solicitudes'] = (bool) $request->input('acepta_solicitudes', $tramite->acepta_solicitudes);
    $data['acepta_pruebas']     = (bool) $request->input('acepta_pruebas',     $tramite->acepta_pruebas);
    $data['modulo_citas']       = (bool) $request->input('modulo_citas',       $tramite->modulo_citas);
    $data['modulo_inspectores'] = (bool) $request->input('modulo_inspectores', $tramite->modulo_inspectores);

    $data = $this->stripMissingColumns($data);

    $tramite->update($data);

    return redirect()->route('funcionario.tramite_config')
        ->with('success', 'Trámite actualizado correctamente.');
}
    /* =========================================================
     |  Eliminar
     * ========================================================= */
    public function destroy($id)
    {
        $tramite = Tramite::findOrFail($id);

        // 1) Quitar relación padre de los hijos
        Tramite::where('parent_id', $tramite->id)->update(['parent_id' => null]);

        // 2) Limpiar vínculos N:M en ambos sentidos (si existen)
        if (method_exists($tramite, 'relacionados')) {
            $tramite->relacionados()->detach();
        }
        if (method_exists($tramite, 'relacionadosComoDestino')) {
            $tramite->relacionadosComoDestino()->detach();
        }

        // 3) Borrar
        $tramite->delete();

        return redirect()
            ->route('funcionario.tramite_config')
            ->with('success', 'Trámite eliminado.');
    }

    /* =========================================================
     |  Upload media (imágenes/videos para textos enriquecidos)
     * ========================================================= */
    public function mediaUpload(Request $request)
    {
        $request->validate([
            'file' => ['required','file','mimes:jpg,jpeg,png,gif,webp,svg,mp4,webm,ogg','max:51200'] // 50MB
        ]);

        $path = $request->file('file')->store('tramites', 'public');

        return response()->json([
            'url' => Storage::disk('public')->url($path)
        ], 201);
    }

    /* =========================================================
     |  Helpers de validación / serialización
     * ========================================================= */

    /**
     * Validación "general" (pestaña General).
     */
    private function validateGeneral(Request $request): array
    {
        return $request->validate([
            'tutorial_html'      => ['nullable','string'],
            'modalidad'          => ['nullable','in:Presencial,Online,Presencial/Online'],
            'implica_costo'      => ['nullable','in:Con costo,Sin costo'],
            'detalle_costo_html' => ['nullable','string'],
            'telefono_oficina'   => ['nullable','string','max:60'],
            'horario_atencion'   => ['nullable','string','max:120'],

            'dependencia_id'     => ['nullable','integer','exists:catalogo_items,id'],
            'categoria_id'       => ['nullable','integer','exists:catalogo_items,id'],
            'oficina_id'         => ['nullable','integer','exists:catalogo_items,id'],
            'ubicacion_id'       => ['nullable','integer','exists:catalogo_items,id'],

            'descripcion_html'   => ['nullable','string'],
            'requisitos_html'    => ['nullable','string'],
            'pasos_html'         => ['nullable','string'],
        ]);
    }

    /**
     * Serializa los campos "General" a general_json con {id,nombre}.
     */
    private function buildGeneralJson(array $g): array
    {
        return [
            'tutorial_html'      => $g['tutorial_html']      ?? null,
            'modalidad'          => $g['modalidad']          ?? null,
            'implica_costo'      => $g['implica_costo']      ?? null,
            'detalle_costo_html' => $g['detalle_costo_html'] ?? null,
            'telefono_oficina'   => $g['telefono_oficina']   ?? null,
            'horario_atencion'   => $g['horario_atencion']   ?? null,

            'dependencia' => [
                'id'     => $g['dependencia_id'] ?? null,
                'nombre' => $this->itemNombre($g['dependencia_id'] ?? null),
            ],
            'categoria' => [
                'id'     => $g['categoria_id'] ?? null,
                'nombre' => $this->itemNombre($g['categoria_id'] ?? null),
            ],
            'oficina' => [
                'id'     => $g['oficina_id'] ?? null,
                'nombre' => $this->itemNombre($g['oficina_id'] ?? null),
            ],
            'ubicacion' => [
                'id'     => $g['ubicacion_id'] ?? null,
                'nombre' => $this->itemNombre($g['ubicacion_id'] ?? null),
            ],

            'descripcion_html'   => $g['descripcion_html'] ?? null,
            'requisitos_html'    => $g['requisitos_html']  ?? null,
            'pasos_html'         => $g['pasos_html']       ?? null,
        ];
    }

    /**
     * Validación base del recurso Tramite (campos comunes).
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'tipo'            => 'nullable|string|max:100',
            'estatus'         => 'nullable|string|max:100',
            'mensaje'         => 'nullable|string',

            // JSONs de pestañas (se sobreescribe general_json más abajo)
            'general_json'    => 'nullable',
            'formulario_json' => 'nullable',
            'etapas_json'     => 'nullable',
            'documento_json'  => 'nullable',
            'config_json'     => 'nullable',

            // switches
            'publicado'           => 'nullable',
            'disponible'          => 'nullable',
            'mostrar_inicio'      => 'nullable',
            'acepta_solicitudes'  => 'nullable',
            'acepta_pruebas'      => 'nullable',
            'modulo_citas'        => 'nullable',
            'modulo_inspectores'  => 'nullable',
        ]);
    }

    /**
     * Normaliza toggles/checkbox a boolean.
     */
    private function normalizeBooleans(Request $request, array $data): array
    {
        foreach ([
            'publicado','disponible','mostrar_inicio',
            'acepta_solicitudes','acepta_pruebas',
            'modulo_citas','modulo_inspectores'
        ] as $k) {
            $data[$k] = $request->boolean($k);
        }
        return $data;
    }

    /**
     * Si vienen strings JSON en $data, los decodifica a array.
     */
    private function normalizeJsonPayload(array $data, array $keys): array
    {
        foreach ($keys as $k) {
            if (!array_key_exists($k, $data)) continue;
            $v = $data[$k];
            if (is_string($v)) {
                $decoded = json_decode($v, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[$k] = $decoded;
                }
            }
        }
        return $data;
    }

    /**
     * Limita el array de data a columnas existentes en la tabla `tramites`.
     */
    private function stripMissingColumns(array $data): array
    {
        $existing = Schema::getColumnListing('tramites');
        return array_intersect_key($data, array_flip($existing));
    }

    /* =========================================================
     |  Helpers de Catálogos
     * ========================================================= */

    /**
     * Devuelve opciones [{id,nombre}] de un catálogo por slug o nombre.
     * Cachea 5 minutos.
     */
    private function catOptions(string $slug): array
    {
        $key = 'cat_opts_' . strtolower($slug);

        return Cache::remember($key, 300, function () use ($slug) {
            $catalogo = Catalogo::query()
                ->whereRaw('LOWER(slug) = ?', [strtolower($slug)])
                ->orWhereRaw('LOWER(nombre) = ?', [strtolower($slug)])
                ->first();

            if (!$catalogo) {
                return [];
            }

            return $catalogo->items()
                ->where('activo', 1)
                ->orderBy('orden')
                ->orderBy('nombre')
                ->get(['id','nombre'])
                ->map(fn ($i) => ['id' => $i->id, 'nombre' => $i->nombre])
                ->all();
        });
    }

    /**
     * Dado un id de CatalogoItem devuelve su nombre o null.
     */
    private function itemNombre(?int $id): ?string
    {
        if (!$id) return null;
        return optional(CatalogoItem::find($id))->nombre;
    }

    private function coerceJson($val): array
{
    if (is_array($val)) return $val;

    if (is_string($val)) {
        // 1° intento: JSON normal
        $j = json_decode($val, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($j)) return $j;

        // 2° intento: por si viene con backslashes (doble-encode)
        $j = json_decode(stripslashes($val), true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($j)) return $j;
    }
    return []; // fallback
}

private function coerceJsonFields(Request $request, array $data): array
{
    foreach (['formulario_json','etapas_json','documento_json','config_json'] as $k) {
        if ($request->has($k)) {
            $data[$k] = $this->coerceJson($request->input($k));
        }
    }
    return $data;
}

}
