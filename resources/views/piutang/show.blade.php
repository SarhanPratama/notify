@extends('layouts.master')

@section('content')
    {{-- Menggunakan breadcrumbs yang sudah ada --}}
    @include('layouts.breadcrumbs')

    <div class="container-fluid">

        <!-- ===== HEADER HALAMAN ===== -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary fw-bold d-flex align-items-center gap-1">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('piutang.print', $piutang->nobukti) }}" class="btn btn-success" target="_blank">
                    <i class="fas fa-print me-1"></i> Cetak Thermal
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print me-1"></i> Cetak Browser
                </button>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 ">
                    <div class="card-header bg-warning py-3 border-0 d-flex align-items-center text-dark">
                        <i class="fas fa-store me-2"></i>
                        <h6 class="mb-0 fw-bold">Informasi Outlet</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <small class="d-block">Nama Outlet</small>
                                <span class="fw-bold">{{ $piutang->penjualan->outlet->nama ?? '-' }}</span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="d-block">Telepon</small>
                                <span>{{ $piutang->penjualan->outlet->telepon ?? '-' }}</span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="d-block">Alamat</small>
                                <span>{{ $piutang->penjualan->outlet->alamat ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-warning py-3 border-0 d-flex align-items-center text-dark">
                        <i class="fas fa-info-circle me-2"></i>
                        <h6 class="mb-0 fw-bold">Informasi Piutang</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <small class="d-block">Tanggal Jatuh Tempo</small>
                                <span>{{ \Carbon\Carbon::parse($piutang->jatuh_tempo)->format('d F Y') }}</span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <small class="d-block">No. Bukti</small>
                                <span class="fw-bold">{{ $piutang->nobukti }}</span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <small class="d-block">Total Piutang</small>
                                <span class="fw-bold text-danger">Rp {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <small class="d-block">Status</small>
                                @if($piutang->status === 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($piutang->status === 'belum_lunas')
                                    <span class="badge bg-warning text-dark">Belum Lunas</span>
                                @else
                                    <span class="badge bg-secondary">{{ $piutang->status }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== DAFTAR PRODUK ===== -->
        <div class="card shadow-sm rounded-3 border-light">
            <div class="card-header bg-warning py-3 text-dark">
                <h6 class="mb-0 fw-bold d-flex align-items-center">
                    <i class="fas fa-boxes me-2"></i>
                    Rincian Produk Penjualan
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Produk</th>
                            <th class="text-center">Kuantitas</th>
                            <th class="text-nowrap">Harga Satuan</th>
                            <th class="text-nowrap">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($piutang->penjualan->mutasi as $detail)
                            <tr>
                                <td class="ps-4 align-middle">
                                    <div class="fw-medium">{{ $detail->bahanBaku->nama ?? 'Produk Dihapus' }}</div>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge bg-secondary bg-opacity-25 text-dark fw-medium rounded-pill px-3 py-2">
                                        {{ $detail->quantity }} {{ $detail->bahanBaku->satuan->nama ?? '' }}
                                    </span>
                                </td>
                                <td class="text-nowrap">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="text-nowrap">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Tidak ada produk dalam penjualan ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===== RINGKASAN PIUTANG ===== -->
        <div class="row my-3 g-4">
            <!-- Kolom Catatan Penjualan -->
            <div class="col-lg-7">
                <div class="card h-100 shadow-sm rounded-3 border-light">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-2">Catatan Penjualan</h6>
                        <p class="text-muted mb-0">
                            {!! $piutang->penjualan->catatan !!}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom Ringkasan Piutang -->
            <div class="col-lg-5">
                <div class="card shadow-sm rounded-3 border-light">
                    <div class="card-body p-4">
                        @php
                            $totalDibayar = $piutang->pembayaran->sum('jumlah');
                            $sisa = $piutang->jumlah_piutang - $totalDibayar;
                        @endphp

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-medium">Total Piutang</span>
                                <span class="fw-bold text-danger">Rp {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-medium">Sudah Dibayar</span>
                                <span class="fw-bold text-success">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 mb-0 fw-semibold">Sisa Piutang</span>
                                <span class="h6 mb-0 fw-bold {{ $sisa > 0 ? 'text-danger' : 'text-success' }}">
                                    Rp {{ number_format($sisa, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== RIWAYAT PEMBAYARAN ===== -->
        <div class="card shadow-sm border-light mt-4 mb-3">
            <div class="card-header bg-warning text-dark d-flex align-items-center">
                <i class="fas fa-wallet me-2"></i> Riwayat Pembayaran Piutang
            </div>
            <div class="card-body">
                @if ($piutang->pembayaran->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Belum ada pembayaran tercatat untuk piutang ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Sumber Dana</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($piutang->pembayaran as $pembayaran)
                                    <tr>
                                        <td>{{ $pembayaran->tanggal->format('d M Y') }}</td>
                                        <td>{{ $pembayaran->sumberDana->nama }}</td>
                                        <td class="text-end fw-bold">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
