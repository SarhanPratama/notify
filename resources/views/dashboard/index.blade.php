@extends('layouts.master')
@section('content')
    @include('layouts.breadcrumbs')

    {{-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Admin Gudang</h1>
    </div> --}}

    <!-- Statistik Cards -->
    @hasanyrole('admin gudang|owner')
        <div class="row">
            <!-- Total Bahan Baku -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Bahan Baku</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['total_bahan_baku'] }}</div>
                                {{-- <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-{{ $persentasePembelian >= 0 ? 'success' : 'danger' }} mr-2">
                                    <i class="fas fa-arrow-{{ $persentasePembelian >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($persentasePembelian) }}%
                                </span>
                                <span>Since last month</span>
                            </div> --}}
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bahan Kritis -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Bahan Kritis</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['bahan_kritis'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Bahan Kritis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['bahan_kritis'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

            <!-- Pembelian Bulan Ini -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Pembelian Bahan Baku Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp. {{ number_format($stats['pembelian_bulan_ini'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distribusi Hari Ini -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Penjualan Bahan Baku Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp.
                                    {{ number_format($stats['penjualan_bulan_ini'], 0, ',', '.') }}</div>
                            </div>

                            <div class="col-auto">
                                <i class="fas fa-truck fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Distribusi Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['distribusi_hari_ini'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        </div>

        <!-- Grafik dan Tabel -->
        <div class="row">
            <!-- Grafik Stok -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Trend Stok 30 Hari Terakhir</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area" style="height: 400px;">
                            <canvas id="stockChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supplier Teraktif -->
            {{-- <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Supplier Teraktif</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 15rem;"
                            src="{{ asset('svg/undraw_processing.svg') }}" alt="Supplier Illustration">
                    </div>
                    <p class="text-center">
                        <strong>{{ $stats['supplier_aktif'] }}</strong> supplier aktif bulan ini
                    </p>
                    <a href="{{ route('supplier.index') }}">Lihat daftar supplier &rarr;</a>
                </div>
            </div>
        </div> --}}
        </div>

        <!-- Bahan Baku Kritis -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-exclamation-circle"></i> Bahan Baku Kritis
                </h6>
            </div>
            <div class="card-body">
                @if ($bahanBakuKritis->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama Bahan</th>
                                    <th>Stok Tersedia</th>
                                    <th>Stok Minimal</th>
                                    {{-- <th>Supplier</th> --}}
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bahanBakuKritis as $bahan)
                                    <tr>
                                        <td>{{ $bahan->nama }}</td>
                                        <td
                                            class="{{ $bahan->stok_akhir < $bahan->stok_minimum ? 'text-danger font-weight-bold' : '' }}">
                                            {{ $bahan->stok_akhir }} {{ $bahan->satuan->nama ?? '' }}
                                        </td>
                                        <td>{{ $bahan->stok_minimum }}</td>
                                        {{-- <td>{{ $bahan->supplier->nama ?? '-' }}</td> --}}
                                        <td>
                                            <a href="{{ route('pembelian.create', ['bahan_id' => $bahan->id]) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-cart-plus"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Tidak ada bahan baku kritis saat ini
                    </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Pembelian Terakhir -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history"></i> Riwayat Pembelian Terakhir
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No. Pembelian</th>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayatPembelian as $pembelian)
                                <tr>
                                    <td>{{ $pembelian->nobukti }}</td>
                                    <td>{{ $pembelian->tanggal }}</td>
                                    <td>{{ $pembelian->supplier->nama }}</td>
                                    <td>Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ url('pembelian.show', $pembelian->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @section('scripts')
        <!-- Load Chart.js -->
        <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Pastikan elemen canvas ada
                var ctx = document.getElementById("stockChart");
                if (!ctx) {
                    console.error("Canvas element not found!");
                    return;
                }

                // Pastikan data chart valid
                var chartData = @json($chartData);
                if (!chartData || !chartData.labels || !chartData.datasets) {
                    console.error("Invalid chart data:", chartData);
                    return;
                }

                try {
                    var stockChart = new Chart(ctx, {
                        type: 'line',
                        data: chartData,
                        options: {
                            maintainAspectRatio: false,
                            responsive: true,
                            layout: {
                                padding: {
                                    left: 10,
                                    right: 25,
                                    top: 25,
                                    bottom: 0
                                }
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 7
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: false,
                                        padding: 10,
                                        callback: function(value) {
                                            return number_format(value);
                                        }
                                    },
                                    gridLines: {
                                        color: "rgb(234, 236, 244)",
                                        zeroLineColor: "rgb(234, 236, 244)",
                                        drawBorder: false,
                                        borderDash: [2],
                                        zeroLineBorderDash: [2]
                                    }
                                }]
                            },
                            tooltips: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyFontColor: "#858796",
                                titleMarginBottom: 10,
                                titleFontColor: '#6e707e',
                                titleFontSize: 14,
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                xPadding: 15,
                                yPadding: 15,
                                displayColors: false,
                                intersect: false,
                                mode: 'index',
                                caretPadding: 10,
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.yLabel.toLocaleString();
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error("Error creating chart:", error);
                }
            });

            function number_format(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        </script>
        @endsection
    @endhasanyrole
@endsection
