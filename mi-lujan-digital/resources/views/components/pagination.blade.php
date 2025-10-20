@if ($items->hasPages())
<nav>
  <ul class="pagination justify-content-center mt-4">
    {{-- Botón anterior --}}
    @if ($items->onFirstPage())
    <li class="page-item disabled"><span class="page-link">Anterior</span></li>
    @else
    <li class="page-item">
      <a class="page-link" href="{{ $items->appends(request()->query())->previousPageUrl() }}" rel="prev">Anterior</a>

    </li>
    @endif

    {{-- Páginas --}}
    @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
    <li class="page-item {{ $items->currentPage() == $page ? 'active' : '' }}">
      <a class="page-link" href="{{ $url }}">{{ $page }}</a>
    </li>
    @endforeach

    {{-- Botón siguiente --}}
    @if ($items->hasMorePages())
    <li class="page-item">
      <a class="page-link" href="{{ $items->appends(request()->query())->nextPageUrl() }}" rel="next">Siguiente</a>

    </li>
    @else
    <li class="page-item disabled"><span class="page-link">Siguiente</span></li>
    @endif
  </ul>
</nav>
@endif