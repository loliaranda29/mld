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

    

protected function aparienciaDefaults(): array
{
    // Valores por defecto (tomados de tus capturas)
    return [
        // Colores base
        'primary'   => '#298E8CFF',
        'secondary' => '#298E8CFF',
        'accent'    => '#24B889FF',
        'error'     => '#FF5252FF',
        'info'      => '#2196F3FF',
        'success'   => '#4CAF50FF',
        'warning'   => '#FFBC00FF',

        // Textos
        'text_title'     => '#0B0B0BFF',
        'text_subtitle'  => '#0B0B0BFF',
        'text_body'      => '#0B0B0BEO', // si querés exacto usa #0B0B0BFF

        // Botones
        'btn_primary'   => '#298E8CFF',
        'btn_secondary' => '#298E8CFF',
        'chips'         => '#80CBC4FF',

        // Home
        'home_card_title' => '#298E8CFF',
        'home_tabs'       => '#298E8CFF',
        'home_lists'      => '#298E8CFF',

        // Ficha de trámite
        'doc_icons'       => '#298E8CFF',
        'doc_cards_border'=> '#298E8CFF',
        'doc_section'     => '#298E8CFF',

        // Cards/Modales
        'card_title_bg'   => '#298E8CFF',
        'modal_toolbar'   => '#298E8CFF',

        // Tabs
        'tabs_border'     => '#298E8CFF',
        'tabs_text'       => '#0B0B0BFF',
        'tabs_card_bg'    => '#298E8CFF',
        'tabs_active'     => '#FFFFFFFF',
        'tabs_disabled'   => '#686868FF',

        // Íconos generales
        'icons'           => '#298E8CFF',
    ];
}

public function aparienciaIndex(\Illuminate\Http\Request $request)
{
    $paleta = $request->session()->get('config_apariencia', $this->aparienciaDefaults());

    return view('pages.profile.funcionario.configuracion.apariencia', [
        'active' => 'configuracion',
        'paleta' => $paleta,
    ]);
}

public function aparienciaGuardar(\Illuminate\Http\Request $request)
{
    // Lista blanca de claves permitidas (para evitar extraños)
    $keys = array_keys($this->aparienciaDefaults());

    $data = $request->validate([
        // todos como string; validación soft para permitir #RRGGBB o #RRGGBBAA
    ] + collect($keys)->mapWithKeys(fn($k)=>[$k=>['nullable','string','max:12']])->all());

    // Normalizamos: si falta alguno, usamos el existente/defecto
    $actual = $request->session()->get('config_apariencia', $this->aparienciaDefaults());
    foreach ($keys as $k) {
        $val = $data[$k] ?? $actual[$k] ?? null;
        // Limpieza simple: uppercase y quitar espacios
        if (is_string($val)) {
            $val = strtoupper(trim($val));
        }
        $actual[$k] = $val ?: $actual[$k];
    }

    $request->session()->put('config_apariencia', $actual);

    return back()->with('ok', 'Apariencia guardada.');
}

// app/Http/Controllers/ConfiguracionAdminController.php

protected function seoDefaults(): array
{
    return [
        'title'       => 'Mi Luján',
        'description' => 'Mi Luján Digital',
        'favicon'     => null, // path público (storage) o null
    ];
}

public function seoIndex(\Illuminate\Http\Request $request)
{
    $seo = $request->session()->get('config_seo', $this->seoDefaults());

    return view('pages.profile.funcionario.configuracion.seo', [
        'active'   => 'configuracion.seo',
        'seo'      => $seo,
        'faviconUrl' => $seo['favicon'] ? asset('storage/'.$seo['favicon']) : null,
    ]);
}

public function seoGuardar(\Illuminate\Http\Request $request)
{
    $data = $request->validate([
        'title'       => ['required','string','max:120'],
        'description' => ['required','string','max:255'],
        'favicon'     => ['nullable','file','mimes:ico,png,svg','max:512'], // 512KB
    ]);

    $seo = $request->session()->get('config_seo', $this->seoDefaults());
    $seo['title']       = $data['title'];
    $seo['description'] = $data['description'];

    if ($request->hasFile('favicon')) {
        // Asegúrate de tener el symlink: php artisan storage:link
        $ext  = $request->file('favicon')->getClientOriginalExtension();
        $name = 'favicon.'.strtolower($ext);
        $path = $request->file('favicon')->storeAs('seo', $name, 'public'); // storage/app/public/seo/favicon.*
        $seo['favicon'] = $path;
    }

    $request->session()->put('config_seo', $seo);

    return back()->with('ok', 'SEO guardado.');
}
// app/Http/Controllers/ConfiguracionAdminController.php

protected function mapaDefaults(): array
{
    return [
        'title' => 'Ubicación del mapa',
        // Centro por defecto: Luján de Cuyo (aprox)
        'lat'   => -33.0160,
        'lng'   => -68.8750,
        'zoom'  => 11,
    ];
}

public function mapaIndex(\Illuminate\Http\Request $request)
{
    $mapa = $request->session()->get('config_mapa', $this->mapaDefaults());

    return view('pages.profile.funcionario.configuracion.mapa', [
        'active' => 'configuracion.mapa',
        'mapa'   => $mapa,
        'gmaps_key' => config('services.google.maps_key'), // ver paso 4
    ]);
}

public function mapaGuardar(\Illuminate\Http\Request $request)
{
    $data = $request->validate([
        'title' => ['required','string','max:150'],
        'lat'   => ['required','numeric','between:-90,90'],
        'lng'   => ['required','numeric','between:-180,180'],
        'zoom'  => ['required','integer','min:3','max:20'],
    ]);

    $config = $request->session()->get('config_mapa', $this->mapaDefaults());
    $config = array_merge($config, $data);

    $request->session()->put('config_mapa', $config);

    return back()->with('ok', 'Ubicación guardada.');
}



}
