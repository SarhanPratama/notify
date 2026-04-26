@extends('layouts.master')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Dashboard Owner</h1>

    <div class="row mb-4">

        <div class="col-xl-4 col-md-6 mb-4">
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
                            <!-- <small class="text-muted">Saldo saat ini</small> -->
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan All Time -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 p-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pendapatan (All Time)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalPendapatanAllTime ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran All Time -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 p-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Pengeluaran (All Time)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalPengeluaranAllTime ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

            <!-- �📈 Grafik dan Visual -->
            <div class="row mb-4">
                <div class="col-xl-12 col-lg-12">
                    <div class="card shadow mb-4 p-3">
                        {!! $ArusKasChart->container() !!}
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <!-- Daftar Barang Segera Habis -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-boxes text-info"></i> Daftar
                                Bahan Baku Segera Habis</h6>
                            <span class="badge badge-danger">{{ count($barangSegeraHabis) }} Item Kritis</span>
                        </div>
                        <div class="card-body">
                            @forelse($barangSegeraHabis as $barang)
                                <div class="alert alert-danger d-flex justify-content-between align-items-center"
                                    role="alert">
                                    <div>
                                        <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                                        <strong>{{ $barang->nama }}</strong><br>
                                        <small class="text-muted">Sisa: <span
                                                class="font-weight-bold text-danger">{{ number_format($barang->stok_akhir, 0) }}
                                                {{ $barang->nama_satuan }}</span>
                                            (Min: {{ number_format($barang->stok_minimum, 0) }}
                                            {{ $barang->nama_satuan }})
                                        </small>
                                    </div>
                                    @hasrole('gudang')
                                        <div>
                                            <a href="{{ route('pembelian.create') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-shopping-cart"></i> Order
                                            </a>
                                        </div>
                                    @endhasrole
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
                            <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-credit-card text-warning"></i>
                                Daftar Piutang Jatuh Tempo</h6>
                            <span class="badge badge-warning">{{ count($piutangJatuhTempo) }} Overdue</span>
                        </div>
                        <div class="card-body">
                            @forelse($piutangJatuhTempo as $piutang)
                                <div class="alert alert-warning d-flex justify-content-between align-items-center"
                                    role="alert">
                                    {{-- <div> --}}
                                    <strong class="text-black">
                                        <i class="fas fa-clock text-warning mr-2"></i>
                                        {{ $piutang->nama_mitra }}</strong><br>
                                    <small class="text-muted">
                                        Jumlah: <span class="font-weight-bold text-danger">Rp
                                            {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</span><br>
                                        <span class="badge badge-danger">Telat {{ $piutang->hari_telat }} Hari</span>
                                    </small>
                                    {{--
                                    </div> --}}
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
        @endsection

        @push('scripts')
            <script src="{{ $ArusKasChart->cdn() }}"></script>
            {{--
            {{ $SaldoKasChart->script() }} --}}
            {{ $ArusKasChart->script() }}
        @endpush
