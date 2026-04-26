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

        <!-- Summary Cards (diperbaiki sesuai schema) -->
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
                                <h6 class="text-muted mb-1 small">Menunggu Approval Gudang</h6>
                                <h5 class="mb-0 fw-bold text-center text-warning">
                                    {{ $orders->where('status_gudang', 'pending')->count() }}</h5>
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
                                <h6 class="text-muted mb-1 small">Disetujui Gudang</h6>
                                <h5 class="mb-0 fw-bold text-center text-success">
                                    {{ $orders->where('status_gudang', 'approved')->where('status_keuangan', 'pending')->count() }}
                                </h5>
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
                                <h6 class="text-muted mb-1 small">Disetujui Keuangan</h6>
                                <h5 class="mb-0 fw-bold text-center text-info">
                                    {{ $orders->where('status_gudang', 'approved')->where('status_keuangan', 'approved')->where('status_pembayaran', 'piutang')->count() }}
                                </h5>
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
                                <h6 class="text-muted mb-1 small">Lunas</h6>
                                <h5 class="mb-0 fw-bold text-center text-primary">
                                    {{ $orders->where('status_pembayaran', 'lunas')->count() }}</h5>
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
            <div class="card border-0 shadow-sm mb-4">
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
                                        <td class="align-middle">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="fw-semibold text-nowrap">
                                            {{ $order->nobukti }}
                                            @if ($order->mutasi && $order->mutasi->count() > 0)
                                                <div class="small text-muted">Item: {{ $order->mutasi->count() }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <p class="text-nowrap">
                                                {{ $order->created_at->translatedFormat('l, d/m/Y') }}
                                            </p>
                                            <div class="small text-muted">{{ $order->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td class="align-middle">
                                            @if ($order->status_gudang === 'approved' && $order->status_keuangan === 'pending')
                                                <span class="badge bg-success">Gudang: Approved</span>
                                            @elseif($order->status_gudang === 'rejected')
                                                <span class="badge bg-danger">Gudang: Rejected</span>
                                            @elseif($order->status_gudang === 'pending')
                                                <span class="badge bg-warning">Gudang: Pending</span>
                                            @elseif (
                                                $order->status_keuangan === 'approved' &&
                                                    $order->status_gudang === 'approved' &&
                                                    $order->status_pembayaran !== 'lunas')
                                                <span class="badge bg-success">Keuangan: Approved</span>
                                            @elseif($order->status_keuangan === 'rejected')
                                                <span class="badge bg-danger">Keuangan: Rejected</span>
                                            @elseif($order->status_keuangan === 'pending')
                                                <span class="badge bg-warning">Keuangan: Pending</span>
                                            @elseif(
                                                $order->status_pembayaran === 'lunas' &&
                                                    $order->status_keuangan === 'approved' &&
                                                    $order->status_gudang === 'approved')
                                                <span class="badge bg-success">Pembayaran: Lunas</span>
                                            @else
                                                <span class="badge bg-secondary">Cancelled</span>
                                            @endif
                                        </td>
                                        <td class="text-end text-nowrap align-middle">Rp
                                            {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td class="text-center text-nowrap align-middle">
                                            <a href="{{ route('outlet.pesanan.detail', [$token, $order->id]) }}"
                                                class="btn btn-outline-primary btn-sm" title="Lihat Detail">
                                                <i class="fa fa-list-alt" aria-hidden="true"></i> </a>
                                            @if ($order->status_gudang === 'pending' && $order->status_keuangan === 'pending')
                                                <form action="{{ route('outlet.pesanan.cancel', [$token, $order->id]) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm ms-1"
                                                        title="Cancel Pesanan"
                                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
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
