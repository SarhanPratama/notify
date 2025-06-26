@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Dashboard Admin Gudang</h1>

        <!-- Ringkasan Stok -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs text-primary font-weight-bold text-uppercase mb-1">Total Bahan Baku</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $totalBahanBaku }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Bahan Baku Minimum</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bahanBakuMinimumCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Pengeluaran Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                    {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Pengeluaran Gudang Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                    {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Pendapatan Gudang Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                    {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</div>

                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Persentase Pembelian</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 ml-2 mr-3 font-weight-bold text-gray-800">
                                            {{ number_format($persentasePembelian, 2) }}%</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-{{ $persentasePembelian >= 0 ? 'info' : 'danger' }}"
                                                role="progressbar" style="width: {{ abs($persentasePembelian) }}%"
                                                aria-valuenow="{{ abs($persentasePembelian) }}" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Grafik Tren Stok -->
            <div class="row">
                {{-- <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Tren Stok 30 Hari Terakhir</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="stokTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div> --}}

                 <!-- Distribusi ke Outlet -->
            <div class="row">
                <div class="col-xl-6 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Penjualan Gudang Hari Ini</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Outlet</th>
                                            <th>Bahan Baku</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distribusiHariIni as $distribusi)
                                            <tr>
                                                <td>{{ $distribusi->penjualan->tanggal }}</td>
                                                <td>{{ $distribusi->penjualan->cabang->nama }}</td>
                                                <td>{{ $distribusi->bahanBaku->nama }}</td>
                                                <td>{{ $distribusi->quantity }}
                                                    {{ $distribusi->bahanBaku->satuan->nama ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pembelian Terakhir -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Pembelian Terakhir</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No. Bukti</th>
                                            <th>Tanggal</th>
                                            <th>Supplier</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pembelianTerakhir as $pembelian)
                                            <tr>
                                                <td>{{ $pembelian->nobukti }}</td>
                                                <td>{{ $pembelian->tanggal }}</td>
                                                <td>{{ $pembelian->supplier->nama ?? '-' }}</td>
                                                <td>Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



                <!-- Outlet dengan Permintaan Terbanyak -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Outlet dengan Permintaan Terbanyak</h6>
                        </div>
                        <div class="card-body">
                            @foreach ($outletPermintaan as $outlet)
                                <h4 class="small font-weight-bold">{{ $outlet->cabang }} <span
                                        class="float-right">{{ $outlet->total_permintaan }}</span></h4>
                                <div class="progress mb-4">
                                    <div class="progress-bar bg-{{ $loop->index % 2 == 0 ? 'info' : 'success' }}"
                                        role="progressbar"
                                        style="width: {{ ($outlet->total_permintaan / ($outletPermintaan->first()->total_permintaan ?: 1)) * 100 }}%"
                                        aria-valuenow="{{ $outlet->total_permintaan }}" aria-valuemin="0"
                                        aria-valuemax="{{ $outletPermintaan->first()->total_permintaan }}"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bahan Baku Perlu Restock -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-danger">Bahan Baku Perlu Restock</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" width="100%" cellspacing="0">
                                    <thead>
                                        <tr class="bg-danger text-white">
                                            <th>Bahan Baku</th>
                                            <th>Stok Sekarang</th>
                                            <th>Stok Minimum</th>
                                            {{-- <th>Supplier Termurah</th> --}}
                                            {{-- <th>Harga</th> --}}
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bahanPerluRestock as $bahan)
                                            <tr>
                                                <td>{{ $bahan->nama }}</td>
                                                <td class="text-danger font-weight-bold">{{ $bahan->stok_akhir }}</td>
                                                <td>{{ $bahan->stok_minimum }}</td>
                                                {{-- <td>{{ $bahan->supplier->first()->nama ?? '-' }}</td> --}}
                                                {{-- <td>Rp
                                                    {{ number_format($bahan->supplier->first()->pivot->harga ?? 0, 0, ',', '.') }}
                                                </td> --}}
                                                {{-- <td>
                                                    <a href="{{ route('pembelian.create', ['bahan_baku_id' => $bahan->id]) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-cart-plus"></i> Beli
                                                    </a>
                                                </td> --}}
                                            </tr>
                                        @endforeach
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
        <script>
            // Grafik Tren Stok
            var ctx = document.getElementById('stokTrendChart').getContext('2d');
            var stokTrendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($stokTrend->pluck('date')) !!},
                    datasets: [{
                        label: 'Perubahan Stok',
                        data: {!! json_encode($stokTrend->pluck('perubahan')) !!},
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        },
                        y: {
                            grid: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: "rgb(255,255,255)",
                            bodyColor: "#858796",
                            titleMarginBottom: 10,
                            titleColor: '#6e707e',
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
                                label: function(context) {
                                    var label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.parsed.y;
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
