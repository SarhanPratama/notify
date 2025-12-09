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
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-1"></i> Cetak
            </button>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 ">
                    <div class="card-header bg-success py-3 border-0 d-flex align-items-center text-light">
                        {{-- <span class="bg-light text-maron p-2 rounded-circle mr-2">
                        </span> --}}
                        <i class="fas fa-store me-2"></i>
                        <h6 class="mb-0 fw-bold">Informasi Outlet</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <small class="d-block">Nama Outlet</small>
                                <span class="fw-bold">{{ $detailPenjualan->outlet->nama ?? '-' }}</span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="d-block">Telepon</small>
                                <span>{{ $detailPenjualan->outlet->telepon ?? '-' }}</span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="d-block">Alamat</small>
                                <span>{{ $detailPenjualan->outlet->alamat ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-success py-3 border-0 d-flex align-items-center text-light">
                        <i class="fas fa-info-circle me-2"></i>
                        <h6 class="mb-0 fw-bold">Informasi Penjualan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <small class="d-block">Tanggal</small>
                                <span>{{ \Carbon\Carbon::parse($detailPenjualan->tanggal)->format('d F Y') }}</span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <small class="d-block">Kode</small>
                                <span class="fw-bold">{{ $detailPenjualan->nobukti }}</span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <small class="d-block">Kas Masuk</small>
                                <span>{{ $detailPenjualan->transaksi->SumberDana->nama ?? 'Tidak tercatat' }}</span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                @php
                                    $metode = $detailPenjualan->metode_pembayaran;
                                    $statusTransaksi = $detailPenjualan->status;
                                    $statusPiutang = $detailPenjualan->piutang->status ?? null;
                                @endphp

                                <small class="d-block">Status Transaksi</small>

                                @if ($metode === 'tunai')
                                    @switch($statusTransaksi)
                                        @case('pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @break

                                        @case('approved')
                                            <span class="badge bg-info text-dark">Approved</span>
                                        @break

                                        @case('rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @break

                                        @case('completed')
                                            <span class="badge bg-success">Lunas</span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">Unknown</span>
                                    @endswitch

                                    {{-- CASE 2: PEMBAYARAN KASBON --}}
                                @elseif ($metode === 'kasbon')
                                    @switch($statusPiutang)
                                        @case('belum_lunas')
                                            <span class="badge bg-warning text-dark">Kasbon - Belum Lunas</span>
                                        @break

                                        @case('lunas')
                                            <span class="badge bg-success">Kasbon - Lunas</span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">Kasbon - Tidak Diketahui</span>
                                    @endswitch
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== DAFTAR PRODUK ===== -->
        <div class="card shadow-sm rounded-3 border-light">
            <div class="card-header bg-success py-3 text-light">
                <h6 class="mb-0 fw-bold d-flex align-items-center">
                    <i class="fas fa-boxes me-2"></i>
                    Rincian Produk
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
                        @forelse ($detailPenjualan->mutasi as $detail)
                            <tr>
                                <td class="ps-4 align-middle">
                                    <div class="fw-medium">{{ $detail->bahanBaku->nama ?? 'Produk Dihapus' }}</div>
                                </td>
                                <td class="text-center align-middle">
                                    <span
                                        class="badge bg-secondary bg-opacity-25 text-dark fw-medium rounded-pill px-3 py-2">
                                        {{ $detail->quantity }} {{ $detail->bahanBaku->satuan->nama ?? '' }}
                                    </span>
                                </td>
                                <td class="text-nowrap">Rp
                                    {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="text-nowrap">Rp
                                    {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Tidak ada produk dalam pembelian ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===== RINGKASAN & CATATAN ===== -->
        <div class="row my-3 g-4">
            <!-- Kolom Catatan -->
            <div class="col-lg-7">
                <div class="card h-100 shadow-sm rounded-3 border-light">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-2">Catatan</h6>
                        <p class="text-muted mb-0">
                            {!! $detailPenjualan->catatan !!}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom Total -->
            <div class="col-lg-5">
                <div class="card shadow-sm rounded-3 border-light">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 fw-semibold">Total</span>
                            <span class="h5 mb-0 fw-bold text-primary">Rp
                                {{ number_format($detailPenjualan->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($detailPenjualan->metode_pembayaran === 'kasbon' && $detailPenjualan->piutang)
            @php
                $piutang = $detailPenjualan->piutang;
                $totalDibayar = $piutang->pembayaran->sum('jumlah');
                $sisa = $piutang->jumlah_piutang - $totalDibayar;
            @endphp

            <div class="card shadow-sm border-light mt-4 mb-3">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <i class="fas fa-wallet me-2"></i> Riwayat Pembayaran Piutang
                </div>
                <div class="card-body">

                    <!-- RINGKASAN -->
                    <div class="row mb-3">
                        <div class="col-md-4 text-center">
                            <small class="text-muted fw-bold">Total Piutang</small>
                            <div class="fw-bold text-dark">Rp {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <small class="text-muted fw-bold">Sudah Dibayar</small>
                            <div class="fw-bold text-success">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-4 text-center">
                            <small class="text-muted fw-bold">Sisa Piutang</small>
                            <div class="fw-bold text-danger">Rp {{ number_format($sisa, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <!-- RIWAYAT -->
                    @if ($piutang->pembayaran->isEmpty())
                        <p class="text-muted mb-0">Belum ada pembayaran tercatat.</p>
                    @else
                        <ul class="list-group">
                            @foreach ($piutang->pembayaran as $pay)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $pay->tanggal->format('d M Y') }}</strong><br>
                                        {{-- <small class="text-muted">{{ $pay->keterangan }}</small> --}}
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold d-block">Rp
                                            {{ number_format($pay->jumlah, 0, ',', '.') }}</span>
                                        <span class="badge bg-primary mt-1">{{ $pay->sumberDana->nama }}</span>
                                    </div>
                                </li>
                                <hr>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
