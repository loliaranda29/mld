@extends('layouts.app-funcionario')

@section('profile_content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Gestión de Trámites</h1>
    <a href="{{ route('funcionario.tramites.create') }}" class="btn btn-primary">
      + Nuevo trámite
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Nombre</th>
              <th>Padre</th>
              <th>Subtrámites</th>
              <th>Vínculos</th>
              <th>Estado</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
          @forelse($tramites as $t)
            <tr>
              <td>
                <strong>{{ $t->nombre }}</strong>
                @if($t->descripcion)
                  <div class="text-muted small">{{ Str::limit($t->descripcion, 80) }}</div>
                @endif
              </td>
              <td class="text-muted">
                {{ $t->parent?->nombre ?? '—' }}
              </td>
              <td>
                {{-- mostramos count rápido; si querés listado, podés expandir --}}
                {{ $t->hijos()->count() }}
              </td>
              <td>
                {{-- vínculos como origen --}}
                {{ $t->relacionados()->count() }}
              </td>
              <td>
                @if($t->publicado)
                  <span class="badge bg-success">Publicado</span>
                @else
                  <span class="badge bg-secondary">Borrador</span>
                @endif
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('funcionario.tramites.edit', $t->id) }}">Editar</a>

                <form action="{{ route('funcionario.tramites.destroy', $t->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('¿Eliminar definitivamente este trámite?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </td>
            </tr>

            {{-- Si querés mostrar subtrámites en línea (nivel 1) --}}
            @foreach($t->hijos as $h)
              <tr class="table-sm">
                <td>
                  <span class="text-muted">↳</span> {{ $h->nombre }}
                </td>
                <td class="text-muted">{{ $h->parent?->nombre ?? '—' }}</td>
                <td>{{ $h->hijos()->count() }}</td>
                <td>{{ $h->relacionados()->count() }}</td>
                <td>
                  @if($h->publicado)
                    <span class="badge bg-success">Publicado</span>
                  @else
                    <span class="badge bg-secondary">Borrador</span>
                  @endif
                </td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('funcionario.tramites.edit', $h->id) }}">Editar</a>
                  <form action="{{ route('funcionario.tramites.destroy', $h->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('¿Eliminar este subtrámite?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                  </form>
                </td>
              </tr>
            @endforeach

          @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No hay trámites aún.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
