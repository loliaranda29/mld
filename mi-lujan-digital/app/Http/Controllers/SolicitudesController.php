<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SolicitudesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /** Mis solicitudes */
  public function index()
  {
    $solicitudes = Solicitud::with('tramite')
      ->where('usuario_id', auth()->id())
      ->latest('id')
      ->paginate(20);

    return view('pages.profile.ciudadano.solicitudes', compact('solicitudes'));
  }

  /** GET /profile/tramites/{tramite}/iniciar */
  public function create($tramiteId)
  {
    $tramite  = Tramite::findOrFail($tramiteId);
    $schema   = $this->schemaToArray($tramite->formulario_json);
    $sections = is_array($schema['sections'] ?? null) ? $schema['sections'] : [];


    return Inertia::render('Ciudadano/Tramites/NuevaSolicitud', [
      'tramite' => $tramite,
      'sections' => $sections,
    ]);
  }

  /** POST /profile/solicitudes */
  public function store(Request $request)
  {
    $user    = auth()->user();
    $tramite = Tramite::findOrFail($request->input('tramite_id'));

    if (!$tramite->acepta_solicitudes || !$tramite->disponible) {
      return back()->with('error', 'Este trámite no acepta solicitudes actualmente.');
    }

    // Schema base normalizado
    $schema = $this->schemaToArray($tramite->formulario_json);
    if (!isset($schema['sections']) || !is_array($schema['sections'])) {
      $schema['sections'] = [];
    }

    // 1) Reglas de validación (server-side, evita enviar vacío)
    [$rules, $attrs] = $this->rulesFromSchema($schema);
    $request->validate($rules, [], $attrs);

    // 2) Respuestas robustas (form[...] | answers_json | dummy | planos)
    [$posted] = $this->extractAnswers($request, $schema);

    // 3) Volcado en schema + archivos temporales
    $this->mergeIntoSchema($schema, $posted, $request);

    // 4) Guardar solicitud
    $expediente = $this->generarExpediente($tramite);

    $solicitud = Solicitud::create([
      'tramite_id'      => $tramite->id,
      'usuario_id'      => $user->id,
      'expediente'      => $expediente,
      'estado'          => 'iniciado',
      'datos'           => $schema,    // JSON con (posibles) value en fields
      'respuestas_json' => $posted,    // MAPA PLANO infalible
    ]);

    // 5) Archivos tmp => definitivos
    $this->finalizarArchivosEnSchema($solicitud);

    return redirect()->route('profile.tramites.detail', $solicitud->id)->with('success', 'Tu solicitud fue creada correctamente.');
  }

  /** Detalle */
  public function show($id)
  {
    $user = auth()->user();

    $solicitud = Solicitud::with('tramite')
      ->where('usuario_id', $user->id)
      ->findOrFail($id);

    // Fusionar respuestas_json sobre el schema por si faltaran value
    $schema  = $this->schemaToArray($solicitud->datos);
    $answers = is_array($solicitud->respuestas_json) ? $solicitud->respuestas_json : [];
    $schema  = $this->mergeAnswersForDisplay($schema, $answers);

    return view('pages.profile.ciudadano.details.solicitud', [
      'solicitud' => $solicitud,
      'schema'    => $schema,
    ]);
  }

  /** PUT/PATCH (si habilitas edición) */
  public function update(Request $request, $id)
  {
    $solicitud = Solicitud::where('usuario_id', auth()->id())->findOrFail($id);

    $schema = $this->schemaToArray($solicitud->datos);
    if (!isset($schema['sections']) || !is_array($schema['sections'])) {
      $schema['sections'] = [];
    }

    [$rules, $attrs] = $this->rulesFromSchema($schema);
    $request->validate($rules, [], $attrs);

    [$posted] = $this->extractAnswers($request, $schema);
    $this->mergeIntoSchema($schema, $posted, $request);

    $solicitud->datos = $schema;
    $solicitud->respuestas_json = array_replace((array)$solicitud->respuestas_json, (array)$posted);
    $solicitud->save();

    $this->finalizarArchivosEnSchema($solicitud);

    return back()->with('success', 'Datos guardados.');
  }

  /* ============================================================
     | Helpers
     * ========================================================== */

  private function schemaToArray($raw): array
  {
    if (is_array($raw)) return $raw;
    if ($raw instanceof \ArrayObject) return (array)$raw;
    if (is_object($raw)) return json_decode(json_encode($raw), true) ?: [];
    if (is_string($raw) && trim($raw) !== '') {
      $arr = json_decode($raw, true);
      return is_array($arr) ? $arr : [];
    }
    return [];
  }

  /** Reglas server-side desde el schema */
  private function rulesFromSchema(array $schema): array
  {
    $rules = [];
    $attrs = [];

    foreach (($schema['sections'] ?? []) as $si => $sec) {
      foreach (($sec['fields'] ?? []) as $fi => $f) {
        $name  = $f['name'] ?? "s{$si}_f{$fi}";
        $label = $f['label'] ?? $name;
        $type  = strtolower($f['type'] ?? 'text');
        $req   = !empty($f['required']);
        $acc   = trim($f['accept'] ?? '');

        if ($type === 'file') {
          $base    = "files.$name";
          $attrs[$base] = $label;

          $arrRule = $f['multiple'] ?? false ? ['array'] : [];
          $arrRule[] = $req ? 'required' : 'nullable';
          $rules[$base] = $arrRule;

          $itemRules = ['file'];
          $m = $this->acceptToMimes($acc);
          if ($m) $itemRules[] = "mimes:$m";
          if (isset($f['maxSize'])) $itemRules[] = "max:" . (intval($f['maxSize']) * 1024);
          $rules[$base . (!empty($f['multiple']) ? '.*' : '')] = $itemRules;

          continue;
        }

        $common = $req ? ['required'] : ['nullable'];
        $validation = strtolower($f['validation'] ?? '');
        if ($type === 'number' || $validation === 'number') $common[] = 'numeric';
        if ($type === 'date'   || $validation === 'date')   $common[] = 'date';
        if ($type === 'select' && !empty($f['options']) && is_array($f['options'])) {
          $opts = array_map(fn($x) => (string)$x, $f['options']);
          $common[] = 'in:' . implode(',', array_map(fn($x) => str_replace(',', '\,', $x), $opts));
        }

        $rules["form.$name"] = $common;
        $attrs["form.$name"] = $label;  // anidado
        $rules[$name]        = $common;
        $attrs[$name] = $label;         // plano
      }
    }

    return [$rules, $attrs];
  }


  private function acceptToMimes(string $accept): string
  {
    if ($accept === '') return '';
    $parts = array_map('trim', explode(',', $accept));
    $mimes = [];
    foreach ($parts as $p) {
      if (stripos($p, 'image/png') !== false) $mimes[] = 'png';
      elseif (stripos($p, 'image/jpeg') !== false || stripos($p, 'image/jpg') !== false) $mimes[] = 'jpg';
      elseif (stripos($p, 'application/pdf') !== false) $mimes[] = 'pdf';
    }
    return implode(',', array_unique($mimes));
  }

  /** Lee respuestas de todas las variantes posibles */
  private function extractAnswers(Request $request, array $schema): array
  {
    $expectedNames = [];
    foreach (($schema['sections'] ?? []) as $si => $sec) {
      foreach (($sec['fields'] ?? []) as $fi => $f) {
        $expectedNames[] = $f['name'] ?? "s{$si}_f{$fi}";
      }
    }

    $posted = $request->input('form', []);

    if (empty($posted)) {
      $rawJson = $request->input('answers_json')
        ?? $request->input('respuestas_json')
        ?? $request->input('form_serialized')
        ?? $request->input('respuestas');

      if ($rawJson) {
        try {
          $tmp = json_decode($rawJson, true, 512, JSON_THROW_ON_ERROR);
          if (is_array($tmp)) $posted = $tmp;
        } catch (\Throwable $e) { /* noop */
        }
      }
    }

    if (!empty($expectedNames)) {
      foreach ($expectedNames as $n) {
        if (!array_key_exists($n, $posted)) {
          if ($request->has($n)) {
            $posted[$n] = $request->input($n);
          } else {
            $dummy = '_dummy_' . $n;
            if ($request->has($dummy)) $posted[$n] = $request->input($dummy);
          }
        }
        if (isset($posted[$n]) && is_string($posted[$n])) {
          $s = trim($posted[$n]);
          if ($s !== '' && ($s[0] === '[' || $s[0] === '{')) {
            try {
              $dec = json_decode($s, true, 512, JSON_THROW_ON_ERROR);
              if (is_array($dec)) $posted[$n] = $dec;
            } catch (\Throwable $e) {
            }
          }
        }
      }
    }

    return [$posted, $expectedNames];
  }

  /** Inserta values al schema y procesa archivos (temporal) */
  private function mergeIntoSchema(array &$schema, array $posted, Request $request): void
  {
    foreach (($schema['sections'] ?? []) as $si => &$sec) {
      foreach (($sec['fields'] ?? []) as $fi => &$f) {
        $name     = $f['name'] ?? "s{$si}_f{$fi}";
        $type     = strtolower($f['type'] ?? 'text');
        $multiple = !empty($f['multiple']);

        if ($type === 'file') {
          $uploads = $request->file("files.$name");
          if (!$uploads) $uploads = $request->file("files.s{$si}_f{$fi}");

          $stored = [];
          if ($uploads) {
            if ($multiple && is_array($uploads)) {
              foreach ($uploads as $u) if ($u) $stored[] = $this->storeSolicitudFileTemp($u);
            } else {
              $stored[] = $this->storeSolicitudFileTemp($uploads);
            }
          }
          $f['value'] = $multiple ? $stored : ($stored[0] ?? null);
          continue;
        }

        $val = $posted[$name] ?? $posted["s{$si}_f{$fi}"] ?? null;

        if ($val === null) {
          $val = $request->input($name)
            ?? $request->input("s{$si}_f{$fi}")
            ?? $request->input("_dummy_{$name}")
            ?? $request->input("_dummy_s{$si}_f{$fi}");
        }

        if (is_string($val)) {
          $s = trim($val);
          if ($s !== '' && ($s[0] === '[' || $s[0] === '{')) {
            try {
              $dec = json_decode($s, true, 512, JSON_THROW_ON_ERROR);
              if (is_array($dec)) $val = $dec;
            } catch (\Throwable $e) {
            }
          }
        }

        $f['value'] = $val;
      }
    }
    unset($sec, $f);
  }

  /** Solo para mostrar: si falta value, lo completa con respuestas_json */
  private function mergeAnswersForDisplay(array $schema, array $answers): array
  {
    foreach (($schema['sections'] ?? []) as $si => &$sec) {
      foreach (($sec['fields'] ?? []) as $fi => &$f) {
        if (!array_key_exists('value', $f) || $f['value'] === null || $f['value'] === '') {
          $name = $f['name'] ?? "s{$si}_f{$fi}";
          if (array_key_exists($name, $answers)) {
            $f['value'] = $answers[$name];
          }
        }
      }
    }
    unset($sec, $f);
    return $schema;
  }

  /** Guarda archivo temporalmente */
  protected function storeSolicitudFileTemp(\Illuminate\Http\UploadedFile $file): array
  {
    $path = $file->store("solicitudes/tmp/" . auth()->id(), 'public');

    return [
      'tmp'   => true,
      'path'  => $path,
      'url'   => Storage::disk('public')->url($path),
      'name'  => $file->getClientOriginalName(),
      'size'  => $file->getSize(),
      'mime'  => $file->getClientMimeType(),
    ];
  }

  /** Mueve archivos tmp => definitivos y actualiza URLs */
  protected function finalizarArchivosEnSchema(Solicitud $solicitud): void
  {
    $data = $this->schemaToArray($solicitud->datos);
    $changed = false;

    foreach (($data['sections'] ?? []) as &$sec) {
      foreach (($sec['fields'] ?? []) as &$f) {
        if (($f['type'] ?? null) !== 'file' || empty($f['value'])) continue;

        $vals = is_array($f['value']) ? $f['value'] : [$f['value']];
        $new  = [];

        foreach ($vals as $v) {
          if (!empty($v['tmp']) && !empty($v['path'])) {
            $newPath = "solicitudes/{$solicitud->id}/" . basename($v['path']);
            Storage::disk('public')->move($v['path'], $newPath);
            $v['path'] = $newPath;
            $v['url']  = Storage::disk('public')->url($newPath);
            unset($v['tmp']);
            $changed = true;
          }
          $new[] = $v;
        }

        $f['value'] = is_array($f['value']) ? $new : ($new[0] ?? null);
      }
    }
    unset($sec, $f);

    if ($changed) {
      $solicitud->datos = $data;
      $solicitud->save();
    }
  }

  /** Genera código de expediente */
  protected function generarExpediente(Tramite $tramite): string
  {
    $prefix  = 'TRAM-' . $tramite->id . '-' . now()->format('Ymd');
    $count   = Solicitud::where('tramite_id', $tramite->id)
      ->whereDate('created_at', now()->toDateString())
      ->count() + 1;

    return $prefix . '-' . str_pad((string)$count, 3, '0', STR_PAD_LEFT);
  }
}
