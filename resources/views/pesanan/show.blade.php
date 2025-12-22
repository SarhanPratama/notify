@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">

        <!-- ===== HEADER HALAMAN ===== -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('admin.pesanan.index') }}"
                class="btn btn-outline-secondary fw-bold d-flex align-items-center gap-1">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-success py-3 border-0 d-flex align-items-center text-light">
                        <i class="fas fa-store me-2"></i>
                        <h6 class="mb-0 fw-bold">Informasi Outlet</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <small class="d-block">Nama Outlet</small>
                                <span class="fw-bold">{{ $order->outlet->nama ?? '-' }}</span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="d-block">Telepon</small>
                                <span>{{ $order->outlet->telepon ?? '-' }}</span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="d-block">Alamat</small>
                                <span>{{ $order->outlet->alamat ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header bg-success py-3 border-0 d-flex align-items-center text-light">
                        <i class="fas fa-info-circle me-2"></i>
                        <h6 class="mb-0 fw-bold">Informasi Pesanan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <small class="d-block">Tanggal</small>
                                <span>{{ \Carbon\Carbon::parse($order->tanggal)->format('d F Y') }}</span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <small class="d-block">Kode</small>
                                <span class="fw-bold">{{ $order->nobukti }}</span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <small class="d-block">Kas Masuk</small>
                                <span>{{ $order->transaksi->SumberDana->nama ?? 'Tidak tercatat' }}</span>
                            </div>
                            <div class="col-lg-3 mb-3">
                                @php
                                    $metode = $order->status_pembayaran;
                                    $statusTransaksi = $order->status;
                                    $statusPiutang = $order->piutang->status ?? null;
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
                        @forelse ($order->mutasi as $item)
                            <tr>
                                <td class="ps-4 align-middle">
                                    <div class="fw-medium">{{ $item->bahanBaku->nama ?? 'Produk Dihapus' }}</div>
                                </td>
                                <td class="text-center align-middle">
                                    <span
                                        class="badge bg-secondary bg-opacity-25 text-dark fw-medium rounded-pill px-3 py-2">
                                        {{ $item->quantity }} {{ $item->bahanBaku->satuan->nama ?? '' }}
                                    </span>
                                </td>
                                <td class="text-nowrap">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="text-nowrap">Rp {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Tidak ada produk dalam pesanan ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="3" class="text-end pe-4">Total Pesanan</th>
                            <th class="text-nowrap">Rp {{ number_format($order->total, 0, ',', '.') }}</th>
                        </tr>
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
                            {!! $order->catatan !!}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom Total & Aksi -->
            @can('pesanan')
                <div class="col-lg-12">
                    <div class="card shadow-sm rounded-3 border-light">
                        <div class="card-body">
                            @if ($order->status === 'pending' && auth()->user()->hasRole('gudang'))
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
                            @elseif ($order->status === 'approved_by_gudang' && auth()->user()->hasRole('keuangan'))
                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <button type="button" class="btn btn-outline-success" data-toggle="modal"
                                        data-target="#approveModal">
                                        Approve & Proses Pembayaran
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" data-toggle="modal"
                                        data-target="#rejectModal">
                                        Tolak Pesanan
                                    </button>
                                </div>
                            @elseif ($order->status === 'pending' && auth()->user()->hasRole('keuangan'))
                                <div class="alert alert-warning text-dark">
                                    <i class="fas fa-exclamation-triangle text-dark"></i> Pesanan ini belum diverifikasi oleh
                                    Admin Gudang.
                                </div>
                            @elseif ($order->status === 'approved_by_gudang' && auth()->user()->hasRole('gudang'))
                                <div class="alert alert-warning text-dark">
                                    <i class="fas fa-exclamation-triangle text-dark"></i> Pesanan ini sudah diverifikasi stok
                                    dan menunggu proses pembayaran oleh Admin Keuangan.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    @include('pesanan.modal-approve')
    @include('pesanan.modal-reject')
@endsection
