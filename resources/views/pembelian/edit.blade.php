<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Enhanced Header -->
            <div class="modal-header bg-warning text-white py-3">
                <h5 class="modal-title fw-bold">
                    Detail Pembelian #{{ $item->kode }}
                </h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
            </div>

            <div class="modal-body p-4">
                <!-- Status Banner -->
                <div class="alert
                {{ $item->status == 'Completed' ? 'alert-success' : ($item->status == 'Pending' ? 'alert-warning' : 'alert-danger') }}
                d-flex align-items-center mb-4" role="alert">
                <i class="
                    {{ $item->status == 'Completed' ? 'fa fa-check-circle' :
                       ($item->status == 'Pending' ? 'fas fa-clock' : 'fa fa-times-circle') }}
                    me-2 fs-5
                    {{ $item->status == 'Completed' ? 'text-success' :
                       ($item->status == 'Pending' ? 'text-warning' : 'text-danger') }}"></i>
                <div class="text-dark text-sm">
                    Status Pembelian: <strong>{{ ucfirst($item->status) }}</strong> |
                    <span class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y, H:i') }}</span>
                </div>
            </div>

                <!-- Header Info Cards -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 text-sm">
                        <div class="card h-100 shadow-sm border-0 hover-shadow">
                            <div class="card-header bg-light py-3 border-0">
                                <h6 class="mb-0 fw-bold">
                                    Informasi Supplier
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex mb-3">
                                    <span class="text-muted flex-grow-1"><i class="fas fa-user me-2"></i>Nama:</span>
                                    <span class="fw-bold">{{ $item->supplier->nama }}</span>
                                </div>
                                <div class="d-flex mb-3">
                                    <span class="text-muted flex-grow-1"><i class="fas fa-phone me-2"></i>Telepon:</span>
                                    <span>{{ $item->supplier->telepon }}</span>
                                </div>
                                <div class="d-flex">
                                    <span class="text-muted flex-grow-1"><i class="fas fa-map-marker-alt me-2"></i>Alamat:</span>
                                    <span class="text-end">{{ $item->supplier->alamat }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 shadow-sm border-0 hover-shadow">
                            <div class="card-header bg-light py-3 border-0">
                                <h6 class="mb-0 fw-bold text-sm">
                                    Informasi Pembelian
                                </h6>
                            </div>
                            <div class="card-body text-sm">
                                <div class="d-flex mb-3">
                                    <span class="text-muted flex-grow-1"><i class="fas fa-calendar-alt me-2"></i>Tanggal:</span>
                                    <span>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y, H:i') }}</span>
                                </div>
                                <div class="d-flex mb-3">
                                    <span class="text-muted flex-grow-1"><i class="fas fa-tag me-2"></i>Kode:</span>
                                    <span class="fw-bold">{{ $item->kode }}</span>
                                </div>
                                <div class="d-flex">
                                    <span class="text-muted flex-grow-1"><i class="fas fa-user-edit me-2"></i>Dibuat Oleh:</span>
                                    <span>{{ $item->user->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product List -->
                <div class="card mb-4 rounded-4 shadow-sm border-0">
                    <div class="card-header bg-light py-3 border-0">
                        <h6 class="mb-0 fw-bold">
                            Daftar Produk
                        </h6>
                    </div>

                    <!-- Product Header -->
                    <div class="d-none d-md-flex bg-light text-dark fw-medium p-3 border-bottom">
                        <div class="col-5 ps-2">Nama Produk</div>
                        <div class="col-2 text-center">Kuantitas</div>
                        <div class="col-2 text-end text-nowrap">Harga Satuan</div>
                        <div class="col-3 text-end pe-2">Total</div>
                    </div>

                    <!-- Product Items -->
                    <div class="products-container">
                        @foreach($item->detailPembelian as $detail)
                        <div class="d-flex flex-wrap align-items-center p-3 border-bottom product-item">
                            <div class="col-md-5 col-12 mb-2 mb-md-0">
                                <span class="fw-medium">{{ $detail->bahanBaku->nama ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-2 col-6 text-md-center mb-2 mb-md-0">
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    {{ $detail->quantity }} {{ $detail->bahanBaku->satuan->nama ?? '' }}
                                </span>
                            </div>
                            <div class="col-md-2 col-6 text-end mb-2 mb-md-0">
                                <span class="price">Rp {{ number_format($detail->harga, 2, ',', '.') }}</span>
                            </div>
                            <div class="col-md-3 col-12 text-end">
                                <span class="fw-bold total-price">Rp {{ number_format($detail->total_harga, 2, ',', '.') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 bg-primary text-white rounded-4 shadow">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-5">Total Pembelian:</span>
                                    <span class="fs-4 fw-bold">Rp {{ number_format($item->total, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 border-0 rounded-4 shadow-sm">
                            <div class="card-header bg-light py-3 border-0">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-sticky-note me-2 text-primary"></i>Catatan
                                </h6>
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
