@extends('layouts.outlet')

@section('content')
    <div class="d-flex align-items-center mb-3">
        <div class="ms-1">
            <a href="{{ route('outlet.pesanan', ['token' => $token]) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <div class="flex-grow-1 text-center">
            <h4 class="fw-bold text-maron fs-4 mb-0">Detail Pesanan</h4>
            <small class="text-muted"><strong>{{ $order->nobukti }}</strong></small>
        </div>
    </div>

    <div class="row g-3">
        <!-- Left Column: Main Info -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 text-center mb-3">
                            <small class="text-muted d-block">Tanggal Pesan</small>
                            <span class="fw-semibold">{{ $order->created_at->format('d F Y') }}</span>
                            <div class="small text-muted">{{ $order->created_at->format('H:i') }} WIB</div>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <small class="text-muted d-block">Tanggal Kirim</small>
                            <span
                                class="fw-semibold">{{ $order->tanggal ? \Carbon\Carbon::parse($order->tanggal)->format('d F Y') : '-' }}</span>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <small class="text-muted d-block">Status</small>
                            @switch($order->status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @break

                                @case('approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @break

                                @case('rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @break

                                @case('completed')
                                    <span class="badge bg-info text-dark">Selesai</span>
                                @break

                                @default
                                    <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                            @endswitch
                        </div>
                        @if ($order->catatan)
                            <div class="col-md-12 mb-2">
                                <small class="text-muted d-block">Catatan</small>
                                <div class="bg-light rounded p-2"><small class="fst-italic">"{{ $order->catatan }}"</small>
                                </div>
                            </div>
                        @endif
                    </div>

                    <hr class="mt-0">

                    <h6 class="fw-bold mb-3 my-3 text-center text-maron">Daftar Item</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->mutasi as $item)
                                    <tr>
                                        <td>{{ $item->bahanBaku->nama ?? '-' }}</td>
                                        <td class="text-center">{{ $item->quantity }}
                                            {{ $item->bahanBaku->satuan->nama ?? 'pcs' }}</td>
                                        <td class="text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp
                                            {{ number_format($item->sub_total ?? $item->quantity * $item->harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada detail item</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th class="text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i>Ringkasan Pesanan</h6>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1"><span>Total
                                Item:</span><strong>{{ $order->mutasi ? $order->mutasi->count() : 0 }}</strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>Total Pembayaran:</span><strong
                                class="text-success">Rp {{ number_format($order->total, 0, ',', '.') }}</strong></div>
                    </div>
                    <!-- Back button moved to header -->
                </div>
            </div>
        </div>
    </div>
@endsection
