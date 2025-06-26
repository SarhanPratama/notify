@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Owner</h1>

    <!-- KPI Utama -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pendapatan Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pertumbuhan Penjualan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pertumbuhanPenjualan, 2) }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Rata-rata Transaksi per Outlet</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{-- Rp {{ number_format($rataTransaksiPerOutlet->avg('rata_transaksi'), 0, ',', '.') }} --}}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Peta Outlet dan Performa -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Performa Outlet</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Outlet</th>
                                    <th>Total Transaksi</th>
                                    <th>Total Pendapatan</th>
                                    <th>Rata-rata per Transaksi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach($outletPerformance as $outlet)
                                @php
                                    $status = $outlet->total_pendapatan < ($outletPerformance->avg('total_pendapatan') * 0.7) ? 'danger' : 'success';
                                @endphp
                                <tr>
                                    <td>{{ $outlet->nama }}</td>
                                    <td>{{ $outlet->total_transaksi }}</td>
                                    <td>Rp {{ number_format($outlet->total_pendapatan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($outlet->total_transaksi ? $outlet->total_pendapatan / $outlet->total_transaksi : 0, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $status }}">
                                            {{ $status == 'danger' ? 'Perlu Perhatian' : 'Baik' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontribusi Produk -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Kontribusi Produk</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="produkPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        {{-- @foreach($kontribusiProduk as $produk)
                        <span class="mr-2">
                            <i class="fas fa-circle text-{{ ['primary', 'success', 'info', 'warning', 'danger'][$loop->index % 5] }}"></i> {{ $produk->nama }}
                        </span>
                        @endforeach --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Margin Produk -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Margin Produk</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="marginProdukChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Strategis -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-warning">
                    <h6 class="m-0 font-weight-bold text-white">Outlet Perlu Perhatian</h6>
                </div>
                <div class="card-body">
                    {{-- @if($outletUnderperforming->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-warning text-white">
                                    <th>Outlet</th>
                                    <th>Total Pendapatan</th>
                                    <th>% dari Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($outletUnderperforming as $outlet)
                                <tr>
                                    <td>{{ $outlet->nama }}</td>
                                    <td>Rp {{ number_format($outlet->total_pendapatan, 0, ',', '.') }}</td>
                                    <td>{{ number_format(($outlet->total_pendapatan / $outletPerformance->avg('total_pendapatan')) * 100, 2) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Semua outlet berkinerja baik!
                    </div>
                    @endif --}}
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-danger">
                    <h6 class="m-0 font-weight-bold text-white">Bahan Baku Naik Harga</h6>
                </div>
                <div class="card-body">
                    {{-- @if($bahanNaikHarga->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-danger text-white">
                                    <th>Bahan Baku</th>
                                    <th>Harga Terakhir</th>
                                    <th>Kenaikan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bahanNaikHarga as $bahan)
                                <tr>
                                    <td>{{ $bahan->nama }}</td>
                                    <td>Rp {{ number_format($bahan->harga_terakhir, 0, ',', '.') }}</td>
                                    <td class="text-danger">+ Rp {{ number_format($bahan->kenaikan, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Tidak ada bahan baku yang naik harga signifikan!
                    </div>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- <script>
    // Pie Chart Kontribusi Produk
    var ctx = document.getElementById('produkPieChart').getContext('2d');
    var produkPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($kontribusiProduk->pluck('nama')) !!},
            datasets: [{
                data: {!! json_encode($kontribusiProduk->pluck('total_penjualan')) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
            },
            cutout: '80%',
        },
    });

    // Bar Chart Margin Produk
    var ctx2 = document.getElementById('marginProdukChart').getContext('2d');
    var marginProdukChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: {!! json_encode($marginProduk->pluck('nama')) !!},
            datasets: [{
                label: 'Harga Modal',
                data: {!! json_encode($marginProduk->pluck('harga_modal')) !!},
                backgroundColor: '#e74a3b',
                hoverBackgroundColor: '#be2617',
            },
            {
                label: 'Harga Jual',
                data: {!! json_encode($marginProduk->pluck('harga_jual')) !!},
                backgroundColor: '#1cc88a',
                hoverBackgroundColor: '#17a673',
            },
            {
                label: 'Margin',
                data: {!! json_encode($marginProduk->pluck('margin')) !!},
                backgroundColor: '#4e73df',
                hoverBackgroundColor: '#2e59d9',
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: false,
                },
                y: {
                    stacked: false,
                    beginAtZero: true
                }
            }
        }
    });
</script> --}}
@endpush
