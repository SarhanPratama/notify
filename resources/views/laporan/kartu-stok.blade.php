@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body bg-maron text-white rounded">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 font-weight-bold">
                                    <i class="fas fa-clipboard-list mr-2"></i>
                                    Laporan Kartu Stok Bahan Baku
                                </h4>
                                <p class="mb-0 text-white-60">Pantau pergerakan stok bahan baku secara detail</p>
                            </div>
                            <div>
                                <i class="fas fa-warehouse fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('laporan.kartu-stok') }}" method="GET">
                            <div class="row align-items-end">
                                <div class="col-md-9">
                                    <label class="font-weight-semibold mb-2">
                                        <i class="fas fa-box text-primary mr-2"></i>
                                        Pilih Bahan Baku
                                    </label>
                                    <select name="id_bahan_baku" id="id_bahan_baku" class="form-control form-control-lg"
                                        required>
                                        <option value="" disabled {{ !$selected_id ? 'selected' : '' }}>
                                            -- Pilih Bahan Baku --
                                        </option>
                                        @foreach ($bahan_baku_list as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $selected_id == $item->id ? 'selected' : '' }}>
                                                {{ ucwords($item->nama) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-outline-primary btn-lg btn-block">
                                        <i class="fas fa-search mr-2"></i>
                                        Tampilkan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if ($selected_item)
            <!-- Info Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm border-left-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Bahan Baku
                                    </div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                                        {{ ucwords($selected_item->nama) }}
                                    </div>
                                </div>
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm border-left-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Stok Awal
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($selected_item->stok_awal, 0, ',', '.') }}
                                    </div>
                                </div>
                                <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm border-left-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Stok Saat Ini
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($selected_item->viewStok->stok_akhir ?? $selected_item->stok_awal, 0, ',', '.') }}
                                    </div>
                                </div>
                                <i class="fas fa-boxes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm border-left-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Mutasi
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ number_format($riwayat_mutasi->count(), 0, ',', '.') }}
                                    </div>
                                </div>
                                <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 font-weight-bold text-dark">
                                    <i class="fas fa-table mr-2 text-primary"></i>
                                    Riwayat Pergerakan Stok
                                </h5>
                                <div>
                                    <button onclick="printReport()" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-print mr-1"></i> Cetak
                                    </button>
                                    {{-- <button onclick="exportExcel()" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-file-excel mr-1"></i> Excel
                                    </button> --}}
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0" id="dataTableHover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="13%">Tanggal</th>
                                            <th width="13%">No. Bukti</th>
                                            <th>Keterangan</th>
                                            <th width="10%" class="text-center">Masuk</th>
                                            <th width="10%" class="text-center">Keluar</th>
                                            <th width="12%" class="text-center bg-light">Saldo</th>
                                        </tr>
                                        @php
                                            $saldo_berjalan = $selected_item->stok_awal;
                                        @endphp
                                        <tr class="table-info font-weight-bold">
                                            <td class="text-center">-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>
                                                <i class="fas fa-flag-checkered mr-2"></i>
                                                SALDO AWAL
                                            </td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">
                                                {{ number_format($saldo_berjalan, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayat_mutasi as $mutasi)
                                            @php
                                                $masuk = 0;
                                                $keluar = 0;
                                                $keterangan = 'N/A';
                                                $no_bukti = 'N/A';

                                                if ($mutasi->jenis_transaksi == 'M') {
                                                    $masuk = $mutasi->quantity;
                                                    $keterangan = 'Pembelian Stok';
                                                } elseif ($mutasi->jenis_transaksi == 'K') {
                                                    $keluar = $mutasi->quantity;
                                                    $keterangan = 'Distribusi ke Outlet';
                                                }

                                                $saldo_berjalan = $saldo_berjalan + $masuk - $keluar;

                                                if ($mutasi->mutasiable) {
                                                    $no_bukti = $mutasi->mutasiable->nobukti ?? 'N/A';
                                                }
                                            @endphp

                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <small class="d-block font-weight-semibold">
                                                        {{ $mutasi->mutasiable->created_at ? $mutasi->mutasiable->created_at->format('d M Y') : '-' }}
                                                        {{ $mutasi->mutasiable->created_at ? $mutasi->mutasiable->created_at->format('H:i') : '-' }}
                                                    </small>
                                                    {{-- <small class="text-muted">
                                                    </small> --}}
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary">{{ $no_bukti }}</span>
                                                </td>
                                                <td>
                                                    @if ($masuk > 0)
                                                        <span class="badge badge-success badge-pill">
                                                            <i class="fas fa-arrow-down mr-1"></i>{{ $keterangan }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger badge-pill">
                                                            <i class="fas fa-arrow-up mr-1"></i>{{ $keterangan }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($masuk > 0)
                                                        <span class="text-success font-weight-bold">
                                                            +{{ number_format($masuk, 0, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($keluar > 0)
                                                        <span class="text-danger font-weight-bold">
                                                            -{{ number_format($keluar, 0, ',', '.') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center bg-light font-weight-bold">
                                                    {{ number_format($saldo_berjalan, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        <!-- Row Saldo Akhir -->
                                    </tbody>
                                    @if ($riwayat_mutasi->count() > 0)
                                        <tfoot>
                                            <tr class="table-info font-weight-bold">
                                                <td class="text-center">-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>
                                                    <i class="fas fa-flag mr-2"></i>
                                                    SALDO AKHIR
                                                </td>
                                                <td class="text-center text-success">
                                                    +{{ number_format($riwayat_mutasi->where('jenis_transaksi', 'M')->sum('quantity'), 0, ',', '.') }}
                                                </td>
                                                <td class="text-center text-danger">
                                                    -{{ number_format($riwayat_mutasi->where('jenis_transaksi', 'K')->sum('quantity'), 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($saldo_berjalan, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>

                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Laporan dihasilkan pada: {{ now()->format('d F Y, H:i') }} WIB
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-search fa-4x text-muted mb-4"></i>
                            <h5 class="text-muted">Pilih Bahan Baku</h5>
                            <p class="text-muted">
                                Silakan pilih bahan baku pada filter di atas untuk menampilkan laporan kartu stok
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#id_bahan_baku').select2({
                placeholder: "-- Pilih Bahan Baku --",
                allowClear: true,
                width: '100%'
            });

            // Initialize DataTables
            @if ($selected_item && $riwayat_mutasi->count() > 0)
                var table = $('#dataTableHover').DataTable({
                    responsive: true,
                    dom: '<"row"<"col-md-6"B><"col-md-6"f>>rt<"row"<"col-md-6"l><"col-md-6"p>>',
                    buttons: [{
                            extend: 'print',
                            text: '<i class="fas fa-print mr-1"></i> Cetak',
                            className: 'btn btn-sm btn-outline-primary',
                            title: 'Laporan Kartu Stok - {{ $selected_item->nama ?? 'Bahan Baku' }}',
                            exportOptions: {
                                columns: ':visible'
                            },
                            customize: function(win) {
                                $(win.document.body).find('table').addClass('compact').css(
                                    'font-size', '10pt');
                                $(win.document.body).find('h1').css('text-align', 'center');
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fas fa-file-excel mr-1"></i> Excel',
                            className: 'btn btn-sm btn-outline-success',
                            title: 'Laporan Kartu Stok - {{ $selected_item->nama ?? 'Bahan Baku' }}',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }
                    ],
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
                    },
                    order: [], // No initial sorting
                    columnDefs: [{
                            orderable: false,
                            targets: [0, 3, 6]
                        }, // Disable sorting for No, Keterangan, and Saldo columns
                        {
                            className: "text-center",
                            targets: [0, 4, 5, 6]
                        } // Center align specific columns
                    ]
                });

                console.log('DataTable loaded with {{ $riwayat_mutasi->count() }} records');
            @endif
        });

        function printReport() {
            window.print();
        }
    </script>
@endpush
