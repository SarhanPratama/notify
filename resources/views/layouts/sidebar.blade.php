<ul class="navbar-nav sidebar sidebar accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center bg-maron" href="{{ route('admin.dashboard') }}">
        <div class="navbar-brand">
            <img src="{{ url('assets/img/logo/brand.png') }}" width="100">
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    @can('dashboard.view')
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt text-maron"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @endcan

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Operasional</div>

    <!-- Produk & Diskon -->
    {{-- @canany(['produk.view', 'discount.view'])
    <li class="nav-item">
        <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse" data-target="#collapseProduk" aria-expanded="true" aria-controls="collapseProduk">
            <i class="fa fa-cube text-maron"></i>
            <span>Produk & Diskon</span>
        </a>
        <div id="collapseProduk" class="collapse {{ in_array(request()->route()->getName(), ['produk.index', 'produk.create', 'produk.edit', 'produk.show', 'resep.index', 'discount.index']) ? 'show' : '' }}" aria-labelledby="headingProduk" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @can('produk.view')
                <a class="collapse-item {{ request()->routeIs('produk.index') ? 'active font-weight-bold' : '' }}" href="{{ route('produk.index') }}">List Produk</a>
                @endcan
                @can('discount.view')
                <a class="collapse-item {{ request()->routeIs('discount.index') ? 'active font-weight-bold' : '' }}" href="{{ route('discount.index') }}">Manajemen Diskon</a>
                @endcan
            </div>
        </div>
    </li>
    @endcanany --}}

    <!-- Transaksi -->
    @canany(['kas.view', 'pembelian.view', 'penjualan.view'])
    <li class="nav-item">
        <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse" data-target="#collapseKas" aria-expanded="true" aria-controls="collapseKas">
            <i class="fa fa-money text-maron"></i>
            <span>Transaksi</span>
        </a>
        <div id="collapseKas" class="collapse {{ in_array(request()->route()->getName(), ['kas.index', 'kas.create', 'pembelian.index', 'pembelian.create', 'penjualan.index', 'penjualan.create', 'penjualan.edit']) ? 'show' : '' }}" aria-labelledby="headingKas" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @can('kas.view')
                <a class="collapse-item {{ request()->routeIs('kas.index') ? 'active font-weight-bold' : '' }}" href="{{ route('kas.index') }}">Arus Kas</a>
                @endcan
                @can('pembelian.view')
                <a class="collapse-item {{ request()->routeIs(['pembelian.index', 'pembelian.create', 'pembelian.edit']) ? 'active font-weight-bold' : '' }}" href="{{ route('pembelian.index') }}">Pembelian</a>
                @endcan
                @can('penjualan.view')
                <a class="collapse-item {{ request()->routeIs(['penjualan.index', 'penjualan.create', 'penjualan.edit']) ? 'active font-weight-bold' : '' }}" href="{{ route('penjualan.index') }}">Penjualan</a>
                @endcan
            </div>
        </div>
    </li>
    @endcanany

    <!-- Inventory -->
    @canany(['supplier.view', 'bahan-baku.view'])
    <li class="nav-item">
        <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse" data-target="#collapseInventory" aria-expanded="true" aria-controls="collapseInventory">
            <i class="fa fa-truck text-maron"></i>
            <span>Inventory</span>
        </a>
        <div id="collapseInventory" class="collapse {{ in_array(request()->route()->getName(), ['supplier.index', 'bahan-baku.index']) ? 'show' : '' }}" aria-labelledby="headingInventory" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @can('supplier.view')
                <a class="collapse-item {{ request()->routeIs('supplier.index') ? 'active font-weight-bold' : '' }}" href="{{ route('supplier.index') }}">Supplier</a>
                @endcan
                @can('bahan-baku.view')
                <a class="collapse-item {{ request()->routeIs('bahan-baku.index') ? 'active font-weight-bold' : '' }}" href="{{ route('bahan-baku.index') }}">Bahan Baku</a>
                @endcan
            </div>
        </div>
    </li>
    @endcanany

    <!-- Laporan -->
    @can('laporan.view')
    <li class="nav-item">
        <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse" data-target="#collapselaporan" aria-expanded="true" aria-controls="collapselaporan">
            <i class="fa fa-file-text-o text-maron"></i>
            <span>Laporan</span>
        </a>
        <div id="collapselaporan" class="collapse {{ in_array(request()->route()->getName(), ['laporan-stok', 'laporan-pembelian', 'laporan-penjualan']) ? 'show' : '' }}" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('laporan-stok') ? 'active font-weight-bold' : '' }}" href="{{ route('laporan-stok') }}">Stok Bahan Baku</a>
                <a class="collapse-item {{ request()->routeIs('laporan-pembelian') ? 'active font-weight-bold' : '' }}" href="{{ route('laporan-pembelian') }}">Pembelian</a>
                <a class="collapse-item {{ request()->routeIs('laporan-penjualan') ? 'active font-weight-bold' : '' }}" href="{{ route('laporan-penjualan') }}">Penjualan</a>
            </div>
        </div>
    </li>
    @endcan

    <hr class="sidebar-divider">

    <!-- Konfigurasi -->
    @canany(['users.view', 'cabang.view', 'role.view', 'permission.view', 'akses-role.manage'])
    <div class="sidebar-heading">Pengaturan</div>

    <li class="nav-item">
        <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse" data-target="#collapseUser" aria-expanded="true" aria-controls="collapseUser">
            <i class="fa fa-cog text-maron"></i>
            <span>Pengguna & Akses</span>
        </a>
        <div id="collapseUser" class="collapse {{ in_array(request()->route()->getName(), ['users.index', 'users.create', 'users.show', 'users.edit', 'cabang.index', 'role.index', 'permission.index', 'akses-role.index']) ? 'show' : '' }}" aria-labelledby="headingUser" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @can('users.view')
                <a class="collapse-item {{ request()->routeIs('users.index', 'users.create', 'users.edit', 'users.show') ? 'active font-weight-bold' : '' }}" href="{{ route('users.index') }}">Karyawan</a>
                @endcan
                @can('cabang.view')
                <a class="collapse-item {{ request()->routeIs('cabang.index') ? 'active font-weight-bold' : '' }}" href="{{ route('cabang.index') }}">Cabang</a>
                @endcan
                @can('role.view')
                <a class="collapse-item {{ request()->routeIs('role.index') ? 'active font-weight-bold' : '' }}" href="{{ route('role.index') }}">Role</a>
                @endcan
                @can('permission.view')
                <a class="collapse-item {{ request()->routeIs('permission.index') ? 'active font-weight-bold' : '' }}" href="{{ route('permission.index') }}">Permission</a>
                @endcan
                @can('akses-role.manage')
                <a class="collapse-item {{ request()->routeIs('akses-role.index') ? 'active font-weight-bold' : '' }}" href="{{ route('akses-role.index') }}">Akses Role</a>
                @endcan
            </div>
        </div>
    </li>
    <hr class="sidebar-divider">
    @endcanany


    <div class="version" id="version-ruangadmin"></div>
</ul>
