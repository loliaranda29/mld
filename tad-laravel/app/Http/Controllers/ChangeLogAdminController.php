<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChangeLogAdminController extends Controller
{
    /** Dataset “fake” para clonar UI (6.8k filas) */
    protected function seed()
    {
        // cacheamos en sesión para no generar cada request
        $key = 'seed_changelog';
        if (session()->has($key)) return collect(session($key));

        $mods = ['Configuraciones','Trámites','Inspectores','Pagos','Citas'];
        $sections = ['Escritorios','Catálogos','Permisos','Plantillas','Operaciones'];
        $actions  = ['Crear','Actualizar','Eliminar','Asignar','Aprobar','Rechazar'];

        $rows = collect();
        $total = 6844;

        for ($i=0; $i<$total; $i++) {
            $mod = $mods[array_rand($mods)];
            $sec = $sections[array_rand($sections)];
            $acc = $actions[array_rand($actions)];

            $rows->push([
                'modulo'  => $mod,
                'seccion' => $sec,
                'accion'  => $acc,
                'fecha'   => now()->subMinutes(rand(0, 60*24*180))->format('d/m/Y H:i:s') . ' hrs',
                'user'    => fake()->name(),
                'email'   => Str::of(fake()->safeEmail())->replace('.', '')->value(), // estética
            ]);
        }

        session([$key => $rows]);
        return $rows;
    }

    /** Mapeo de pestañas => filtro por módulo */
    protected function tabToModulo(?string $tab): ?string
    {
        return match($tab) {
            'configuraciones' => 'Configuraciones',
            'tramites'        => 'Trámites',
            'inspectores'     => 'Inspectores',
            'pagos'           => 'Pagos',
            'citas'           => 'Citas',
            default           => null, // todos
        };
    }

    public function index(Request $request)
    {
        $tab     = $request->get('tab', 'todos'); // pestaña activa
        $modLike = $request->get('modulo');       // select
        $email   = trim((string) $request->get('email'));
        $fecha   = $request->get('fecha');        // YYYY-MM-DD

        $all  = $this->seed();

        // Filtros por pestaña (módulo)
        if ($m = $this->tabToModulo($tab)) {
            $all = $all->where('modulo', $m);
        }

        // Filtros por select módulo, correo y fecha (fecha = “d/m/Y …”)
        if ($modLike) {
            $all = $all->filter(fn($r) => Str::contains(Str::lower($r['modulo']), Str::lower($modLike)));
        }
        if ($email !== '') {
            $all = $all->filter(fn($r) => Str::contains(Str::lower($r['email']), Str::lower($email)));
        }
        if ($fecha) {
            $all = $all->filter(function ($r) use ($fecha) {
                // r['fecha'] = "14/08/2025 10:54:39 hrs"
                $d = \DateTime::createFromFormat('d/m/Y H:i:s \h\r\s', $r['fecha']);
                return $d?->format('Y-m-d') === $fecha;
            });
        }

        // Paginación simple
        $page    = max(1, (int) $request->get('page', 1));
        $perPage = (int) $request->get('per_page', 5);
        $total   = $all->count();
        $items   = $all->slice(($page-1)*$perPage, $perPage)->values();

        // Opciones select de módulo
        $modulos = ['Configuraciones','Trámites','Inspectores','Pagos','Citas'];

        return view('pages.profile.funcionario.registro_cambios.index', [
            'active'  => 'registro',
            'items'   => $items,
            'total'   => $total,
            'page'    => $page,
            'perPage' => $perPage,
            'tab'     => $tab,
            'modulos' => $modulos,
            'filters' => [
                'modulo' => $modLike,
                'email'  => $email,
                'fecha'  => $fecha,
            ],
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        // Reaplicamos filtros para exportar todo el set filtrado
        $tab     = $request->get('tab', 'todos');
        $modLike = $request->get('modulo');
        $email   = trim((string) $request->get('email'));
        $fecha   = $request->get('fecha');

        $rows = $this->seed();
        if ($m = $this->tabToModulo($tab)) $rows = $rows->where('modulo', $m);
        if ($modLike) $rows = $rows->filter(fn($r) => str_contains(Str::lower($r['modulo']), Str::lower($modLike)));
        if ($email !== '') $rows = $rows->filter(fn($r) => str_contains(Str::lower($r['email']), Str::lower($email)));
        if ($fecha) {
            $rows = $rows->filter(function ($r) use ($fecha) {
                $d = \DateTime::createFromFormat('d/m/Y H:i:s \h\r\s', $r['fecha']);
                return $d?->format('Y-m-d') === $fecha;
            });
        }

        $filename = 'registro_cambios_'.now()->format('Ymd_His').'.csv';
        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Módulo','Sección','Acción','Fecha','Nombre del usuario','Correo del usuario']);
            foreach ($rows as $r) {
                fputcsv($out, [$r['modulo'],$r['seccion'],$r['accion'],$r['fecha'],$r['user'],$r['email']]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
