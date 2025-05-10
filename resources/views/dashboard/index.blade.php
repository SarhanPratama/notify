@extends('layouts.master')
@section('content')
    @include('layouts.breadcrumbs')

    <div class="row mb-4">
        <!-- Kas Seroo Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Kas Seroo</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                {{ number_format($totalKasSeroo, 0, ',', '.') }}</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-{{ $persentaseKas >= 0 ? 'success' : 'danger' }} mr-2">
                                    <i class="fa fa-arrow-{{ $persentaseKas >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($persentaseKas) }}%
                                </span>
                                <span>Since last month</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penjualan Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Penjualan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-{{ $persentasePenjualan >= 0 ? 'success' : 'danger' }} mr-2">
                                    <i class="fas fa-arrow-{{ $persentasePenjualan >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($persentasePenjualan) }}%
                                </span>
                                <span>Since last month</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pembelian Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Pembelian</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                {{ number_format($totalPembelian, 0, ',', '.') }}</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-{{ $persentasePembelian >= 0 ? 'success' : 'danger' }} mr-2">
                                    <i class="fas fa-arrow-{{ $persentasePembelian >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($persentasePembelian) }}%
                                </span>
                                <span>Since last month</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bahan Baku Minimum Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Bahan Baku Minimum</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bahanBakuMinimumCount }}</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-danger mr-2"><i class="fas fa-exclamation-circle"></i> Perlu
                                    Restock</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Grafik Laporan Bulanan -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Laporan Bulanan</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Opsi:</div>
                            <a class="dropdown-item" href="#" onclick="updateChart('penjualan')">Penjualan</a>
                            <a class="dropdown-item" href="#" onclick="updateChart('pembelian')">Pembelian</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="updateChart('semua')">Tampilkan Semua</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlyReportChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bahan Baku Terlaris -->
        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Bahan Baku Terlaris</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle btn btn-primary btn-sm" href="#" role="button"
                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Bulan Ini <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Pilih Periode</div>
                            <a class="dropdown-item" href="#" onclick="updateTopProducts('today')">Hari Ini</a>
                            <a class="dropdown-item" href="#" onclick="updateTopProducts('week')">Minggu Ini</a>
                            <a class="dropdown-item active" href="#" onclick="updateTopProducts('month')">Bulan
                                Ini</a>
                            <a class="dropdown-item" href="#" onclick="updateTopProducts('year')">Tahun Ini</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ($topBahanBaku as $index => $bahan)
                        <div class="mb-3">
                            <div class="small text-gray-500">{{ $bahan->nama }}
                                <div class="small float-right"><b>{{ $bahan->total_terjual }}
                                        {{ $bahan->satuan->nama ?? '' }}</b></div>
                            </div>
                            <div class="progress" style="height: 12px;">
                                @php
                                    $percentage = ($bahan->total_terjual / $maxTerjual) * 100;
                                    $colors = ['bg-warning', 'bg-success', 'bg-danger', 'bg-info', 'bg-primary'];
                                @endphp
                                <div class="progress-bar {{ $colors[$index % count($colors)] }}" role="progressbar"
                                    style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer text-center">
                    <a class="m-0 small text-primary card-link" href="{{ route('bahan-baku.index') }}">Lihat Semua <i
                            class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Bahan Baku Minimum -->
    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Bahan Baku Mencapai Batas Minimum</h6>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Bahan Baku</th>
                                <th>Stok Sekarang</th>
                                <th>Stok Minimum</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bahanBakuMinimum as $index => $bahan)
                                <tr style="background-color: #ffe6e6;">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bahan->nama }}</td>
                                    <td><span class="badge bg-danger">{{ $bahan->stok_akhir }}</span></td>
                                    <td>{{ $bahan->stok_minimum }}</td>
                                    <td>{{ $bahan->satuan->nama ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center;">✅ Semua stok masih aman</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a class="m-0 small text-primary card-link" href="{{ route('bahan-baku.index') }}">Lihat Semua <i
                            class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Aktivitas Terakhir -->
        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-header py-4 bg-primary d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-light">Aktivitas Terakhir</h6>
                </div>
                <div>
                    @foreach ($recentActivities as $activity)
                        <div class="customer-message align-items-center">
                            <a class="{{ $activity->is_important ? 'font-weight-bold' : '' }}" href="#">
                                <div class="text-truncate message-title">
                                    <i class="fas fa-{{ $activity->icon }} mr-2"></i>
                                    {{ $activity->description }}
                                </div>
                                <div
                                    class="small text-gray-500 message-time {{ $activity->is_important ? 'font-weight-bold' : '' }}">
                                    {{-- {{ $activity->user->name }} · {{ $activity->created_at->diffForHumans() }} --}}
                                </div>
                            </a>
                        </div>
                    @endforeach
                    <div class="card-footer text-center">
                        <a class="m-0 small text-primary card-link" href="{{ url('aktivitas.index') }}">Lihat Semua <i
                                class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.defaults.font.family = 'Nunito, -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.color = '#858796';

    var ctx = document.getElementById('monthlyReportChart').getContext('2d');

    var chartData = {
        labels: {!! json_encode($monthlyLabels) !!},
        datasets: [
            {
                label: 'Penjualan',
                data: {!! json_encode($monthlyPenjualan) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff'
            },
            {
                label: 'Pembelian',
                data: {!! json_encode($monthlyPembelian) !!},
                backgroundColor: 'rgba(28, 200, 138, 0.05)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: 'rgba(28, 200, 138, 1)',
                pointBorderColor: '#fff'
            }
        ]
    };

    var chartOptions = {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    };

    var monthlyReportChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: chartOptions
    });

    window.updateChart = function(type) {
        if (type === 'penjualan') {
            monthlyReportChart.data.datasets[0].hidden = false;
            monthlyReportChart.data.datasets[1].hidden = true;
        } else if (type === 'pembelian') {
            monthlyReportChart.data.datasets[0].hidden = true;
            monthlyReportChart.data.datasets[1].hidden = false;
        } else {
            monthlyReportChart.data.datasets[0].hidden = false;
            monthlyReportChart.data.datasets[1].hidden = false;
        }
        monthlyReportChart.update();
    };
});
</script>

