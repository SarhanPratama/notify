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
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-user text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Nama Outlet</small>
                                <span class="fw-bold">{{ $order->outlet->nama ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-user text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Penanggung Jawab</small>
                                <span>{{ $order->outlet->penanggung_jawab ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-phone text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Telepon</small>
                                <span>{{ $order->outlet->telepon ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-map-marker-alt text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Alamat</small>
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
                                @if ($order->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($order->status == 'approved_by_gudang')
                                    <span class="badge bg-success text-light">
                                        Disetujui Gudang
                                    </span>
                                @elseif($order->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-info text-dark">Completed</span>
                                @elseif($order->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif($order->status == 'rejected_by_gudang')
                                    <span class="badge bg-danger">
                                        Rejected Gudang
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                <i class="fas fa-credit-card text-info"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Metode Pembayaran</small>
                                <span>{{ $order->metode_pembayaran ?? '-' }}</span>
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
                            <span class="h5 mb-0 fw-bold text-primary">Rp
                                {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        @if ($order->status === 'pending' && (auth()->user()->hasRole('gudang')))
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <button type="button" class="btn btn-outline-success" data-toggle="modal"
                                    data-target="#approveModal">
                                    Setujui Pesanan
                                </button>
                                <button type="button" class="btn btn-outline-danger" data-toggle="modal"
                                    data-target="#rejectModal">
                                    Tolak Pesanan
                                </button>
                            </div>
                        @elseif (
                            $order->status === 'approved_by_gudang' &&
                                (auth()->user()->hasRole('keuangan') || auth()->user()->hasRole('owner')))
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
        </div>
    </div>
    @include('pesanan.modal-approve')
    @include('pesanan.modal-reject')
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentTypeSelect = document.getElementById('payment_type');
            if (paymentTypeSelect) {
                const kasbonFields = document.querySelectorAll('.kasbon-fields');

                function toggleKasbonFields() {
                    const isKasbon = paymentTypeSelect.value === 'kasbon';
                    kasbonFields.forEach(field => {
                        field.style.display = isKasbon ? 'block' : 'none';
                    });
                }

                // Initial check
                toggleKasbonFields();

                // Listen for changes
                paymentTypeSelect.addEventListener('change', toggleKasbonFields);
            }
        });
    </script>
@endsection
