<?php
// app/Http/Controllers/ConfiguracionAdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ConfiguracionAdminController extends Controller
{
    /** Valores por defecto para clonar la UI */
    protected function defaults(): array
    {
        return [
            'idioma'                => 'es_AR',
            'template_enabled'      => true,
            'tramites_internos'     => false,
            'mostrar_todos_campos'  => false,
            'editar_formulario'     => false,
            'modulo_empresas'       => false,
            'apoderados'            => false,
            'momento_cobro'         => 'al_enviar', // al_enviar | al_llegar_etapa
            'autocomplete_fields'   => ['nombre','apellido_paterno','apellido_materno','correo','cuit','cuil','telefono_celular','localidad'],
            'identificador_usuario' => 'cuil',
            'dias_inhabiles'        => ['2023-11-08','2024-12-09','2024-12-24','2024-12-25','2024-12-31'],
        ];
    }

    /** Catálogo de campos autocompletables para chips */
    protected function catalogoCampos(): array
    {
        return [
            'nombre' => 'Nombre',
            'apellido_paterno' => 'Apellido paterno',
            'apellido_materno' => 'Apellido materno',
            'correo' => 'Correo electrónico',
            'cuit' => 'CUIT',
            'cuil' => 'CUIL',
            'fecha_nac' => 'Fecha de nacimiento',
            'telefono_celular' => 'Teléfono celular',
            'telefono_fijo' => 'Teléfono fijo',
            'localidad' => 'Localidad',
            'provincia' => 'Provincia',
            'barrio' => 'Barrio',
            'calle' => 'Calle',
            'numero_int' => 'Número interior',
            'numero_ext' => 'Número exterior',
            'direccion_publica' => 'Dirección pública',
            'nro_acta' => 'Número de acta constitutiva',
            'fecha_venc' => 'Fecha de vencimiento',
            'inicio_op' => 'Fecha inicio de operaciones',
            'razon_social' => 'Nombre, denominación o razón social',
            'genero' => 'Género',
            'referencias' => 'Referencias',
        ];
    }

    /** Catálogo de idiomas */
    protected function idiomas(): array
    {
        return [
            'es_AR' => 'Español argentino',
            'es_ES' => 'Español (España)',
            'en_US' => 'English (US)',
        ];
    }

    public function index(Request $request)
    {
        $config = $request->session()->get('config_general', $this->defaults());
        return view('pages.profile.funcionario.configuracion.index', [
            'active'     => 'configuracion',
            'config'     => $config,
            'idiomas'    => $this->idiomas(),
            'campos'     => $this->catalogoCampos(),
        ]);
    }

    /** Guarda el formulario principal */
    public function guardar(Request $request)
    {
        $data = $request->validate([
            'idioma'               => ['required', 'string'],
            'template_enabled'     => ['nullable','boolean'],
            'tramites_internos'    => ['nullable','boolean'],
            'mostrar_todos_campos' => ['nullable','boolean'],
            'editar_formulario'    => ['nullable','boolean'],
            'modulo_empresas'      => ['nullable','boolean'],
            'apoderados'           => ['nullable','boolean'],
            'momento_cobro'        => ['required','in:al_enviar,al_llegar_etapa'],
            'autocomplete_fields'  => ['array'],
            'autocomplete_fields.*'=> ['string'],
            'identificador_usuario'=> ['required','string'],
        ]);

        // Normalizar switches (checkbox no marcados no llegan)
        foreach (['template_enabled','tramites_internos','mostrar_todos_campos','editar_formulario','modulo_empresas','apoderados'] as $k) {
            $data[$k] = (bool) Arr::get($data, $k, false);
        }
        $data['autocomplete_fields'] = array_values(array_unique(Arr::get($data, 'autocomplete_fields', [])));

        $config = $request->session()->get('config_general', $this->defaults());
        $config = array_merge($config, $data);

        $request->session()->put('config_general', $config);

        return back()->with('ok', 'Configuración guardada.');
    }

    /** Agregar día inhábil (YYYY-MM-DD) */
    public function agregarInhabil(Request $request)
    {
        $request->validate([
            'dia' => ['required','date_format:Y-m-d'],
        ]);

        $config = $request->session()->get('config_general', $this->defaults());
        $dias   = collect($config['dias_inhabiles'] ?? []);
        if (! $dias->contains($request->dia)) {
            $dias->push($request->dia);
        }

        $config['dias_inhabiles'] = $dias->sort()->values()->all();
        $request->session()->put('config_general', $config);

        return back()->with('ok', 'Día inhábil agregado.');
    }

    /** Eliminar día inhábil (parámetro en ruta) */
    public function eliminarInhabil(Request $request, string $dia)
    {
        $config = $request->session()->get('config_general', $this->defaults());
        $config['dias_inhabiles'] = collect($config['dias_inhabiles'] ?? [])
            ->reject(fn($d) => $d === $dia)
            ->values()->all();

        $request->session()->put('config_general', $config);

        return back()->with('ok', 'Día inhábil eliminado.');
    }
}
