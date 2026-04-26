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
    @can('dashboard')
        <li class="nav-item {{ request()->routeIs('dashboard.*') ? 'active text-maron' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt text-maron"></i>
                <span>Dashboard</span>
            </a>
        </li>
    @endcan

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Operasional</div>
    @hasrole('gudang|keuangan')

        <li class="nav-item">
            <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse" data-target="#collapseDataMaster"
                aria-expanded="true" aria-controls="collapseDataMaster">
                <i class="fas fa-fw fa-database text-maron"></i>
                <span>Data Master</span>
            </a>
            <div id="collapseDataMaster"
                class="collapse {{ request()->routeIs(['kategori.*', 'bahan-baku.*', 'kategori.*', 'kategori-keuangan.*', 'supplier.*', 'outlet.*']) ? 'show' : '' }}"
                aria-labelledby="headingKas" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @can('kategori')
                        <a class="collapse-item {{ request()->routeIs('kategori.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('kategori.index') }}">Kategori BB</a>
                    @endcan
                    @can('kategori-keuangan')
                        <a class="collapse-item {{ request()->routeIs('kategori-keuangan.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('kategori-keuangan.index') }}">Kategori Keuangan</a>
                    @endcan
                    @can('bahan-baku')
                        <a class="collapse-item {{ request()->routeIs('bahan-baku.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('bahan-baku.index') }}">Bahan Baku</a>
                    @endcan
                    @can('supplier')
                        <a class="collapse-item {{ request()->routeIs('supplier.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('supplier.index') }}">Supplier</a>
                    @endcan
                    @can('outlet')
                        <a class="collapse-item {{ request()->routeIs('outlet.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('outlet.index') }}">Outlet</a>
                    @endcan
                </div>
            </div>
        </li>
    @endhasrole

    <!-- Transaksi -->
    @hasrole('gudang')
        <li class="nav-item">
            <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse"
                data-target="#collapseTransaksiGudang" aria-expanded="true" aria-controls="collapseTransaksiGudang">
                <i class="fas fa-fw fa-warehouse text-maron"></i>
                <span>Inventory</span>
            </a>
            <div id="collapseTransaksiGudang"
                class="collapse {{ request()->routeIs(['pembelian.*', 'penjualan.*', 'admin.pesanan.*']) ? 'show' : '' }}"
                aria-labelledby="headingKas" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @can('pembelian')
                        <a class="collapse-item {{ request()->routeIs('pembelian.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('pembelian.index') }}">Pembelian Stok</a>
                    @endcan
                    @can('penjualan')
                        <a class="collapse-item {{ request()->routeIs('penjualan.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('penjualan.index') }}">Penjualan Stok</a>
                    @endcan
                    @can('pesanan')
                        <a class="collapse-item {{ request()->routeIs('admin.pesanan.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('admin.pesanan.index') }}">Pesanan Outlet</a>
                    @endcan
                </div>
            </div>
        </li>
    @endhasrole

    @hasrole('keuangan')
        <li class="nav-item">
            <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse" data-target="#collapseKeuangan"
                aria-expanded="true" aria-controls="collapseKeuangan">
                <i class="fas fa-fw fa-wallet text-maron"></i>
                <span>Keuangan</span>
            </a>
            <div id="collapseKeuangan"
                class="collapse {{ request()->routeIs(['piutang.*', 'transaksi.*', 'pemasukan.*', 'pengeluaran.*']) ? 'show' : '' }}">
                <div class="bg-white py-2 collapse-inner rounded">
                    @can('piutang')
                        <a class="collapse-item {{ request()->routeIs('piutang.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('piutang.index') }}">Piutang</a>
                    @endcan
                    @can('pemasukan')
                        <a class="collapse-item {{ request()->routeIs('pemasukan.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('pemasukan.index') }}">Pemasukan</a>
                    @endcan
                    @can('pengeluaran')
                        <a class="collapse-item {{ request()->routeIs('pengeluaran.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('pengeluaran.index') }}">Pengeluaran</a>
                    @endcan
                    @can('transaksi')
                        <a class="collapse-item {{ request()->routeIs('transaksi.*') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('transaksi.index') }}">Riwayat Transaksi</a>
                    @endcan
                </div>
            </div>
        </li>
    @endhasrole

    <!-- Laporan -->
    @canany(['laporan-stok', 'laporan-kartu-stok', 'laporan-rekap-transaksi'])
        <li class="nav-item">
            <a class="nav-link collapsed fw-bold" href="#" data-toggle="collapse" data-target="#collapselaporan"
                aria-expanded="true" aria-controls="#collapselaporan">
                <i class="fas fa-fw fa-file-alt text-maron"></i>
                <span>Laporan</span>
            </a>
            <div id="collapselaporan"
                class="collapse {{ request()->routeIs(['laporan-stok', 'laporan-pembelian', 'laporan-penjualan', 'laporan.kartu-stok', 'laporan.rekap-transaksi', 'laporan.buku-besar', 'laporan.saldo-kas', 'laporan.piutang']) ? 'show' : '' }}"
                aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @can('laporan-stok')
                        <a class="collapse-item {{ request()->routeIs('laporan-stok') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('laporan-stok') }}">Stok</a>
                    @endcan
                    @can('laporan-kartu-stok')
                        <a class="collapse-item {{ request()->routeIs('laporan.kartu-stok') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('laporan.kartu-stok') }}">Kartu Stok</a>
                    @endcan
                    @can('laporan-rekap-transaksi')
                        <a class="collapse-item {{ request()->routeIs('laporan.rekap-transaksi') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('laporan.rekap-transaksi') }}">Rekap Transaksi</a>
                    @endcan
                    <a class="collapse-item {{ request()->routeIs('laporan.piutang') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('laporan.piutang') }}">Piutang Outlet</a>
                    {{-- <a class="collapse-item {{ request()->routeIs('laporan.buku-besar') ? 'active font-weight-bold' : '' }}"
                        href="{{ route('laporan.buku-besar') }}">Buku Besar</a> --}}
                </div>
            </div>
        </li>
    @endcanany

    <hr class="sidebar-divider">

    <!-- Konfigurasi -->
    @canany(['users', 'akses-role'])
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
                    @can('users')
                        <a class="collapse-item {{ request()->routeIs('users.index', 'users.create', 'users.edit', 'users.show') ? 'active font-weight-bold' : '' }}"
                            href="{{ route('users.index') }}">Users</a>
                    @endcan
                    @can('akses-role')
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
