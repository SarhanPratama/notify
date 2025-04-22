<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Enhanced Header -->
            <div class="modal-header bg-warning text-white py-3">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-invoice me-2"></i>Detail Pembelian #{{ $item->nobukti }}
                </h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4">
                <!-- Header Info Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-light py-3 border-0 d-flex align-items-center">
                                <i class="fas fa-truck me-2 text-warning"></i>
                                <h6 class="mb-0 fw-bold">Informasi Supplier</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-user text-warning"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Nama</small>
                                        <span class="fw-bold">{{ $item->supplier->nama }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-phone text-warning"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Telepon</small>
                                        <span>{{ $item->supplier->telepon }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-map-marker-alt text-warning"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Alamat</small>
                                        <span>{{ $item->supplier->alamat }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-light py-3 border-0 d-flex align-items-center">
                                <i class="fas fa-info-circle me-2 text-info"></i>
                                <h6 class="mb-0 fw-bold">Informasi Pembelian</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-calendar-alt text-info"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Tanggal</small>
                                        <span>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y, H:i') }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-tag text-info"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Kode</small>
                                        <span class="fw-bold">{{ $item->nobukti }}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-user-edit text-info"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Dibuat Oleh</small>
                                        <span>{{ $item->user->name ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product List -->
                <div class="card mb-4 rounded-4 shadow-sm border-0">
                    <div class="card-header bg-light py-3 border-0 d-flex align-items-center">
                        <i class="fas fa-boxes me-2 text-success"></i>
                        <h6 class="mb-0 fw-bold">Daftar Produk</h6>
                    </div>

                    <!-- Product List Header (Desktop) -->
                    <div class="d-none d-md-flex bg-light text-dark fw-medium p-3 border-bottom">
                        <div class="col-5">Produk</div>
                        <div class="col-2 text-center">Qty</div>
                        <div class="col-2 text-end">Harga</div>
                        <div class="col-3 text-end">Subtotal</div>
                    </div>

                    <!-- Product Items -->
                    <div class="products-container">
                        @foreach($item->detailPembelian as $detail)
                        <div class="product-item border-bottom p-3">
                            <!-- Mobile View -->
                            <div class="d-md-none">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="fw-medium">{{ $detail->bahanBaku->nama ?? 'N/A' }}</div>
                                    <span class="badge bg-primary rounded-pill px-3 py-1">
                                        {{ $detail->quantity }} {{ $detail->bahanBaku->satuan->nama ?? '' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">Harga Satuan</small>
                                    <span>Rp {{ number_format($detail->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">Subtotal</small>
                                    <span class="fw-bold">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Desktop View -->
                            <div class="d-none d-md-flex align-items-center">
                                <div class="col-5 d-flex align-items-center">
                                    <span class="fw-medium">{{ $detail->bahanBaku->nama ?? 'N/A' }}</span>
                                </div>
                                <div class="col-2 text-center">
                                    <span class="badge bg-primary rounded-pill px-3 py-1">
                                        {{ $detail->quantity }} {{ $detail->bahanBaku->satuan->nama ?? '' }}
                                    </span>
                                </div>
                                <div class="col-2 text-end">
                                    <span>Rp {{ number_format($detail->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="col-3 text-end">
                                    <span class="fw-bold">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-0 bg-primary text-white rounded-4 shadow">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-5 fw-bolder">Total Pembelian:</span>
                                    <span class="fs-5 fw-bolder">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 border-0 rounded-4 shadow-sm">
                            <div class="card-header bg-light py-3 border-0 d-flex align-items-center">
                                <i class="fas fa-sticky-note me-2 text-primary"></i>
                                <h6 class="mb-0 fw-bold">Catatan</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-0">{{ $item->catatan ?? 'Tidak ada catatan' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
                <button onclick="window.print()" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-print me-2"></i>Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .product-item:hover {
        background-color: rgba(0,0,0,0.02);
    }
    .badge {
        font-weight: 500;
        min-width: 60px;
    }
    .rounded-4 {
        border-radius: 12px;
    }
    .modal-header {
        border-bottom: none;
    }
    .card {
        transition: all 0.2s ease;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
