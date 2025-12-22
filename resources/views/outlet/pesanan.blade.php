@extends('layouts.outlet')

@section('content')
    <div class="container-fluid mt-3">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 text-maron fs-4 fw-bold">
                        Pesanan
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
                                <h5 class="mb-0 fw-bold text-center text-warning">
                                    {{ $orders->where('status', 'pending')->count() }}</h5>
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
                                <h5 class="mb-0 fw-bold text-center text-success">
                                    {{ $orders->whereIn('status', ['approved', 'approved_by_gudang'])->count() }}</h5>
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
                                <h5 class="mb-0 fw-bold text-center text-info">
                                    {{ $orders->where('status', 'completed')->count() }}</h5>
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
                                <h5 class="mb-0 fw-bold text-center text-primary">{{ $orders->total() }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show text-success" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show text-danger" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

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
                                    <th class="text-nowrap">Status Transaksi</th>
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
                                            {{ $order->created_at->translatedFormat('l, d/m/Y') }}
                                            <div class="small text-muted">{{ $order->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td>
                                            @if ($order->status == 'pending')
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif($order->status == 'approved' || $order->status == 'approved_by_gudang')
                                                <span class="badge bg-success">Disetujui</span>
                                            @elseif($order->status == 'completed')
                                                <span class="badge bg-info">Selesai</span>
                                            @endif
                                        </td>
                                        <td class="text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('outlet.pesanan.detail', [$token, $order->id]) }}"
                                                class="btn btn-outline-primary btn-sm" title="Lihat Detail">
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
                    <p class="text-muted mb-4">Anda belum melakukan pemesanan apapun. Yuk mulai pesan bahan baku untuk
                        outlet
                        Anda!</p>
                    <a href="{{ route('outlet.belanja', $token) }}" class="btn btn-outline-danger">
                        Mulai Belanja
                    </a>
                </div>
            </div>
        @endif
    </div>

@endsection
