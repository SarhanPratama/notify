@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Owner</h1>

    <!-- 📊 Kartu Info Cepat (Data Ringkasan Utama) -->
    <div class="row mb-4">
        <!-- Pemasukan Bulan Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 p-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pemasukan Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($pemasukanBulanIni ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 p-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Pengeluaran Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($pengeluaranBulanIni ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Piutang Beredar -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 p-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Piutang Beredar
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalPiutangBeredar ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Stok Kritis -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 p-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Item Stok Kritis
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $itemStokKritis ?? 0 }} Barang
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- � Data Aktivitas Harian -->
    <div class="row mb-4">
        <!-- Pemasukan Hari Ini -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 p-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pemasukan Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($pemasukanHariIni ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cash-register fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Hari Ini -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 p-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Pengeluaran Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($pengeluaranHariIni ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaksi Baru Hari Ini -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 p-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Transaksi Baru Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $transaksiBaruHariIni ?? 0 }} Transaksi
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- �📈 Grafik dan Visual -->
    <div class="row mb-4">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4 p-3">
                {!! $ArusKasChart->container() !!}
            </div>
        </div>

        <!-- Komposisi Saldo Kas -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4 p-3">

                {!! $SaldoKasChart->container() !!}
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Daftar Barang Segera Habis -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-boxes text-info"></i> Daftar Bahan Baku Segera Habis</h6>
                    <span class="badge badge-danger">{{ count($barangSegeraHabis) }} Item Kritis</span>
                </div>
                <div class="card-body">
                    @forelse($barangSegeraHabis as $barang)
                    <div class="alert alert-danger d-flex justify-content-between align-items-center" role="alert">
                        <div>
                            <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                            <strong>{{ $barang->nama }}</strong><br>
                            <small class="text-muted">Sisa: <span class="font-weight-bold text-danger">{{ number_format($barang->stok_akhir, 0) }} {{ $barang->nama_satuan }}</span>
                            (Min: {{ number_format($barang->stok_minimum, 0) }} {{ $barang->nama_satuan }})</small>
                        </div>
                        <div>
                            <a href="{{ route('pembelian.create') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-shopping-cart"></i> Order
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-success text-center text-black" role="alert">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <strong>Semua stok aman!</strong><br>
                        <small>Tidak ada barang yang mencapai batas minimum.</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Daftar Piutang Jatuh Tempo -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-credit-card text-warning"></i> Daftar Piutang Jatuh Tempo</h6>
                    <span class="badge badge-warning">{{ count($piutangJatuhTempo) }} Overdue</span>
                </div>
                <div class="card-body">
                    @forelse($piutangJatuhTempo as $piutang)
                    <div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">
                        {{-- <div> --}}
                            <strong class="text-black">
                                <i class="fas fa-clock text-warning mr-2"></i>
                                {{ $piutang->nama_mitra }}</strong><br>
                            <small class="text-muted">
                                Jumlah: <span class="font-weight-bold text-danger">Rp {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</span><br>
                                <span class="badge badge-danger">Telat {{ $piutang->hari_telat }} Hari</span>
                            </small>
                        {{-- </div> --}}
                        {{-- <div>
                            <a href="#" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-phone"></i> Tagih
                            </a>
                        </div> --}}
                    </div>
                    @empty
                    <div class="alert alert-success text-center text-black" role="alert">
                        <i class="fas fa-check-circle text-success mr-2"></i>
                        <strong>Semua piutang lancar!</strong><br>
                        <small>Tidak ada tagihan yang melewati jatuh tempo.</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ $SaldoKasChart->cdn() }}"></script>

{{ $SaldoKasChart->script() }}
{{ $ArusKasChart->script() }}

{{-- <script src="{{ url('vendor/chart.js/Chart.min.js')}}"></script> --}}


{{-- <script>
    // Tunggu sampai Chart.js siap
    function initCharts() {
        // Cash Flow Chart (7 Days)
        if (document.getElementById('cashFlowChart') && typeof Chart !== 'undefined') {
            var ctx = document.getElementById('cashFlowChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($dates) !!},
                    datasets: [
                        {
                            label: 'Pemasukan (Debit)',
                            data: {!! json_encode($pemasukan) !!},
                            backgroundColor: '#1cc88a',
                            hoverBackgroundColor: '#17a673',
                            borderWidth: 0
                        },
                        {
                            label: 'Pengeluaran (Kredit)',
                            data: {!! json_encode($pengeluaran) !!},
                            backgroundColor: '#e74a3b',
                            hoverBackgroundColor: '#be2617',
                            borderWidth: 0
                        }
                    ]
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
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        }

        // Cash Composition Pie Chart
        if (document.getElementById('kasCompositionChart') && typeof Chart !== 'undefined') {
            var ctx2 = document.getElementById('kasCompositionChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($kasNames) !!},
                    datasets: [{
                        data: {!! json_encode($kasSaldos) !!},
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617', '#5a5c72'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                        borderWidth: 0
                    }]
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
                            displayColors: true,
                            caretPadding: 10,
                        },
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                    },
                    cutout: '80%',
                }
            });
        }
    }

    // Tunggu sampai document ready dan Chart.js loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // Tunggu Chart library
            var checkChart = setInterval(function() {
                if (typeof Chart !== 'undefined') {
                    clearInterval(checkChart);
                    initCharts();
                }
            }, 100);
        });
    } else {
        // Document sudah siap
        var checkChart = setInterval(function() {
            if (typeof Chart !== 'undefined') {
                clearInterval(checkChart);
                initCharts();
            }
        }, 100);
    }
</script> --}}
@endpush
