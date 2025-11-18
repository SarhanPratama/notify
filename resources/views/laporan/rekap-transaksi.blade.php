@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <!-- Header dengan Filter dan Actions -->
        {{-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">📊 Rekap Transaksi</h1>
            <div class="d-flex gap-2">
                <button class="btn btn-success btn-sm" onclick="exportToExcel()">
                    <i class="fas fa-download"></i> Export Excel
                </button>
                <button class="btn btn-danger btn-sm" onclick="exportToPDF()">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </div>
        </div> --}}

        <!-- Filter Card dengan styling yang lebih baik -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-maron">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-filter text-white mr-2"></i>
                            <h6 class="font-weight-bold text-white mb-0">Filter Laporan</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('laporan.rekap-transaksi') }}" method="GET" class="row align-items-end">
                            <div class="col-md-3">
                                <label for="tanggal_awal" class="form-label">📅 Tanggal Awal</label>
                                <input type="date" class="form-control" id="tanggal_awal"
                                    name="tanggal_awal" value="{{ $tanggal_awal }}">
                            </div>
                            <div class="col-md-3">
                                <label for="tanggal_akhir" class="form-label">📅 Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tanggal_akhir"
                                    name="tanggal_akhir" value="{{ $tanggal_akhir }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-search"></i> Tampilkan Laporan
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilter()">
                                    <i class="fas fa-refresh"></i> Reset Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        @php
            $totalPembelian = collect($rekap_pembelian)->sum('total_pembelian');
            $totalPenjualan = collect($rekap_penjualan)->sum('total_penjualan');
            $totalTransaksiPembelian = collect($rekap_pembelian)->sum('jumlah_transaksi');
            $totalTransaksiPenjualan = collect($rekap_penjualan)->sum('jumlah_transaksi');
            $netProfit = $totalPenjualan - $totalPembelian;
        @endphp

        <div class="row mb-4">
            <!-- Total Pembelian -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    💸 Total Pembelian
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalPembelian, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">{{ $totalTransaksiPembelian }} Transaksi</small>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Penjualan -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    💰 Total Penjualan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">{{ $totalTransaksiPenjualan }} Transaksi</small>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-cash-register fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Net Profit -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-{{ $netProfit >= 0 ? 'success' : 'danger' }} shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-{{ $netProfit >= 0 ? 'success' : 'danger' }} text-uppercase mb-1">
                                    📈 Net Profit
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($netProfit, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">
                                    {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}
                                    {{ $totalPenjualan > 0 ? number_format(($netProfit / $totalPenjualan) * 100, 1) : 0 }}%
                                </small>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-{{ $netProfit >= 0 ? 'chart-line' : 'chart-line-down' }} fa-2x text-{{ $netProfit >= 0 ? 'success' : 'danger' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Period Info -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    📅 Periode Laporan
                                </div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($tanggal_awal)->format('d M') }} -
                                    {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($tanggal_awal)->diffInDays(\Carbon\Carbon::parse($tanggal_akhir)) + 1 }} Hari
                                </small>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="font-weight-bold text-primary m-0">🛒 Rekap Pembelian per Supplier</h6>
                            <span class="text-xs text-muted">{{ $tanggal_awal }} s/d {{ $tanggal_akhir }}</span>
                        </div>
                        <div class="dropdown no-arrow">
                            <button class="btn btn-link btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <div class="dropdown-header">Export Options:</div>
                                {{-- <a class="dropdown-item" href="#" onclick="exportTableToCSV('tablePembelian', 'rekap_pembelian')">
                                    <i class="fas fa-file-csv fa-sm fa-fw mr-2 text-gray-400"></i> Export CSV
                                </a> --}}
                                <a class="dropdown-item" href="#" onclick="printTable('tablePembelian')">
                                    <i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i> Print
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover tablePembelian" id="dataTableHover">
                            <thead>
                                <tr>
                                    <th width="10%">No</th>
                                    <th>Nama Supplier</th>
                                    <th class="text-center" width="20%">Transaksi</th>
                                    <th class="text-right" width="25%">Total Pembelian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rekap_pembelian as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $item['nama_supplier'] }}</strong>

                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $item['jumlah_transaksi'] }}</span>
                                        </td>
                                        <td class="text-right">
                                            <strong class="text-danger">Rp {{ number_format($item['total_pembelian'], 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-inbox text-muted fa-2x mb-2"></i><br>
                                            <span class="text-muted">Tidak ada data pembelian pada periode ini.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($rekap_pembelian) > 0)
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">TOTAL:</th>
                                    <th class="text-center">{{ $totalTransaksiPembelian }}</th>
                                    <th class="text-right">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="font-weight-bold text-success m-0">🏪 Rekap Distribusi per Outlet</h6>
                            <span class="text-xs text-muted">{{ $tanggal_awal }} s/d {{ $tanggal_akhir }}</span>
                        </div>
                        <div class="dropdown no-arrow">
                            <button class="btn btn-link btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <div class="dropdown-header">Export Options:</div>
                                {{-- <a class="dropdown-item" href="#" onclick="exportTableToCSV('tablePenjualan', 'rekap_penjualan')">
                                    <i class="fas fa-file-csv fa-sm fa-fw mr-2 text-gray-400"></i> Export CSV
                                </a> --}}
                                <a class="dropdown-item" href="#" onclick="printTable('tablePenjualan')">
                                    <i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i> Print
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover tablePenjualan" id="dataTableHover2">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Outlet</th>
                                    <th class="text-center">Transaksi</th>
                                    <th class="text-right">Total Distribusi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rekap_penjualan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $item['nama_outlet'] }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $item['jumlah_transaksi'] }}</span>
                                        </td>
                                        <td class="text-right">
                                            <strong class="text-success">Rp {{ number_format($item['total_penjualan'], 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-inbox text-muted fa-2x mb-2"></i><br>
                                            <span class="text-muted">Tidak ada data distribusi pada periode ini.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(count($rekap_penjualan) > 0)
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">TOTAL:</th>
                                    <th class="text-center">{{ $totalTransaksiPenjualan }}</th>
                                    <th class="text-right">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>

// Reset Filter
function resetFilter() {
    document.getElementById('tanggal_awal').value = '{{ now()->startOfMonth()->toDateString() }}';
    document.getElementById('tanggal_akhir').value = '{{ now()->endOfMonth()->toDateString() }}';
}

function printTable(tableId) {
    const printContents = document.getElementsByClassName(tableId)[0].outerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = `
        <html>
        <head>
            <title>Rekap Transaksi</title>
            <style>
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .text-center { text-align: center !important; }
                .text-right { text-align: right !important; }
            </style>
        </head>
        <body>
            <h2>Rekap Transaksi - {{ $tanggal_awal }} s/d {{ $tanggal_akhir }}</h2>
            ${printContents}
        </body>
        </html>
    `;

    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
</script>
@endpush
