@extends('layouts.app-funcionario')

@section('title', 'Dashboard')

@section('profile_content')
<div class="container-fluid mt-3">
  {{-- Migas --}}
  <nav class="mb-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">Dashboard</h5>
    </div>

    <div class="card-body">
      {{-- Filtros --}}
      <form class="row g-2 align-items-center mb-3" method="GET">
        <div class="col-md-4">
          <select name="dep" class="form-select">
            @foreach($deps as $k => $label)
              <option value="{{ $k }}" @selected($k===$dep)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <input name="fecha" type="month" class="form-control" value="{{ $fecha }}">
        </div>
        <div class="col-auto">
          <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
          <button type="button" class="btn btn-outline-secondary" title="Subir"><i class="bi bi-upload"></i></button>
        </div>
        <div class="col-auto">
          <button type="button" class="btn btn-outline-secondary" title="Descargar"><i class="bi bi-download"></i></button>
        </div>
      </form>

      {{-- KPIs --}}
      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <div class="border rounded p-3 d-flex align-items-center gap-3">
            <div class="fs-3"><i class="bi bi-diagram-3"></i></div>
            <div>
              <div class="small text-muted">Trámites solicitados</div>
              <div class="fw-bold fs-5">{{ number_format($kpis['tramites']) }}</div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded p-3 d-flex align-items-center gap-3">
            <div class="fs-3"><i class="bi bi-person-workspace"></i></div>
            <div>
              <div class="small text-muted">Total de inspecciones</div>
              <div class="fw-bold fs-5">{{ number_format($kpis['inspecciones']) }}</div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded p-3 d-flex align-items-center gap-3">
            <div class="fs-3"><i class="bi bi-file-earmark-text"></i></div>
            <div>
              <div class="small text-muted">Total de notificaciones</div>
              <div class="fw-bold fs-5">{{ number_format($kpis['notificaciones']) }}</div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded p-3 d-flex align-items-center gap-3">
            <div class="fs-3"><i class="bi bi-calendar2-week"></i></div>
            <div>
              <div class="small text-muted">Total de citas</div>
              <div class="fw-bold fs-5">{{ number_format($kpis['citas']) }}</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Gráficos fila 1 --}}
      <div class="row g-3">
        <div class="col-lg-12">
          <div class="card border-0">
            <div class="card-body">
              <h6 class="card-title">Estatus de las solicitudes</h6>
              <div id="chart-solicitudes" style="height:420px;"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- Gráficos fila 2 --}}
      <div class="row g-3 mt-1">
        <div class="col-lg-6">
          <div class="card border-0">
            <div class="card-body">
              <h6 class="card-title">Estatus de citas</h6>
              <div id="chart-citas" style="height:320px;"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card border-0">
            <div class="card-body">
              <h6 class="card-title">Estatus de inspecciones por dependencia</h6>
              <div id="chart-inspecciones" style="height:320px;"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- Gráficos fila 3 --}}
      <div class="row g-3 mt-1">
        <div class="col-12">
          <div class="card border-0">
            <div class="card-body">
              <h6 class="card-title">Estatus de notificaciones por dependencia</h6>
              <div id="chart-notificaciones" style="height:320px;"></div>
            </div>
          </div>
        </div>
      </div>

    </div>{{-- card-body --}}
  </div>{{-- card --}}
</div>

@push('scripts')
  {{-- Google Charts --}}
  <script src="https://www.gstatic.com/charts/loader.js"></script>
  <script>
    const DATA = {
      solicitudes: @json($solicitudes),
      citas:        @json($citas),
      inspecciones: @json($inspecciones),
      notificaciones: @json($notificaciones),
    };

    google.charts.load('current', {'packages':['corechart','bar']});
    google.charts.setOnLoadCallback(drawAll);

    function drawAll(){
      drawSolicitudes();
      drawCitas();
      drawInspecciones();
      drawNotificaciones();
    }

    function drawSolicitudes(){
      const dt = new google.visualization.DataTable();
      dt.addColumn('string','Estado');
      dt.addColumn('number','Cantidad');
      dt.addRows(DATA.solicitudes);

      const chart = new google.visualization.PieChart(document.getElementById('chart-solicitudes'));
      chart.draw(dt, {
        legend: { position: 'right' },
        pieHole: 0, // 0 = pie, >0 = donut
        chartArea: { left:0, top:20, width:'100%', height:'85%' }
      });
    }

    function drawCitas(){
      const dt = new google.visualization.DataTable();
      dt.addColumn('string','Estado');
      dt.addColumn('number','Cantidad');
      dt.addRows(DATA.citas);

      const chart = new google.visualization.PieChart(document.getElementById('chart-citas'));
      chart.draw(dt, {
        legend: { position: 'bottom' },
        pieHole: 0.6,
        chartArea: { width:'100%', height:'80%' }
      });
    }

    function drawInspecciones(){
      // Estructura correcta: primera columna string, el resto number
      const dt = new google.visualization.DataTable();
      dt.addColumn('string','Dependencia');
      dt.addColumn('number','Por asignar inspector');
      dt.addColumn('number','Asignada');
      dt.addColumn('number','Concluida');
      dt.addColumn('number','Cancelada');
      dt.addColumn('number','Visita atrasada');
      dt.addRows(DATA.inspecciones);

      const chart = new google.charts.Bar(document.getElementById('chart-inspecciones'));
      const options = {
        isStacked: true,
        legend: { position: 'bottom' },
        chartArea: { left:60, top:20, width:'90%', height:'70%' },
        bars: 'vertical',
        vAxis: { minValue: 0 }
      };
      chart.draw(dt, google.charts.Bar.convertOptions(options));
    }

    function drawNotificaciones(){
      const dt = new google.visualization.DataTable();
      dt.addColumn('string','Dependencia');  // etiqueta
      dt.addColumn('number','Notificaciones');
      dt.addRows(DATA.notificaciones);

      const chart = new google.charts.Bar(document.getElementById('chart-notificaciones'));
      chart.draw(dt, google.charts.Bar.convertOptions({
        legend: { position: 'none' },
        chartArea: { left:60, top:20, width:'90%', height:'70%' },
        bars: 'vertical',
        vAxis: { minValue: 0 }
      }));
    }

    // Redibuja al redimensionar
    window.addEventListener('resize', () => { if (google && google.visualization) drawAll(); });
  </script>
@endpush
@endsection
