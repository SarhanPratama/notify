@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        {{-- <!-- Header dengan Actions -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">📖 Laporan Buku Besar</h1>
            <div class="d-flex gap-2">
                <button class="btn btn-success btn-sm" onclick="exportToExcel()">
                    <i class="fas fa-download"></i> Export Excel
                </button>
                <button class="btn btn-danger btn-sm" onclick="exportToPDF()">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
                <button class="btn btn-info btn-sm" onclick="printReport()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div> --}}

        <!-- Filter Card -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-maron">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-search text-white mr-2"></i>
                            <h6 class="font-weight-bold text-white mb-0">Filter Buku Besar</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('laporan.buku-besar') }}" method="GET" class="row align-items-end">
                            <div class="col-md-2">
                                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                                <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal"
                                    value="{{ $tanggal_awal }}">
                            </div>
                            <div class="col-md-2">
                                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir"
                                    value="{{ $tanggal_akhir }}">
                            </div>
                            <div class="col-md-2">
                                <label for="tipe_transaksi" class="form-label">Tipe Transaksi</label>
                                <select class="form-control" id="tipe_transaksi" name="tipe_transaksi">
                                    <option value="all" {{ $tipe_transaksi == 'all' ? 'selected' : '' }}>Semua Tipe
                                    </option>
                                    <option value="debit" {{ $tipe_transaksi == 'debit' ? 'selected' : '' }}>Debit (+)
                                    </option>
                                    <option value="kredit" {{ $tipe_transaksi == 'kredit' ? 'selected' : '' }}>Kredit (-)
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="search" class="form-label">Cari Deskripsi</label>
                                <input type="text" class="form-control" id="search" name="search"
                                    value="{{ $search }}" placeholder="Kata kunci...">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </form>
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilter()">
                                <i class="fas fa-refresh"></i> Reset
                            </button>
                            <small class="text-muted ml-3">
                                Menampilkan {{ $jumlahTransaksi }} transaksi dari
                                {{ \Carbon\Carbon::parse($tanggal_awal)->format('d M Y') }} -
                                {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <!-- Total Debit -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Debit
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalDebit, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">Pemasukan & Penambahan</small>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-plus-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Kredit -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total Kredit
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalKredit, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">Pengeluaran & Pengurangan</small>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-minus-circle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Saldo Akhir -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-{{ $saldoAkhir >= 0 ? 'primary' : 'warning' }} shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div
                                    class="text-xs font-weight-bold text-{{ $saldoAkhir >= 0 ? 'primary' : 'warning' }} text-uppercase mb-1">
                                    Saldo Akhir
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">{{ $saldoAkhir >= 0 ? 'Surplus' : 'Defisit' }}</small>
                            </div>
                            <div class="col-auto">
                                <i
                                    class="fas fa-balance-scale fa-2x text-{{ $saldoAkhir >= 0 ? 'primary' : 'warning' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jumlah Transaksi -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Jumlah Transaksi
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($jumlahTransaksi, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">Total Aktivitas</small>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-list-ol fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buku Besar Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="font-weight-bold text-primary m-0">Buku Besar Transaksi</h6>
                            <span class="text-xs text-muted">Periode: {{ $tanggal_awal }} s/d {{ $tanggal_akhir }}</span>
                        </div>
                        <div>
                            <a class="btn btn-outline-success btn-sm" href="{{ route('laporan.buku-besar.export', request()->query()) }}"
                                target="_blank">
                               Export Excel
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover bukuBesarTable" id="dataTableHover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th width="12%" class="text-right">Debit (+)</th>
                                    <th width="12%" class="text-right">Kredit (-)</th>
                                    <th width="13%" class="text-right">Saldo</th>
                                    <th width="8%" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksi as $trx)
                                    <tr class="{{ $trx->tipe == 'debit' ? 'table-success' : 'table-danger' }}">
                                        <td class="text-center">
                                            {{ $loop->iteration + ($transaksi->currentPage() - 1) * $transaksi->perPage() }}
                                        </td>
                                        <td>
                                            <strong>{{ $trx->tanggal->format('d/m/Y') }}</strong><br>
                                            <small class="text-muted">{{ $trx->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $trx->deskripsi }}</strong>
                                            @if ($trx->referenceable_type)
                                                <br><small class="text-muted">Ref:
                                                    {{ class_basename($trx->referenceable_type) }}</small>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($trx->tipe == 'debit')
                                                <strong class="text-success">Rp
                                                    {{ number_format($trx->jumlah, 0, ',', '.') }}</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($trx->tipe == 'kredit')
                                                <strong class="text-danger">Rp
                                                    {{ number_format($trx->jumlah, 0, ',', '.') }}</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <strong
                                                class="{{ $trx->running_balance >= 0 ? 'text-primary' : 'text-warning' }}">
                                                Rp {{ number_format($trx->running_balance ?? 0, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $trx->status == 1 ? 'success' : 'secondary' }}">
                                                {{ $trx->status == 1 ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-inbox text-muted fa-3x mb-3"></i><br>
                                            <h6 class="text-muted">Tidak ada transaksi ditemukan</h6>
                                            <p class="text-muted mb-0">Silakan ubah filter atau periode tanggal</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if (count($transaksi) > 0)
                                <tfoot class="thead-light">
                                    <tr>
                                        <th colspan="3" class="text-right">TOTAL PERIODE:</th>
                                        <th class="text-right">Rp {{ number_format($totalDebit, 0, ',', '.') }}</th>
                                        <th class="text-right">Rp {{ number_format($totalKredit, 0, ',', '.') }}</th>
                                        <th class="text-right">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</th>
                                        <th class="text-center">{{ $jumlahTransaksi }}</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($transaksi->hasPages())
                        <div class="card-footer">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        Menampilkan {{ $transaksi->firstItem() }} - {{ $transaksi->lastItem() }}
                                        dari {{ $transaksi->total() }} transaksi
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    {{ $transaksi->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTables (disable pagination since we use server-side)
            $('#bukuBesarTable').DataTable({
                "paging": false,
                "ordering": false,
                "searching": false,
                "info": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                }
            });
        });

        // Reset Filter
        function resetFilter() {
            document.getElementById('tanggal_awal').value = '{{ now()->startOfMonth()->toDateString() }}';
            document.getElementById('tanggal_akhir').value = '{{ now()->endOfMonth()->toDateString() }}';
            document.getElementById('sumber_dana').value = 'all';
            document.getElementById('tipe_transaksi').value = 'all';
            document.getElementById('search').value = '';
        }
    </script>
@endpush
