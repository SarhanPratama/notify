<ul class="navbar-nav sidebar sidebar accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center bg-maron"
        href="{{ route('admin.dashboard') }}">
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
    <li class="nav-item">
        <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse" data-target="#collapseDataMaster"
            aria-expanded="true" aria-controls="collapseDataMaster">
            <i class="fas fa-fw fa-database text-maron"></i>
            <span>Data Master</span>
        </a>
        <div id="collapseDataMaster"
            class="collapse {{ request()->routeIs(['kategori.*', 'bahan-baku.*', 'kategori.*', 'supplier.*', 'outlet.*']) ? 'show' : '' }}"
            {{-- class="collapse {{ in_array(request()->route()->getName(), ['transaksi.index', 'transaksi.create', 'piutang.index', 'pembelian.index', 'pembelian.create', 'penjualan.index', 'penjualan.create', 'penjualan.edit']) ? 'show' : '' }}" --}} aria-labelledby="headingKas" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('kategori.index') ? 'active font-weight-bold' : '' }}"
                    href="{{ route('kategori.index') }}">Kategori</a>
                @can('bahan-baku.view')
                    <a class="collapse-item {{ request()->routeIs('bahan-baku.index') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('bahan-baku.index') }}">Bahan Baku</a>
                @endcan
                @can('penjualan.view')
                    <a class="collapse-item {{ request()->routeIs('supplier.*') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('supplier.index') }}">Supplier</a>
                @endcan
                @can('penjualan.view')
                    <a class="collapse-item {{ request()->routeIs('outlet.*') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('outlet.index') }}">Outlet</a>
                @endcan
            </div>
        </div>
    </li>
    <!-- Transaksi -->
    @canany(['kas.view', 'pembelian.view', 'penjualan.view'])
        <li class="nav-item">
            <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse"
                data-target="#collapseTransaksiGudang" aria-expanded="true" aria-controls="collapseTransaksiGudang">
                <i class="fas fa-fw fa-warehouse text-maron"></i>
                <span>Transaksi Gudang</span>
            </a>
            <div id="collapseTransaksiGudang"
                class="collapse {{ request()->routeIs(['pembelian.*', 'penjualan.*', 'admin.pesanan.*']) ? 'show' : '' }}"
                aria-labelledby="headingKas" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    {{-- @can('kas.view')
                        <a class="collapse-item {{ request()->routeIs('transaksi.index') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('transaksi.index') }}">Arus Kas</a>
                    @endcan --}}
                    {{-- <a class="collapse-item {{ request()->routeIs('piutang.index') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('piutang.index') }}">Kasbon</a> --}}
                    @can('pembelian.view')
                        <a class="collapse-item {{ request()->routeIs('pembelian.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('pembelian.index') }}">Pembelian Stok</a>
                    @endcan
                    @can('penjualan.view')
                        <a class="collapse-item {{ request()->routeIs('penjualan.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('penjualan.index') }}">Penjualan Stok</a>
                    @endcan
                     <a class="collapse-item {{ request()->routeIs('admin.pesanan.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('admin.pesanan.index') }}">Pesanan Outlet</a>
                </div>
            </div>
        </li>
    @endcanany

    <li class="nav-item">
        <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse" data-target="#collapseKeuangan"
            aria-expanded="true" aria-controls="collapseKeuangan">
            <i class="fas fa-fw fa-wallet text-maron"></i>
            <span>Keuangan</span>
        </a>
        <div id="collapseKeuangan"
            class="collapse {{ request()->routeIs(['sumber-dana.*', 'piutang.*', 'transaksi.*']) ? 'show' : '' }}"
            {{-- class="collapse {{ in_array(request()->route()->getName(), ['transaksi.index', 'transaksi.create', 'piutang.index', 'pembelian.index', 'pembelian.create', 'penjualan.index', 'penjualan.create', 'penjualan.edit']) ? 'show' : '' }}" --}} aria-labelledby="headingKas" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                @can('bahan-baku.view')
                    <a class="collapse-item {{ request()->routeIs('sumber-dana.index') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('sumber-dana.index') }}">Sumber Dana</a>
                @endcan
                <a class="collapse-item {{ request()->routeIs('piutang.index') ? 'active font-weight-bold' : '' }}"
                    href="{{ route('piutang.index') }}">Piutang</a>
                @can('kas.view')
                    <a class="collapse-item {{ request()->routeIs('transaksi.*') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('transaksi.index') }}">Arus Kas</a>
                @endcan
            </div>
        </div>
    </li>

    <!-- Laporan -->
    @can('laporan.view')
        <li class="nav-item">
            <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse" data-target="#collapselaporan"
                aria-expanded="true" aria-controls="#collapselaporan">
                <i class="fas fa-fw fa-file-alt text-maron"></i>
                <span>Laporan</span>
            </a>
            <div id="collapselaporan"
                class="collapse {{ request()->routeIs(['laporan-stok', 'laporan-pembelian', 'laporan-penjualan', 'laporan.kartu-stok', 'laporan.rekap-transaksi', 'laporan.buku-besar', 'laporan.saldo-kas']) ? 'show' : '' }}"
                aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ request()->routeIs('laporan-stok') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('laporan-stok') }}">Stok</a>
                    <a class="collapse-item {{ request()->routeIs('laporan.kartu-stok') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('laporan.kartu-stok') }}">Kartu Stok</a>
                    <a class="collapse-item {{ request()->routeIs('laporan.rekap-transaksi') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('laporan.rekap-transaksi') }}">Rekap Transaksi</a>
                    <a class="collapse-item {{ request()->routeIs('laporan.buku-besar') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('laporan.buku-besar') }}">Buku Besar</a>
                    <a class="collapse-item {{ request()->routeIs('laporan.saldo-kas') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('laporan.saldo-kas') }}">Saldo Kas</a>
                </div>
            </div>
        </li>
    @endcan

    <hr class="sidebar-divider">

    <!-- Konfigurasi -->
    @canany(['users.view', 'outlet.view', 'role.view', 'permission.view', 'akses-role.manage'])
        <div class="sidebar-heading">Pengaturan</div>

        <li class="nav-item">
            <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse" data-target="#collapseUser"
                aria-expanded="true" aria-controls="collapseUser">
                <i class="fas fa-fw fa-users-cog text-maron"></i>
                <span>Pengguna & Akses</span>
            </a>
            <div id="collapseUser"
                class="collapse {{ in_array(request()->route()->getName(), ['users.index', 'users.create', 'users.show', 'users.edit', 'role.index', 'permission.index', 'akses-role.index']) ? 'show' : '' }}"
                aria-labelledby="headingUser" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @can('users.view')
                        <a class="collapse-item {{ request()->routeIs('users.index', 'users.create', 'users.edit', 'users.show') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('users.index') }}">Karyawan</a>
                    @endcan
                    @can('role.view')
                        <a class="collapse-item {{ request()->routeIs('role.index') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('role.index') }}">Role</a>
                    @endcan
                    @can('permission.view')
                        <a class="collapse-item {{ request()->routeIs('permission.index') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('permission.index') }}">Permission</a>
                    @endcan
                    @can('akses-role.manage')
                        <a class="collapse-item {{ request()->routeIs('akses-role.index') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('akses-role.index') }}">Akses Role</a>
                    @endcan
                </div>
            </div>
        </li>
        <hr class="sidebar-divider">
    @endcanany


    <div class="version" id="version-ruangadmin"></div>
</ul>
