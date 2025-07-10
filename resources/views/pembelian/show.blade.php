@extends('layouts.master')

@section('content')
    {{-- Menggunakan breadcrumbs yang sudah ada --}}
    @include('layouts.breadcrumbs')

    <div class="container-fluid">

        <!-- ===== HEADER HALAMAN ===== -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ url()->previous() }}"
                class="btn btn-outline-secondary fw-bold d-flex align-items-center gap-1">
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
                        <i class="fas fa-truck me-2"></i>
                        <h6 class="mb-0 fw-bold">Informasi Supplier</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-user text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Nama</small>
                                <span class="fw-bold">{{ $detailPembelian->supplier->nama ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-phone text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Telepon</small>
                                <span>{{ $detailPembelian->supplier->telepon ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-map-marker-alt text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Alamat</small>
                                <span>{{ $detailPembelian->supplier->alamat ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-success py-3 border-0 d-flex align-items-center text-light">
                        <i class="fas fa-info-circle me-2"></i>
                        <h6 class="mb-0 fw-bold">Informasi Pembelian</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-calendar-alt text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Tanggal</small>
                                <span>{{ \Carbon\Carbon::parse($detailPembelian->tanggal)->format('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-tag text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Kode</small>
                                <span class="fw-bold">{{ $detailPembelian->nobukti }}</span>
                            </div>
                        </div>
                        {{-- <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-user-edit text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Dibuat Oleh</small>
                                <span>{{ $detailPembelian->user->name ?? '-' }}</span>
                            </div>
                        </div> --}}
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-wallet text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Sumber Dana</small>
                                <span>{{ $detailPembelian->transaksi->first()->SumberDana->nama ?? 'Tidak tercatat' }}</span>
                            </div>
                        </div>
                        {{-- <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-wallet text-success"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Sumber Dana</small>
                                <span>{{ $detailPembelian->transaksi->first()->SumberDana->nama ?? 'Tidak tercatat' }}</span>
                            </div>
                        </div> --}}
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
                        @forelse ($detailPembelian->mutasi as $detail)
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
                            {!! $detailPembelian->catatan !!}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom Total -->
            <div class="col-lg-5">
                <div class="card shadow-sm rounded-3 border-light">
                    <div class="card-body p-4">
                        {{-- Anda bisa menambahkan item lain seperti diskon atau pajak di sini --}}
                        {{-- <div class="d-flex justify-content-between text-muted mb-2">
                            <span>Subtotal</span>
                            <span>Rp 1.000.000</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted mb-2">
                            <span>Pajak (11%)</span>
                            <span>Rp 110.000</span>
                        </div>
                        <hr> --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 fw-semibold">Total</span>
                            <span class="h5 mb-0 fw-bold text-primary">Rp
                                {{ number_format($detailPembelian->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
