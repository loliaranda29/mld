<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Tramite;
use App\Models\CitaConfiguracion;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class CitaController extends Controller
{
  /**
   * Mostrar formulario de creación de cita (lado funcionario)
   */
  public function index()
  {

    return Inertia::render('Citas/Citas');
  }

  public function show($id)
  {
    $cita = CitaConfiguracion::with('tramite')->findOrFail($id);

    // Si querés traer todos los trámites para desplegarlos también:
    $tramites = Tramite::select('id', 'nombre')->get();



    return inertia('Citas/Show', ['cita' => $this->normalizeModelData($cita), 'tramites' => $tramites]);
  }


  public function create()
  {
    // Obtenemos los trámites disponibles
    $tramites = Tramite::select('id', 'nombre')->get();

    return Inertia::render('Citas/Create', [
      'tramites' => $tramites
    ]);
  }

  public function edit()
  {
    $citasConfiguraciones = CitaConfiguracion::select('id', 'tramite_id', 'fecha_inicio', 'fecha_fin', 'duracion_bloque', 'cupo_por_bloque', 'estado')
      ->with(['tramite:id,nombre'])
      ->get();

    return Inertia::render('Citas/Edit', [
      'citasConfiguraciones' => $citasConfiguraciones,
    ]);
  }



  public function store(Request $request)
  {
    return $this->validator('create', $request);
  }

  public function update(Request $request, $id)
  {
    return $this->validator('update', $request, $id);
  }


  public function validator($method, $request, $id = null)
  {
    try {
      $cita = null;
      $redirectRoute = null;

      // Validar con mensajes personalizados
      $data = $request->validate([
        'tramite_id' => [
          'required',
          'integer',
          'exists:tramites,id',
          $method === 'create'
            ? Rule::unique('citas_configuraciones', 'tramite_id')
            : Rule::unique('citas_configuraciones', 'tramite_id')->ignore($id),
        ],
        'fecha_inicio'    => 'required|date',
        'fecha_fin'       => 'required|date|after_or_equal:fecha_inicio',
        'dias_atencion'   => 'required|array|min:1',
        'hora_inicio'     => 'required|date_format:H:i',
        'hora_fin'        => 'required|date_format:H:i|after:hora_inicio',
        'hora_inicio_2'   => 'nullable|date_format:H:i',
        'hora_fin_2'      => 'nullable|date_format:H:i|after:hora_inicio_2',
        'dividir_horario' => 'boolean',
        'duracion_bloque' => 'required|integer|min:5',
        'cupo_por_bloque' => 'required|integer|min:1',
        'estado'          => 'required|in:activo,inactivo',
        'todo_el_anio'    => 'boolean',
      ], [
        // Mensajes personalizados en español
        'tramite_id.required' => 'Debe seleccionar un trámite.',
        'tramite_id.unique'   => 'Ya existe una configuración para este trámite.',
        'tramite_id.exists'   => 'El trámite seleccionado no existe.',
        'fecha_inicio.required' => 'Debe indicar la fecha inicial.',
        'fecha_fin.required' => 'Debe indicar la fecha final.',
        'fecha_fin.after_or_equal' => 'La fecha final no puede ser anterior a la inicial.',
        'dias_atencion.required' => 'Debe seleccionar al menos un día de atención.',
        'hora_inicio.required' => 'Debe indicar la hora de inicio.',
        'hora_fin.required' => 'Debe indicar la hora de fin.',
        'hora_fin.after' => 'La hora de fin debe ser posterior a la de inicio.',
        'duracion_bloque.required' => 'Debe indicar la duración de cada bloque.',
        'duracion_bloque.min' => 'La duración mínima de un bloque es de 5 minutos.',
        'cupo_por_bloque.required' => 'Debe indicar la cantidad de cupos por bloque.',
        'estado.required' => 'Debe seleccionar un estado (activo o inactivo).',
      ]);

      // Ajustes de datos
      if (empty($data['dividir_horario'])) {
        $data['hora_inicio_2'] = null;
        $data['hora_fin_2'] = null;
      }

      $data['dias_atencion'] = implode(',', $data['dias_atencion']);

      // Guardar
      if ($method === 'update') {
        $cita = CitaConfiguracion::findOrFail($id);
        $cita->update($data);
        $redirectRoute = route('citas.show', $id);
      } else {
        $cita = CitaConfiguracion::create($data);
        $redirectRoute = route('citas.create');
      }

      if ($cita) {
        return redirect()
          ->to($redirectRoute)
          ->with('success', '✅ Configuración guardada correctamente.');
      }

      // Si no se guarda por alguna razón
      return back()->withErrors([
        'error' => '❌ No se pudo guardar la configuración. Intente nuevamente.',
      ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
      // Capturar el error y redirigir con mensajes claros
      $errores = collect($e->errors())->map(function ($msgs, $campo) {
        $campoTraducido = match ($campo) {
          'tramite_id' => 'Trámite',
          'fecha_inicio' => 'Fecha inicial',
          'fecha_fin' => 'Fecha final',
          'dias_atencion' => 'Días de atención',
          'hora_inicio' => 'Hora de inicio',
          'hora_fin' => 'Hora de fin',
          'duracion_bloque' => 'Duración por bloque',
          'cupo_por_bloque' => 'Cupo por bloque',
          'estado' => 'Estado',
          default => ucfirst(str_replace('_', ' ', $campo)),
        };
        return "{$campoTraducido}: {$msgs[0]}";
      })->values()->implode(' | ');

      return back()
        ->withErrors(['message' => $errores])
        ->withInput();
    } catch (\Exception $ex) {
      // Cualquier otro error inesperado
      return back()->withErrors([
        'message' => '⚠️ Error inesperado: ' . $ex->getMessage(),
      ]);
    }
  }

  function normalizeModelData($model)
  {
    $table = $model->getTable();
    $normalized = [];

    foreach ($model->getAttributes() as $key => $value) {
      $type = Schema::getColumnType($table, $key);
      if ($key === 'dias_atencion') {
        if (is_string($value)) {
          // Si contiene comas → dividir en array
          if (str_contains($value, ',')) {
            $value = array_values(array_filter(array_map('trim', explode(',', $value))));
          }
          // Si es texto plano → envolver en array
          else {
            $value = [$value];
          }
        }
        // Si no es string y no es null → asegurar array
        elseif (!is_array($value) && !is_null($value)) {
          $value = [$value];
        }

        $normalized[$key] = $value;
        continue; // ⛔ Salta el switch, ya se procesó este campo
      }
      switch ($type) {
        case 'date':
        case 'datetime':
        case 'timestamp':
          $normalized[$key] = $value ? Carbon::parse($value)->format('Y-m-d') : null;
          break;

        case 'time':
          $normalized[$key] = $value ? Carbon::parse($value)->format('H:i') : null;
          break;

        case 'bit':
          $normalized[$key] = (bool) $value;
          break;

        default:
          $normalized[$key] = $value;
      }
    }

    return $normalized;
  }
}
