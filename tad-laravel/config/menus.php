<?php

return [
  'funcionario' => [
    [
      'title' => 'Ventanilla Digital',
      'iconSvg' => '<i class="mdi mdi-calendar"></i>',
      'items' => [
        ['url' => '#', 'label' => 'Constructor de ficha'],
        ['label' => 'Listado de trámites', 'customClick' => 'tramites'],
        ['url' => '#', 'label' => 'Bandeja de entrada'],
        ['url' => '#', 'label' => 'Configuración'],
      ],
    ],
    [
      'title' => 'Inspectores',
      'iconSvg' => '<i class="mdi mdi-account"></i>',
      'items' => [
        ['url' => '#', 'label' => 'Bandeja de entrada'],
        ['url' => '#', 'label' => 'Bandeja de asignación'],
        ['url' => '#', 'label' => 'Configuración'],
      ],
    ],
    [
      'title' => 'Pagos',
      'iconSvg' => '<i class="mdi mdi-cash"></i>',
      'items' => [
        ['url' => '#', 'label' => 'Lista de pagos'],
        ['url' => '#', 'label' => 'Configuración'],
      ],
    ],
    [
      'title' => 'Citas',
      'iconSvg' => '<i class="mdi mdi-calendar-check"></i>',
      'items' => [
        ['url' => '#', 'label' => 'Bandeja de entrada'],
        ['url' => '#', 'label' => 'Bandeja de asignación'],
        ['url' => '#', 'label' => 'Configuración'],
      ],
    ],
    [
      'title' => 'Usuarios',
      'iconSvg' => '<i class="mdi mdi-account-group"></i>',
      'items' => [
        ['url' => '#', 'label' => 'Ciudadanos'],
        ['url' => '#', 'label' => 'Institucionales'],
        ['url' => '#', 'label' => 'Funcionarios'],
        ['url' => '#', 'label' => 'Visualizadores'],
        ['url' => '#', 'label' => 'Roles'],
        ['url' => '#', 'label' => 'Configuración'],
      ],
    ],
    [
      'title' => 'Catálogos',
      'iconSvg' => '<i class="mdi mdi-book"></i>',
      'items' => [
        ['url' => '#', 'label' => 'Catálogos'],
      ],
    ],
    [
      'title' => 'Filtros',
      'iconSvg' => '<i class="mdi mdi-filter"></i>',
      'items' => [
        ['url' => '#', 'label' => 'Filtros'],
      ],
    ],
    [
      'title' => 'Configuración',
      'iconSvg' => '<i class="mdi mdi-cog"></i>',
      'items' => [
        ['url' => '#', 'label' => 'Configuración'],
      ],
    ],
    [
      'title' => 'Estadísticas',
      'iconSvg' => '<i class="mdi mdi-chart-bar"></i>',
      'items' => [
        ['url' => '#', 'label' => 'Estadísticas'],
      ],
    ],
    [
      'title' => 'Registro de cambios',
      'iconSvg' => '<i class="mdi mdi-timer-refresh-outline"></i>',
      'items' => [
        ['url' => '#', 'label' => 'Registro de cambios'],
      ],
    ],
    [
      'title' => 'Centro de ayuda +',
      'iconSvg' => '<i class="mdi mdi-help-circle"></i>',
      'items' => [
        ['url' => '#', 'label' => 'Centro de ayuda +', 'external' => true],
      ],
    ],
  ],
];
