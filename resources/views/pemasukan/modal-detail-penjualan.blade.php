<!-- Modal Detail -->
<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->id }}"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-maron text-white border-0">
                <div>
                    <h5 class="modal-title fw-bold mb-1" id="detailModalLabel{{ $item->id }}">
                        Detail Penjualan
                    </h5>
                </div>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <!-- Status Badge -->
                <div class="mb-4 text-center">
                    @if ($item->status_keuangan === 'approved')
                        <span class="badge bg-success px-4 py-2 fs-6">
                            Transaksi Selesai
                        </span>
                    @elseif($item->status_gudang === 'approved')
                        <span class="badge bg-warning px-4 py-2 fs-6">
                            Menunggu Validasi
                        </span>
                    @endif
                </div>

                <!-- Info Cards -->
                <div class="row g-3 mb-4">
                    <!-- Card Supplier -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div>
                                        <small class="text-muted d-block mb-1">Supplier</small>
                                        <h6 class="mb-0 fw-bold">{{ $item->outlet->nama ?? '-' }}</h6>
                                    </div>
                                </div>
                                @if($item->outlet)
                                    <div class="border-top pt-2">
                                        <small class="text-muted d-block">
                                            {{ $item->outlet->telepon ?? '-' }}
                                        </small>
                                        <small class="text-muted d-block mt-1">
                                            {{ $item->outlet->alamat ?? '-' }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Info Transaksi -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div>
                                        <small class="text-muted d-block mb-1">Tanggal Transaksi</small>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}</h6>
                                    </div>
                                </div>
                                <div class="border-top pt-2">
                                    <small class="text-muted d-block">
                                        Nobukti
                                        <h6 class="mb-0 fw-bold">{{ $item->nobukti }}</h6>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Detail Produk -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-bold">
                            Detail Produk
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Bahan Baku</th>
                                    <th class="text-center" width="15%">Kuantitas</th>
                                    <th class="text-end" width="18%">Harga Satuan</th>
                                    <th class="text-end" width="18%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($item->mutasi ?? [] as $index => $detail)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="fw-medium">{{ $detail->bahanBaku->nama ?? 'Produk Dihapus' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger fw-medium px-3 py-2">
                                                {{ $detail->quantity }} {{ $detail->bahanBaku->satuan->nama ?? '' }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-medium text-success">
                                            Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            <span class="text-primary fw-bold text-success">
                                                Rp {{ number_format($detail->sub_total, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                            <p class="text-muted mb-0">Tidak ada produk dalam pembelian ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">
                                        Total
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        Rp {{ number_format($item->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Total dan Catatan -->
                <div class="row g-3">
                    <!-- Catatan -->
                    <div class="col-lg-12">
                        @if($item->catatan)
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-sticky-note me-2 text-warning"></i>Catatan
                                    </h6>
                                    <p class="text-muted mb-0 small">
                                        {!! $item->catatan !!}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
