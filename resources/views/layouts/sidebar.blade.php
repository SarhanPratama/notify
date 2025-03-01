<ul class="navbar-nav sidebar sidebar accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            {{-- <img src="{{ url('assets/img/logo/brand.jpg')}}" width="100%"> --}}
        </div>
        <div class="sidebar-brand-text mx-3 text-dark">RuangAdmin</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Features
    </div>
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap"
            aria-expanded="true" aria-controls="collapseBootstrap">
            <i class="far fa-fw fa-window-maximize"></i>
            <span>Bootstrap UI</span>
        </a>
        <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Bootstrap UI</h6>
                <a class="collapse-item" href="alerts.html">Alerts</a>
                <a class="collapse-item" href="buttons.html">Buttons</a>
                <a class="collapse-item" href="dropdowns.html">Dropdowns</a>
                <a class="collapse-item" href="modals.html">Modals</a>
                <a class="collapse-item" href="popovers.html">Popovers</a>
                <a class="collapse-item" href="progress-bar.html">Progress Bars</a>
            </div>
        </div>
    </li> --}}
    <li class="nav-item">
        <a class="nav-link collapsed
        {{ request()->routeIs('produk.index') || request()->routeIs('cabang.index') ? '' : 'collapsed' }}"
         href="#" data-toggle="collapse" data-target="#collapseProduk"
            aria-expanded="{{ request()->routeIs('produk.index') ? 'true' : 'false' }}"
            aria-controls="collapseProduk">
            <i class="fab fa-fw fa-wpforms"></i>
            <span>Produk</span>
        </a>
        <div id="collapseProduk" class="collapse
        {{ request()->routeIs('produk.index')
            ? 'show'
            : '' }}" aria-labelledby="headingProduk" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- <h6 class="collapse-header">Produk</h6> --}}
                <a class="collapse-item {{ request()->routeIs('produk.index') ? 'active font-weight-bold' : '' }}"
                    href="{{ route('produk.index') }}">List Produk</a>
                <a class="collapse-item" href="form_advanceds.html">Form Advanceds</a>
            </div>
        </div>
    </li>
    {{-- <li class="nav-item">
        <a class="nav-link collapsed"
            href="#" data-toggle="collapse" data-target="#collapseForm" aria-expanded="true"
            aria-controls="collapseForm">
            <i class="fab fa-fw fa-wpforms"></i>
            <span>Forms</span>
        </a>
        <div id="collapseForm" class="collapse" aria-labelledby="headingForm" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Forms</h6>
                <a class="collapse-item" href="form_basics.html">Form Basics</a>
                <a class="collapse-item" href="form_advanceds.html">Form Advanceds</a>
            </div>
        </div>
    </li> --}}
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable"
            aria-expanded="true" aria-controls="collapseTable">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Tables</h6>
                <a class="collapse-item" href="simple-tables.html">Simple Tables</a>
                <a class="collapse-item" href="datatables.html">DataTables</a>
            </div>
        </div>
    </li> --}}
    {{-- <li class="nav-item">
        <a class="nav-link" href="ui-colors.html">
            <i class="fas fa-fw fa-palette"></i>
            <span>UI Colors</span>
        </a>
    </li> --}}
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Konfigurasi
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed  {{ request()->routeIs('users.index') || request()->routeIs('cabang.index') ? '' : 'collapsed' }}"
            href="#" data-toggle="collapse" data-target="#collapseUser"
            aria-expanded="{{ request()->routeIs('users.index') || request()->routeIs('cabang.index') ? 'true' : 'false' }}"
            aria-controls="collapseUser">
            <i class="fa fa-cog" aria-hidden="true"></i>
            <span>Konfigurasi</span>
        </a>
        <div id="collapseUser"
            class="collapse
        {{ request()->routeIs('users.index') ||
        request()->routeIs('cabang.index') ||
        request()->routeIs('role.index') ||
        request()->routeIs('permission.index') ||
        request()->routeIs('akses-role.index')
            ? 'show'
            : '' }}

        "
            aria-labelledby="headingKaryawan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- <h6 class="collapse-header">Forms</h6> --}}
                <a class="collapse-item {{ request()->routeIs('users.index') ? 'active font-weight-bold' : '' }}"
                    href="{{ route('users.index') }}">Karyawan</a>
                <a class="collapse-item {{ request()->routeIs('cabang.index') ? 'active font-weight-bold' : '' }}"
                    href="{{ route('cabang.index') }}">Cabang</a>
                <a class="collapse-item {{ request()->routeIs('role.index') ? 'active font-weight-bold' : '' }}"
                    href="{{ route('role.index') }}">Role</a>
                <a class="collapse-item {{ request()->routeIs('permission.index') ? 'active font-weight-bold' : '' }}"
                    href="{{ route('permission.index') }}">Permission</a>
                <a class="collapse-item {{ request()->routeIs('akses-role.index') ? 'active font-weight-bold' : '' }}"
                    href="{{ route('akses-role.index') }}">Akses Role</a>
            </div>
        </div>
    </li>
    {{-- <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePage" aria-expanded="true"
        aria-controls="collapsePage">
        <i class="fas fa-fw fa-columns"></i>
        <span>Pages</span>
      </a>
      <div id="collapsePage" class="collapse" aria-labelledby="headingPage" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Example Pages</h6>
          <a class="collapse-item" href="login.html">Login</a>
          <a class="collapse-item" href="register.html">Register</a>
          <a class="collapse-item" href="404.html">404 Page</a>
          <a class="collapse-item" href="blank.html">Blank Page</a>
        </div>
      </div>
    </li> --}}
    {{-- <li class="nav-item">
      <a class="nav-link" href="charts.html">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Charts</span>
      </a>
    </li> --}}
    <hr class="sidebar-divider">

</ul>
