@extends('layouts.profile')

@section('profile_content')
<div class="card border-0 shadow-sm rounded-4 px-4 py-5 mb-4" style="background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);">
  {{-- Header --}}
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
      <h4 class="mb-1 fw-bold" style="color: #1a202c;">Mis Solicitudes</h4>
      <p class="text-muted mb-0 small">Gestiona y consulta el estado de tus trámites</p>
    </div>
    <a href="{{ route('profile.catalogo') }}" 
       class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm"
       style="border-radius: 10px; font-weight: 500; transition: all 0.2s;"
       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';"
       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="12" y1="5" x2="12" y2="19"/>
        <line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Nuevo Trámite
    </a>
  </div>

  {{-- Search Bar --}}
  <form method="GET" action="" class="mb-4">
    <div class="position-relative">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" 
           style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); z-index: 10;">
        <circle cx="11" cy="11" r="8"/>
        <path d="m21 21-4.35-4.35"/>
      </svg>
      <input type="text" 
             name="search" 
             class="form-control ps-5 py-3 border-0 shadow-sm" 
             placeholder="Buscar por número de expediente..." 
             value="{{ request('search') }}"
             style="border-radius: 12px; background-color: #ffffff; font-size: 0.95rem;">
      <button class="btn btn-primary position-absolute end-0 top-0 bottom-0 me-1 my-1 px-4" 
              type="submit"
              style="border-radius: 10px; font-weight: 500;">
        Buscar
      </button>
    </div>
  </form>

  {{-- Solicitudes List --}}
  <div class="row g-3">
    @forelse ($solicitudes as $s)
      <div class="col-12">
        <div class="card border-0 shadow-sm h-100" 
             style="border-radius: 14px; transition: all 0.2s; overflow: hidden;"
             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)';"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
          <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
              {{-- Left Content --}}
              <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-3">
                  <div class="d-flex align-items-center justify-content-center rounded-3" 
                       style="width: 48px; height: 48px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                      <polyline points="14 2 14 8 20 8"/>
                    </svg>
                  </div>
                  <div>
                    <div class="small text-muted mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                      Expediente
                    </div>
                    <div class="fw-bold" style="font-size: 1.1rem; color: #1a202c;">
                      {{ $s->expediente }}
                    </div>
                  </div>
                </div>

                <div class="d-flex flex-column gap-2">
                  <div class="d-flex align-items-start gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" class="mt-1 flex-shrink-0">
                      <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                      <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    </svg>
                    <div>
                      <span class="text-muted small">Trámite:</span>
                      <span class="fw-semibold ms-1" style="color: #374151;">{{ $s->tramite->nombre ?? '—' }}</span>
                    </div>
                  </div>

                  <div class="d-flex align-items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" class="flex-shrink-0">
                      <circle cx="12" cy="12" r="10"/>
                      <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <div>
                      <span class="text-muted small">Estado:</span>
                      <span class="badge rounded-pill px-3 py-1 ms-2" 
                            style="background-color: #e0e7ff; color: #4338ca; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px;">
                        {{ $s->estado }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Right Action --}}
              <div class="d-flex align-items-center">
                <a class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm" 
                   href="{{ route('profile.solicitudes.show', $s->id) }}"
                   style="border-radius: 10px; font-weight: 500; white-space: nowrap; transition: all 0.2s;"
                   onmouseover="this.style.transform='translateX(4px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';"
                   onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
                  Ver Detalles
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="text-center py-5">
          <div class="mb-4">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" style="margin: 0 auto;">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="12" y1="18" x2="12" y2="12"/>
              <line x1="9" y1="15" x2="15" y2="15"/>
            </svg>
          </div>
          <h5 class="fw-semibold mb-2" style="color: #374151;">No hay solicitudes aún</h5>
          <p class="text-muted mb-4">Comienza creando tu primer trámite</p>
          <a href="{{ route('profile.catalogo') }}" 
             class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-2 shadow-sm"
             style="border-radius: 10px; font-weight: 500;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="12" y1="5" x2="12" y2="19"/>
              <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Crear Primer Trámite
          </a>
        </div>
      </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  @if($solicitudes->hasPages())
    <div class="mt-4 d-flex justify-content-center">
      {{ $solicitudes->links() }}
    </div>
  @endif
</div>

<style>
  .pagination {
    gap: 0.5rem;
  }
  
  .pagination .page-link {
    border-radius: 8px;
    border: none;
    padding: 0.5rem 1rem;
    font-weight: 500;
    transition: all 0.2s;
  }
  
  .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
  }
  
  .pagination .page-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
</style>
@endsection