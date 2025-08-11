@php
    if (!function_exists('isActive')) {
        function isActive($section, $active) {
            return $section === $active ? 'fw-bold text-dark' : 'text-secondary';
        }
    }

    if (!function_exists('isOpen')) {
        function isOpen($section, $active) {
            return $section === $active ? 'show' : '';
        }
    }
@endphp

<div class="px-3">
    <div class="d-flex align-items-center mb-4">
        <img src="https://ui-avatars.com/api/?name=Alicia+Aranda" class="rounded-circle me-2" width="40" height="40" alt="Perfil">
        <div>
            <strong>Alicia Aranda</strong><br>
            <small class="text-muted">Nivel 1<br>Funcionario</small>
        </div>
    </div>

    <div class="accordion mb-3" id="accordionMenu">

        <!-- VENTANILLA DIGITAL -->
        <div class="accordion-item border-0">
            <h6 class="accordion-header">
                <button class="accordion-button collapsed px-0 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVentanillaDigital">
                    <i class="bi bi-hdd-stack me-2"></i> Ventanilla Digital
                </button>
            </h6>
            <div id="collapseVentanillaDigital" class="accordion-collapse collapse {{ isOpen('ventanillaDigital', $active) }}">
                <ul class="nav flex-column ps-3">
                    <li class="nav-item">
                        <a href="{{ route('funcionario.tramite_config') }}" class="nav-link {{ isActive('tramites', $active) }}">
                            <i class="bi bi-list-columns-reverse me-2"></i> Listado de trámites
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('funcionario.bandeja') }}" class="nav-link {{ isActive('bandeja', $active) }}">
                            <i class="bi bi-inbox me-2"></i> Bandeja de entrada
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- RESTO DEL MENÚ -->
        <div class="accordion-item border-0">
            <h6 class="accordion-header">
                <button class="accordion-button collapsed px-0 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInspectores">
                    <i class="bi bi-person-badge me-2"></i> Inspectores
                </button>
            </h6>
            <div id="collapseInspectores" class="accordion-collapse collapse {{ isOpen('inspectores', $active) }}">
                <ul class="nav flex-column ps-3">
                    <li class="nav-item"><a href="{{ route('inspectores.index') }}" class="nav-link">Gestión de Inspectores</a></li>
                </ul>
            </div>
        </div>

        <div class="accordion-item border-0">
            <h6 class="accordion-header">
                <button class="accordion-button collapsed px-0 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePagos">
                    <i class="bi bi-credit-card me-2"></i> Pagos
                </button>
            </h6>
            <div id="collapsePagos" class="accordion-collapse collapse {{ isOpen('pagos', $active) }}">
                <ul class="nav flex-column ps-3">
                    <li class="nav-item"><a href="{{ route('pagos.index') }}" class="nav-link">Administración de pagos</a></li>
                </ul>
            </div>
        </div>

        <div class="accordion-item border-0">
            <h6 class="accordion-header">
                <button class="accordion-button collapsed px-0 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCitas">
                    <i class="bi bi-calendar-event me-2"></i> Citas
                </button>
            </h6>
            <div id="collapseCitas" class="accordion-collapse collapse {{ isOpen('citas', $active) }}">
                <ul class="nav flex-column ps-3">
                    <li class="nav-item"><a href="{{ route('citas.index') }}" class="nav-link">Gestión de citas</a></li>
                </ul>
            </div>
        </div>

        <div class="accordion-item border-0">
    <h6 class="accordion-header">
        <button class="accordion-button collapsed px-0 py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsuarios">
            <i class="bi bi-people me-2"></i> Usuarios
        </button>
    </h6>
    <div id="collapseUsuarios" class="accordion-collapse collapse {{ isOpen('usuarios', $active) }}">
        <ul class="nav flex-column ps-3">
            <li class="nav-item">
                <a href="{{ route('usuarios.ciudadanos') }}" class="nav-link {{ isActive('ciudadanos', $active) }}">
                    <i class="bi bi-person me-2"></i> Ciudadanos
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('usuarios.permisos') }}" class="nav-link {{ isActive('permisos', $active) }}">
                    <i class="bi bi-shield-lock me-2"></i> Permisos
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('usuarios.config') }}" class="nav-link {{ isActive('configUsuarios', $active) }}">
                    <i class="bi bi-gear me-2"></i> Configuración
                </a>
            </li>
        </ul>
    </div>
</div>


    </div>

    <!-- Otros enlaces sueltos -->
    <ul class="nav flex-column">
    <li class="nav-item">
        <a href="{{ route('catalogos.index') }}" class="nav-link d-flex align-items-center {{ isActive('catalogos', $active) }}">
            <i class="bi bi-folder2-open me-2"></i> Catálogos
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('filtros.index') }}" class="nav-link d-flex align-items-center {{ isActive('filtros', $active) }}">
            <i class="bi bi-funnel me-2"></i> Filtros
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link d-flex align-items-center {{ isActive('configuracion', $active) }}">
            <i class="bi bi-gear me-2"></i> Configuración
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('estadisticas') }}" class="nav-link d-flex align-items-center {{ isActive('estadisticas', $active) }}">
            <i class="bi bi-graph-up me-2"></i> Estadísticas
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('registro.cambios') }}" class="nav-link d-flex align-items-center {{ isActive('registro', $active) }}">
            <i class="bi bi-clock-history me-2"></i> Registro de cambios
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link d-flex align-items-center">
            <i class="bi bi-building me-2"></i> Manuales y guías
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('logout') }}" class="nav-link text-danger d-flex align-items-center" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>
</ul>

</div>
