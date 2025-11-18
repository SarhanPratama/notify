@extends('layouts.outlet')

@section('content')
    <!-- Page Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1 text-maron fs-4 fw-bold">
                    Riwayat Pesanan
                </h3>
            </div>
        </div>
    </div>

    <!-- Summary Cards (match kasbon style) -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center flex-column gap-2">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-2">
                                <i class="fas fa-clock text-warning fs-6"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="text-muted mb-1 small">Menunggu</h6>
                            <h5 class="mb-0 fw-bold text-center">{{ $orders->where('status', 'pending')->count() }}</h5>
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
                        <div class="flex-grow-1 ms-2">
                            <h6 class="text-muted mb-1 small">Disetujui</h6>
                            <h5 class="mb-0 fw-bold text-center">{{ $orders->where('status', 'approved')->count() }}</h5>
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
                                <i class="fas fa-check-double text-info fs-6"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="text-muted mb-1 small">Selesai</h6>
                            <h5 class="mb-0 fw-bold text-center">{{ $orders->where('status', 'completed')->count() }}</h5>
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
                            <div class="bg-primary bg-opacity-10 rounded-3 p-2">
                                <i class="fas fa-list text-primary fs-6"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="text-muted mb-1 small">Total Pesanan</h6>
                            <h5 class="mb-0 fw-bold text-center">{{ $orders->total() }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter removed for table view to keep UI simple -->

    <!-- Orders Table -->
    @if ($orders->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="fs-5 fw-bold text-center text-maron">Daftar Pesanan</p>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="dataTableHover">
                        <thead class="table-light">
                            <tr>
                                <td>No</td>
                                <th class="text-nowrap">Nomor Bukti</th>
                                <th class="text-nowrap">Tanggal Pesan</th>
                                <th class="text-nowrap">Status</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="fw-semibold text-nowrap">
                                        {{ $order->nobukti }}
                                        @if ($order->mutasi && $order->mutasi->count() > 0)
                                            <div class="small text-muted">Item: {{ $order->mutasi->count() }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $order->created_at->format('d/m/Y') }}
                                        <div class="small text-muted">{{ $order->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td class="text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('outlet.pesanan.detail', [$token, $order->id]) }}" class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-shopping-bag text-muted" style="font-size: 64px; opacity: 0.3;"></i>
                </div>
                <h5 class="text-muted mb-2">Belum Ada Pesanan</h5>
                <p class="text-muted mb-4">Anda belum melakukan pemesanan apapun. Yuk mulai pesan bahan baku untuk outlet Anda!</p>
                <a href="{{ route('outlet.belanja', $token) }}" class="btn bg-maron text-white">
                    <i class="fas fa-shopping-cart me-2"></i>Mulai Belanja
                </a>
            </div>
        </div>
    @endif

    @endsection
