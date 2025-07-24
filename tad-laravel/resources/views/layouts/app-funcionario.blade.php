<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Mi Luján Digital')</title>

  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Material Design Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">

  {{-- Tailwind y personalizados --}}
  <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">

  {{-- Livewire Styles --}}
  @livewireStyles

  <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gray-50">
  <div x-data="menus" x-init="$watch('seccionActiva', value => console.log('Sección activa:', value))">
    @include('components.header')

    <div class="flex">
      @include('components.menu-funcionario')

      <main class="flex-1 p-6 ml-64">
        <div x-show="seccionActiva === 'tramites'">
          @livewire('tramites-listado')
        </div>
        <div x-show="seccionActiva !== 'tramites'">
          @yield('content')
        </div>
      </main>
    </div>

    @include('components.footer')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  {{-- Livewire Scripts --}}
  @livewireScripts

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('menus', () => ({
        seccionActiva: 'inicio',
        openMenu: null,
        menus: @json(config('menus.funcionario'))
      }));
    });
  </script>
</body>

</html>
