@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

        <div class="row mb-4">
            <!-- Total Saldo Kas Saat Ini -->
            {{-- <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Saldo Kas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalSaldoSaatIni ?? 0, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">Saldo saat ini</small>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Total Pendapatan Bulan Ini -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Pendapatan Bulan Ini
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalPendapatanBulanIni ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-up fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pengeluaran Bulan Ini -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Pengeluaran Bulan Ini
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalPengeluaranBulanIni ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-down fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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

            <!-- Total Hutang -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Hutang Jatuh Tempo
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalHutang ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Cash Flow 30 Hari -->
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-chart-line text-info"></i> Cash Flow 30
                            Hari Terakhir</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="cashFlowChart" width="100%" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-credit-card text-warning"></i>
                            Piutang Jatuh Tempo</h6>
                        <span class="badge badge-warning">{{ count($piutangJatuhTempo) }} Overdue</span>
                    </div>
                    <div class="card-body">
                        @forelse($piutangJatuhTempo as $piutang)
                            <div class="alert alert-warning d-flex justify-content-between align-items-center mb-2"
                                role="alert">
                                <div>
                                    <strong class="text-black">
                                        <i class="fas fa-clock text-warning mr-2"></i>
                                        {{ $piutang->penjualan->outlet->nama ?? 'N/A' }}
                                    </strong><br>
                                    <small class="text-muted">
                                        Jumlah: <span class="font-weight-bold text-danger">Rp
                                            {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</span><br>
                                        <span
                                            class="badge badge-danger">{{ \Carbon\Carbon::parse($piutang->jatuh_tempo)->diffInDays(now()) }}
                                            hari telat</span>
                                    </small>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-warning text-center text-warning" role="alert">
                                <strong>Semua piutang lancar!</strong><br>
                                <small>Tidak ada tagihan yang melewati jatuh tempo.</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- 📋 Transaksi Terbaru -->
        <div class="row mb-4">
            <div class="col-xl-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history text-primary"></i>
                            Transaksi Terbaru</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        {{-- <th>Sumber Dana</th> --}}
                                        <th>Deskripsi</th>
                                        <th>Tipe</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transaksiTerbaru as $transaksi)
                                        <tr>
                                            <td>{{ $transaksi->tanggal->format('d/m/Y') }}</td>
                                            {{-- <td>{{ $transaksi->SumberDana->nama ?? 'N/A' }}</td> --}}
                                            <td>{{ Str::limit($transaksi->deskripsi, 50) }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $transaksi->tipe === 'debit' ? 'success' : 'danger' }}">
                                                    {{ $transaksi->tipe === 'debit' ? 'Pemasukan' : 'Pengeluaran' }}
                                                </span>
                                            </td>
                                            <td
                                                class="font-weight-bold {{ $transaksi->tipe === 'debit' ? 'text-success' : 'text-danger' }}">
                                                Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $transaksi->status == 1 ? 'success' : 'warning' }}">
                                                    {{ $transaksi->status == 1 ? 'Success' : 'Pending' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p>Tidak ada data transaksi</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cash Flow Chart
            const cashFlowCtx = document.getElementById('cashFlowChart');
            if (cashFlowCtx) {
                const cashFlowData = @json($cashFlow30Hari);
                const labels = cashFlowData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short'
                    });
                });
                const debitData = cashFlowData.map(item => item.debit);
                const kreditData = cashFlowData.map(item => item.kredit);

                new Chart(cashFlowCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Pemasukan',
                            data: debitData,
                            backgroundColor: '#1cc88a',
                            borderColor: '#1cc88a',
                            borderWidth: 1
                        }, {
                            label: 'Pengeluaran',
                            data: kreditData,
                            backgroundColor: '#e74a3b',
                            borderColor: '#e74a3b',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': Rp ' + context.parsed.y
                                            .toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Laba Rugi Chart
            const labaRugiCtx = document.getElementById('labaRugiChart');
            if (labaRugiCtx) {
                const labaRugiData = @json($laporanBulanan);
                const labels = labaRugiData.map(item => {
                    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep',
                        'Okt', 'Nov', 'Des'
                    ];
                    return monthNames[item.bulan - 1] + ' ' + item.tahun;
                });
                const pendapatanData = labaRugiData.map(item => item.pendapatan);
                const pengeluaranData = labaRugiData.map(item => item.pengeluaran);

                new Chart(labaRugiCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Pendapatan',
                            data: pendapatanData,
                            borderColor: '#1cc88a',
                            backgroundColor: 'rgba(28, 200, 138, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Pengeluaran',
                            data: pengeluaranData,
                            borderColor: '#e74a3b',
                            backgroundColor: 'rgba(231, 74, 59, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': Rp ' + context.parsed.y
                                            .toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
