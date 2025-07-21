<aside class="sidebar v-navigation-drawer v-navigation-drawer--fixed v-navigation-drawer--open v-navigation-drawer--right theme--light" style="height: 100vh; width: 300px;">
  <div class="v-navigation-drawer__prepend">
    <div class="row no-gutters justify-space-between">
      <div class="col col-2">
        <div class="v-avatar" style="height: 48px; min-width: 48px; width: 48px;">
          <i class="mdi mdi-application-edit" style="color: #063F6F;"></i>
        </div>
      </div>
      <div class="col">
        <hr class="v-divider v-divider--vertical">
      </div>
      <div class="mr-2 col col-2">
        <button class="v-btn v-btn--has-bg v-btn--tile red darken-3 white--text" style="width: 100%; height: 100%;">
          <i class="mdi mdi-close white--text"></i>
        </button>
      </div>
    </div>
  </div>

  <div class="v-navigation-drawer__content">
    <hr class="v-divider">

    {{-- Panel de navegación funcional --}}
    <div class="v-expansion-panels v-expansion-panels--accordion">
      @foreach ([
        'Header' => 'mdi-page-layout-header',
        'Primera sección' => 'mdi-page-layout-body',
        'Segunda Sección' => 'mdi-page-layout-body',
        'Tercera Sección' => 'mdi-page-layout-body',
        'Cuarta Sección' => 'mdi-page-layout-body',
        'Quinta Sección' => 'mdi-page-layout-body',
        'Sexta Sección' => 'mdi-page-layout-body',
        'Footer' => 'mdi-page-layout-footer',
        'Comm users' => 'mdi-account-plus'
      ] as $label => $icon)
        <div class="v-expansion-panel">
          <button class="v-expansion-panel-header">
            <div class="font-weight-bold">
              <i class="mdi {{ $icon }} mr-2 grey--text text--darken-3"></i>
              {{ $label }}
            </div>
            <div class="v-expansion-panel-header__icon">
              <i class="mdi mdi-chevron-down"></i>
            </div>
          </button>
        </div>
      @endforeach
    </div>
  </div>
</aside>
