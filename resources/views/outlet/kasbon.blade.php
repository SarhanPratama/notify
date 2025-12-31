@extends('layouts.outlet')

@section('content')
<div class="container-fluid mt-3">
    <!-- Page Header -->
    <div class="d-flex justify-content-center align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-maron fs-4 fw-bold">
                Kasbon
            </h4>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center flex-column gap-2">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded-3 p-2">
                                <i class="fas fa-money-bill-wave text-danger fs-6"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Total Kasbon</h6>
                            <h5 class="mb-0 fw-bold text-center">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center flex-column gap-2">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-2">
                                <i class="fas fa-exclamation-triangle text-warning fs-6"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Jatuh Tempo</h6>
                            <h5 class="mb-0 fw-bold text-center text-warning">Rp
                                {{ number_format($jatuhTempo, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center flex-column gap-2">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-3 p-2">
                                <i class="fas fa-clock text-info fs-6"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Belum Dibayar</h6>
                            <h5 class="mb-0 fw-bold text-center">Rp {{ number_format($belumDibayar, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center flex-column gap-2">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-3 p-2">
                                <i class="fas fa-check-circle text-success fs-6"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1 small">Sudah Dibayar</h6>
                            <h5 class="mb-0 fw-bold text-center text-success">Rp
                                {{ number_format($totalDibayar, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tagihan List as Table -->
    @if ($piutangs->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <p class="fs-5 fw-bold text-center text-maron">Daftar Kasbon</p>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="dataTableHover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">Nomor Bukti</th>
                                <th class="text-nowrap">Tanggal</th>
                                <th class="text-nowrap">Jatuh Tempo</th>
                                <th class="text-nowrap">Status Transaksi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($piutangs as $piutang)
                                @php
                                    $totalBayar = $piutang->pembayaran->sum('jumlah');
                                    // $sisaPiutang = $piutang->jumlah_piutang - $totalBayar;
                                    $isJatuhTempo = $piutang->jatuh_tempo && $piutang->jatuh_tempo < now() && $piutang->status != 'lunas';
                                @endphp
                                <tr class="{{ $isJatuhTempo && $piutang->status != 'lunas' ? 'table-danger' : '' }}">
                                    <td class="fw-semibold text-nowrap">
                                        {{ $piutang->nobukti }}
                                        @if ($piutang->penjualan && $piutang->penjualan->mutasi && $piutang->penjualan->mutasi->count() > 0)
                                            <div class="small text-muted">Item: {{ $piutang->penjualan->mutasi->count() }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $piutang->created_at->translatedFormat('l, d/m/Y') }}
                                    </td>
                                    <td class="{{ $isJatuhTempo ? 'text-danger fw-semibold' : '' }}">
                                        {{ $piutang->jatuh_tempo ? $piutang->jatuh_tempo->translatedFormat('l, d/m/Y') : '-' }}
                                        @if ($isJatuhTempo)
                                            <span class="badge bg-danger ms-1">Terlambat</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($piutang->status == 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-danger">Belum Lunas</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a class="btn btn-outline-primary"
                                                href="{{ route('outlet.kasbon.detail', ['token' => $token, 'piutang' => $piutang->id]) }}"
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-file-invoice-dollar text-muted" style="font-size: 64px; opacity: 0.3;"></i>
                </div>
                <p class="text-muted mb-4">
                    Belum ada tagihan untuk outlet Anda saat ini.
                </p>
                {{-- <a href="{{ route('outlet.belanja', ['token' => $token]) }}" class="btn btn-outline-danger">
                    Mulai Belanja
                </a> --}}
            </div>
        </div>
    @endif
</div>
@endsection
