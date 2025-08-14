<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EstadisticasAdminController extends Controller
{
    public function index(Request $request)
    {
        // Filtros simulados
        $deps = [
            'todas' => 'Todas las dependencias',
            'eco'   => 'Secretaría de Economía',
            'seg'   => 'Secretaría de Seguridad',
            'sal'   => 'Secretaría de Salud',
        ];
        $dep = $request->get('dep', 'todas');
        $fecha = $request->get('fecha', '2025-01'); // ejemplo de periodo YYYY-MM

        // ==== KPI (mock) ====
        $kpis = [
            'tramites'      => 0 + rand(3000, 12000),
            'inspecciones'  => 0 + rand(0, 50),
            'notificaciones'=> 0 + rand(0, 200),
            'citas'         => 0 + rand(0, 400),
        ];

        // ==== Estado de solicitudes (Pie) ====
        $solicitudes = [
            ['Aprobado',       7178],
            ['Rechazado',      2329],
            ['Desistido',       707],
            ['En proceso',     2620],
            ['En prevención',   175],
            ['Citado',            2],
            ['Pendiente de pago',  48],
            ['Cerrado',           47],
            ['Other',              2],
        ];

        // ==== Estado de citas (Doughnut) ====
        $citas = [
            ['Atendida',  rand(50, 350)],
            ['Cancelada', rand(1, 20)],
            ['En proceso',rand(20, 150)],
        ];

        // ==== Inspecciones por dependencia (Stacked) ====
        // Primera columna string (dependencia); series numéricas
        $inspecciones = [
            //  Dep.,  Por asignar,  Asignada,  Concluida, Cancelada,  Atrasada
            ['Sin dependencia',     0,   0,   0,   0,   0],
            ['Economía',            0,   1,   0,   0,   0],
            ['Salud',               0,   0,   0,   0,   0],
            ['Seguridad',           0,   0,   0,   0,   0],
        ];

        // ==== Notificaciones por dependencia (Column) ====
        $notificaciones = [
            ['Economía',   rand(0, 50)],
            ['Salud',      rand(0, 50)],
            ['Seguridad',  rand(0, 50)],
            ['Tránsito',   rand(0, 50)],
        ];

        return view('pages.profile.funcionario.estadisticas.index', [
            'active'         => 'estadisticas',
            'deps'           => $deps,
            'dep'            => $dep,
            'fecha'          => $fecha,
            'kpis'           => $kpis,
            'solicitudes'    => $solicitudes,
            'citas'          => $citas,
            'inspecciones'   => $inspecciones,
            'notificaciones' => $notificaciones,
        ]);
    }
}

