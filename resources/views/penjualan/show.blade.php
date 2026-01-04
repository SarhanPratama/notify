@extends('layouts.master')

@section('content')

@include('layouts.breadcrumbs')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary fw-bold d-flex align-items-center gap-1">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
    {{-- <button onclick="window.print()" class="btn btn-primary">
        <i class="fas fa-print me-1"></i> Cetak
    </button> --}}
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card h-100 shadow-sm border-0 ">
            <div class="card-header bg-maron py-3 border-0 d-flex align-items-center text-light">
                {{-- <span class="bg-light text-maron p-2 rounded-circle mr-2">
                </span> --}}
                <i class="fas fa-store me-2"></i>
                <h6 class="mb-0 fw-bold">Informasi Outlet</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <small class="d-block">Outlet</small>
                        <span class="fw-bold">{{ $detailPenjualan->outlet->nama ?? '-' }} -
                            {{ $detailPenjualan->outlet->penanggung_jawab ?? '-' }}</span>
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
            <div class="card-header bg-maron py-3 border-0 d-flex align-items-center text-light">
                <i class="fas fa-info-circle me-2"></i>
                <h6 class="mb-0 fw-bold">Informasi Penjualan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 mb-3">
                        <small class="d-block">Tanggal</small>
                        <span>{{ $detailPenjualan->tanggal->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <small class="d-block">Kode</small>
                        <span class="fw-bold">{{ $detailPenjualan->nobukti }}</span>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <small class="d-block">Status Approval</small>
                        @if ($detailPenjualan->status_gudang === 'approved')
                            <span class="badge bg-success">Gudang: Approved</span>
                        @elseif ($detailPenjualan->status_gudang === 'rejected')
                            <span class="badge bg-danger">Gudang: Rejected</span>
                        @else
                            <span class="badge bg-warning">Gudang: Pending</span>
                        @endif

                        @if ($detailPenjualan->status_keuangan === 'approved')
                            <span class="badge bg-success">Keuangan: Approved</span>
                        @elseif ($detailPenjualan->status_keuangan === 'rejected')
                            <span class="badge bg-danger">Keuangan: Rejected</span>
                        @else
                            <span class="badge bg-warning">Keuangan: Pending</span>
                        @endif
                    </div>
                    <div class="col-lg-3 mb-3">
                        <small class="d-block">Status Pembayaran</small>
                        @if ($detailPenjualan->status_pembayaran === 'lunas')
                            <span class="badge bg-success">Lunas</span>
                        @elseif ($detailPenjualan->status_pembayaran === 'piutang')
                            <span class="badge bg-warning">Piutang</span>
                        @else
                            <span class="badge bg-secondary">-</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm rounded-3 border-light">
    <div class="card-header bg-maron py-3 text-light">
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
            <tfoot>
                <th colspan="3" class="text-end">Total</th>
                <td class="text-nowrap text-success fw-bold">
                    Rp. {{ number_format($detailPenjualan->total, 0, ',', '.') }}
                </td>
            </tfoot>
        </table>
    </div>
</div>

<div class="row my-3 g-4">
    <!-- Kolom Catatan -->
    <div class="col-lg-12">
        <div class="card h-100 shadow-sm rounded-3 border-light">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-2">Catatan</h6>
                <p class="text-muted mb-0">
                    {!! $detailPenjualan->catatan !!}
                </p>
            </div>
        </div>
    </div>
</div>

    @can('pesanan')
@if ($detailPenjualan->status_gudang === 'pending' && auth()->user()->hasRole('gudang'))
    <div class="col-lg-12">
        <div class="card shadow-sm rounded-3 border-light">
            <div class="card-body">
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-success" data-toggle="modal"
                        data-target="#approveModal">
                        Setujui Pesanan
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-toggle="modal"
                        data-target="#rejectModal">
                        Tolak Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
    @endcan

    @include('penjualan.modal-approve')
    @include('penjualan.modal-reject')
@endsection
