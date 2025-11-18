@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">

        <!-- ===== HEADER HALAMAN ===== -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-secondary fw-bold d-flex align-items-center gap-1">
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
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-user text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Nama Outlet</small>
                                <span class="fw-bold">{{ $order->cabang->nama ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-phone text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Telepon</small>
                                <span>{{ $order->cabang->telepon ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-map-marker-alt text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Alamat</small>
                                <span>{{ $order->cabang->alamat ?? '-' }}</span>
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
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-calendar-alt text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Tanggal</small>
                                <span>{{ \Carbon\Carbon::parse($order->created_at)->format('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-tag text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Kode</small>
                                <span class="fw-bold">{{ $order->nobukti }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-clock text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Status</small>
                                <span class="badge bg-{{ $order->status == 'pending' ? 'warning text-dark' : ($order->status == 'approved' ? 'success' : ($order->status == 'completed' ? 'info text-dark' : 'danger')) }}">{{ ucfirst($order->status) }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-calendar-check text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Tanggal Pengiriman</small>
                                <span>{{ $order->tanggal_pengiriman ? \Carbon\Carbon::parse($order->tanggal_pengiriman)->format('d F Y') : '-' }}</span>
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
                                    <span class="badge bg-secondary bg-opacity-25 text-dark fw-medium rounded-pill px-3 py-2">
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
                            {!! $order->catatan !!}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom Total & Aksi -->
            <div class="col-lg-5">
                <div class="card shadow-sm rounded-3 border-light">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 mb-0 fw-semibold">Total</span>
                            <span class="h5 mb-0 fw-bold text-primary">Rp {{ number_format($order->total, 2, ',', '.') }}</span>
                        </div>
                        @if($order->status === 'pending')
                            <hr>
                            <form action="{{ route('admin.pesanan.approve', $order->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Tipe Pembayaran</label>
                                    <select name="payment_type" class="form-select form-select-sm" required>
                                        <option value="tunai">Tunai (Lunas)</option>
                                        <option value="kasbon">Kasbon (Piutang)</option>
                                        <option value="partial">Partial (Sebagian bayar)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Jumlah Dibayar (opsional)</label>
                                    <input type="number" name="paid_amount" class="form-control form-control-sm" min="0" step="0.01" placeholder="Kosong = seluruhnya untuk tunai">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Sumber Dana (opsional)</label>
                                    <select name="id_sumber_dana" class="form-select form-select-sm">
                                        <option value="">-- Pilih Sumber Dana --</option>
                                        @foreach($sumberDana as $sd)
                                            <option value="{{ $sd->id }}">{{ $sd->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Jatuh Tempo (untuk kasbon)</label>
                                    <input type="date" name="jatuh_tempo" class="form-control form-control-sm">
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-outline-success">Approve & Proses</button>
                                </div>
                            </form>

                            <form action="{{ route('admin.pesanan.reject', $order->id) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">Tolak Pesanan</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
