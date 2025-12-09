@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        @include('layouts.breadcrumbs')
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary fw-bold d-flex align-items-center gap-1">
                Kembali
            </a>

            @if ($detailPembelian->status === 'pending' && (auth()->user()->hasRole('keuangan') || auth()->user()->hasRole('owner')))
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#approveModal">
                        Setujui
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#rejectModal">
                        Tolak
                    </button>
                </div>
            @endif
        </div>

        <!-- Alert Status Transaksi -->
        @if ($detailPembelian->status === 'approved')
            <div class="alert alert-success alert-dismissible fade show text-success" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Transaksi Disetujui</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
        @elseif($detailPembelian->status === 'rejected' || $detailPembelian->status === 'reject')
            <div class="alert alert-danger alert-dismissible fade show text-danger" role="alert">
                <i class="fas fa-times-circle me-2"></i>
                <strong>Transaksi Ditolak</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
        @elseif($detailPembelian->status === 'pending')
            <div class="alert alert-warning alert-dismissible fade show text-warning" role="alert">
                <i class="fas fa-clock me-2"></i>
                <strong>Menunggu Validasi</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0 ">
                    <div class="card-header bg-maron py-3 border-0 d-flex align-items-center text-light">
                        <i class="fas fa-truck me-2"></i>
                        <h6 class="mb-0 fw-bold">Informasi Supplier</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <small class="d-block text-muted">Nama Supplier</small>
                                <span class="fw-bold">{{ $detailPembelian->supplier->nama ?? '-' }}</span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="d-block text-muted">Telepon</small>
                                <span>{{ $detailPembelian->supplier->telepon ?? '-' }}</span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="d-block text-muted">Alamat</small>
                                <span>{{ $detailPembelian->supplier->alamat ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-maron py-3 border-0 d-flex align-items-center text-light">
                        <i class="fas fa-info-circle me-2"></i>
                        <h6 class="mb-0 fw-bold">Informasi Pembelian</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <small class="d-block text-muted">Tanggal</small>
                                <span>{{ \Carbon\Carbon::parse($detailPembelian->tanggal)->format('d F Y') }}</span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="d-block text-muted">Kode</small>
                                <span class="fw-bold">{{ $detailPembelian->nobukti }}</span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="d-block text-muted">Status Transaksi</small>
                                <div>
                                    @if ($detailPembelian->status === 'approved')
                                        <span class="badge fw-bolder bg-success">
                                            Disetujui
                                        </span>
                                    @elseif($detailPembelian->status === 'pending')
                                        <span class="badge fw-bolder bg-warning text-dark">
                                            Pending
                                        </span>
                                    @elseif($detailPembelian->status === 'reject' || $detailPembelian->status === 'rejected')
                                        <span class="badge fw-bolder bg-danger">
                                            Ditolak
                                        </span>
                                    @else
                                        <span
                                            class="badge fw-bolder bg-secondary">{{ ucfirst($detailPembelian->status) }}</span>
                                    @endif
                                </div>
                            </div>
                            @if ($detailPembelian->validated_by)
                                <div class="col-md-4 mb-3">
                                    <small class="d-block text-muted">Divalidasi Oleh</small>
                                    <span>{{ $detailPembelian->validator->name ?? '-' }}</span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <small class="d-block text-muted">Waktu Validasi</small>
                                    <span>{{ $detailPembelian->validated_at ? \Carbon\Carbon::parse($detailPembelian->validated_at)->format('d M Y, H:i') : '-' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== DAFTAR PRODUK ===== -->
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
                                <td class="text-nowrap">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="text-nowrap">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
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
                            {!! $detailPembelian->catatan ?: '<em>Tidak ada catatan</em>' !!}
                        </p>

                        @if ($detailPembelian->rejection_reason)
                            <hr>
                            <h6 class="fw-bold mb-2 text-danger">Alasan Penolakan</h6>
                            <p class="text-muted mb-0">
                                {{ $detailPembelian->rejection_reason }}
                            </p>
                        @endif
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
                                {{ number_format($detailPembelian->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('approval-pembelian.modal-approve')
    @include('approval-pembelian.modal-reject')
@endsection
