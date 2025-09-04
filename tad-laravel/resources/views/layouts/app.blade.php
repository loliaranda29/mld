<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Mi Luján Digital')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">


  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  {{-- Material Design Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

  {{-- Estilos adicionales --}}
  <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">

  {{-- Alpine: principal por jsDelivr, con fallback a unpkg si falla --}}
  <script defer
          src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
          onerror="
            var s=document.createElement('script');
            s.defer=true;
            s.src='https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js';
            document.head.appendChild(s);
          ">
  </script>
  <script>
    // Aviso en consola si Alpine no cargó (ayuda a diagnosticar si un firewall bloquea ambos CDNs)
    window.addEventListener('DOMContentLoaded', function () {
      setTimeout(function () {
        if (!window.Alpine) {
          console.error('Alpine.js no se cargó. Verificá que el CDN no esté bloqueado por la red.');
        }
      }, 0);
    });
  </script>

  {{-- (opcional) estilos empujados desde vistas hijas --}}
  @stack('styles')
</head>
<body>
  @include('components.header')

  <main>
    @yield('content')
  </main>

  @include('components.footer')

  {{-- JS base (Bootstrap bundle) --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Aquí se inyectan los scripts pusheados desde sublayouts/páginas (EditorJS, etc.) --}}
  @stack('scripts')
</body>
</html>
