<ul class="navbar-nav sidebar sidebar accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center bg-maron" href="{{ route('admin.dashboard') }}">
        <div class="navbar-brand">
            <img src="{{ url('assets/img/logo/brand.png')}}" width="100">
        </div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt text-maron"></i>
            <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Features
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed fw-bold"
         href="#" data-toggle="collapse" data-target="#collapseProduk"
            aria-controls="collapseProduk">
            <i class="fa fa-cube text-maron" aria-hidden="true"></i>
            <span>Produk</span>
        </a>
        <div id="collapseProduk" class="collapse
        {{ in_array(request()->route()->getName(),
        ['produk.index', 'produk.create', 'produk.edit', 'produk.show',
        'resep.index', 'discount.index'
        ]) ? 'show' : '' }}" aria-labelledby="headingProduk" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ in_array(request()->route()->getName(), ['produk.index', 'produk.create', 'produk.edit', 'produk.show']) ? 'active font-weight-bold' : '' }}"
                    href="{{ route('produk.index') }}">List Produk</a>
                {{-- <a class="collapse-item {{ request()->routeIs('bahan-baku.index') ? 'active font-weight-bold' : '' }}"
                    href="{{ route('bahan-baku.index') }}">Bahan Baku</a> --}}
                {{-- <a class="collapse-item {{ in_array(request()->route()->getName(), ['resep.index']) ? 'active font-weight-bold' : '' }}" href="{{ route('resep.index') }}">Resep</a> --}}
                <a class="collapse-item {{ in_array(request()->route()->getName(), ['discount.index']) ? 'active font-weight-bold' : '' }}" href="{{ route('discount.index') }}">Diskon</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed fw-bold"
         href="#" data-toggle="collapse" data-target="#collapseInventory"
            aria-controls="collapseInventory">
            <i class="fa fa-truck text-maron" aria-hidden="true"></i>
            <span>Inventory</span>
        </a>
        <div id="collapseInventory" class="collapse
        {{ in_array(request()->route()->getName(),
        ['supplier.index', 'pembelian.index', 'bahan-baku.index', 'penjualan.index'
        ]) ? 'show' : '' }}" aria-labelledby="headingInventory" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ in_array(request()->route()->getName(), ['supplier.index']) ? 'active font-weight-bold' : '' }}"
                    href="{{ route('supplier.index') }}">Supplier</a>
                <a class="collapse-item {{ in_array(request()->route()->getName(), ['bahan-baku.index']) ? 'active font-weight-bold' : '' }}"
                        href="{{ route('bahan-baku.index') }}">Bahan Baku</a>
                {{-- <a class="collapse-item {{ in_array(request()->route()->getName(), ['stok.index']) ? 'active font-weight-bold' : '' }}"
                    href="{{ route('stok.index') }}">Stok</a> --}}
                <a class="collapse-item {{ in_array(request()->route()->getName(), ['pembelian.index']) ? 'active font-weight-bold' : '' }}"
                    href="{{ route('pembelian.index') }}">Pembelian</a>
                 <a class="collapse-item {{ in_array(request()->route()->getName(), ['penjualan.index']) ? 'active font-weight-bold' : '' }}"
                        href="{{ route('penjualan.index') }}">Penjualan</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed fw-bold"
         href="#" data-toggle="collapse" data-target="#collapselaporan"
            aria-controls="collapselaporan">
            <i class="fa fa-cube text-maron" aria-hidden="true"></i>
            <span>Laporan</span>
        </a>
        <div id="collapselaporan" class="collapse
        {{ in_array(request()->route()->getName(),
        ['laporan-stok']) ? 'show' : '' }}" aria-labelledby="headingProduk" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ in_array(request()->route()->getName(), ['laporan-stok',]) ? 'active font-weight-bold' : '' }}"
                    href="{{ route('laporan-stok') }}">Laporan Bahan Baku</a>
                {{-- <a class="collapse-item {{ request()->routeIs('bahan-baku.index') ? 'active font-weight-bold' : '' }}"
                    href="{{ route('bahan-baku.index') }}">Bahan Baku</a> --}}
                {{-- <a class="collapse-item {{ in_array(request()->route()->getName(), ['resep.index']) ? 'active font-weight-bold' : '' }}" href="{{ route('resep.index') }}">Resep</a> --}}
                {{-- <a class="collapse-item {{ in_array(request()->route()->getName(), ['discount.index']) ? 'active font-weight-bold' : '' }}" href="{{ route('discount.index') }}">Diskon</a> --}}
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Konfigurasi
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed fw-bold
         "
            href="#" data-toggle="collapse" data-target="#collapseUser"
            aria-controls="collapseUser">
            <i class="fa fa-cog text-maron" aria-hidden="true"></i>
            <span>Konfigurasi</span>
        </a>
        <div id="collapseUser"
            class="collapse
             {{ in_array(request()->route()->getName(),
        ['users.index', 'users.create', 'users.edit', 'users.show',
        'cabang.index',
        'role.index',
        'permission.index',
        'akses-role.index'
        ]) ? 'show' : '' }}

            aria-labelledby="headingKaryawan" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ in_array(request()->route()->getName(), ['users.index', 'users.create', 'users.edit', 'users.show']) ? 'active font-weight-bold' : '' }}"
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
    <hr class="sidebar-divider">
    <div class="version" id="version-ruangadmin"></div>
</ul>
