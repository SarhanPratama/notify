                <!-- Detail Modal -->
                <div class="modal fade" id="detailModal{{ $piutang->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-maron text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-file-invoice me-2"></i>Detail Tagihan
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Invoice Info -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6 class="text-muted small">Nomor Bukti</h6>
                                        <p class="fw-bold">{{ $piutang->nobukti }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted small">Status</h6>
                                        <p>
                                            @if ($piutang->status == 'lunas')
                                                <span class="badge bg-success">Lunas</span>
                                            @elseif($piutang->status == 'dibayar_sebagian')
                                                <span class="badge bg-warning text-dark">Dibayar Sebagian</span>
                                            @else
                                                <span class="badge bg-danger">Belum Dibayar</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted small">Tanggal Pesanan</h6>
                                        <p>{{ \Carbon\Carbon::parse($piutang->penjualan->tanggal)->format('d F Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted small">Jatuh Tempo</h6>
                                        <p class="{{ $isJatuhTempo ? 'text-danger fw-bold' : '' }}">
                                            {{ \Carbon\Carbon::parse($piutang->jatuh_tempo)->format('d F Y') }}
                                            @if ($isJatuhTempo)
                                                <span class="badge bg-danger ms-2">Terlambat</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Items List -->
                                <h6 class="mb-3">
                                    <i class="fas fa-box me-2"></i>Daftar Item
                                </h6>
                                <div class="table-responsive mb-4">
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
                                            @foreach ($piutang->penjualan->mutasi as $item)
                                                <tr>
                                                    <td>{{ $item->bahanBaku->nama }}</td>
                                                    <td class="text-center">{{ $item->qty }}
                                                        {{ $item->bahanBaku->satuan->nama ?? 'pcs' }}</td>
                                                    <td class="text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-end">Rp
                                                        {{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th colspan="3" class="text-end">Total</th>
                                                <th class="text-end">Rp
                                                    {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Payment History -->
                                @if ($piutang->pembayaran->count() > 0)
                                    <h6 class="mb-3">
                                        <i class="fas fa-history me-2"></i>Riwayat Pembayaran
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Jumlah</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($piutang->pembayaran as $pembayaran)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d M Y') }}
                                                        </td>
                                                        <td class="text-success fw-semibold">Rp
                                                            {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                                        <td>{{ $pembayaran->keterangan ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <th>Total Dibayar</th>
                                                    <th colspan="2" class="text-success fw-bold">Rp
                                                        {{ number_format($totalBayar, 0, ',', '.') }}</th>
                                                </tr>
                                                @if ($piutang->status != 'lunas')
                                                    <tr>
                                                        <th>Sisa Tagihan</th>
                                                        <th colspan="2" class="text-danger fw-bold">Rp
                                                            {{ number_format($sisaPiutang, 0, ',', '.') }}</th>
                                                    </tr>
                                                @endif
                                            </tfoot>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Belum ada pembayaran untuk tagihan ini
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                @if ($piutang->status != 'lunas')
                                    <button type="button" class="btn btn-success"
                                        onclick="bayarTagihan('{{ $piutang->nobukti }}', {{ $sisaPiutang }})">
                                        <i class="fas fa-money-bill-wave me-2"></i>Bayar Sekarang
                                    </button>
                                @endif
                                <button type="button" class="btn bg-maron text-white"
                                    onclick="downloadInvoice('{{ $piutang->nobukti }}')">
                                    <i class="fas fa-download me-2"></i>Download Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
