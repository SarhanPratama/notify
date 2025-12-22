    <!-- Modal Setujui -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveModalLabel">
                       Konfirmasi Persetujuan
                    </h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.pesanan.approve', $detailPenjualan->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            {{-- <i class="fas fa-question-circle fa-3x text-success mb-3"></i> --}}
                            <p class="mb-0">Apakah Anda yakin ingin menyetujui pesanan outlet ini?</p>
                            <p class="text-muted small">Kode: <strong>{{ $detailPenjualan->nobukti }}</strong></p>
                        </div>

                        @if(auth()->user()->hasRole('gudang'))
                            <div class="alert alert-info small text-info">
                                Menyetujui ini berarti stok fisik telah diverifikasi dan siap dikirim.
                            </div>
                        @endif

                        @if(auth()->user()->hasRole('keuangan'))
                            <div class="alert alert-info small text-info">
                                Menyetujui ini akan mencatat piutang/pembayaran dan mengurangi stok sistem.
                            </div>

                            <div class="mb-3 text-left">
                                <label class="form-label small fw-bold">Metode Pembayaran <span
                                        class="text-danger">*</span></label>
                                <select name="payment_type" class="form-select form-select-sm" id="payment_type"
                                    required>
                                    <option value="tunai">Tunai (Lunas)</option>
                                    <option value="kasbon">Kasbon (Piutang)</option>
                                </select>
                            </div>
                            <div class="mb-3 kasbon-fields text-left" id="kasbon_fields" style="display: none;">
                                <label class="form-label small fw-bold">Jumlah Dibayar</label>
                                <input type="number" name="paid_amount" class="form-control form-control-sm"
                                    min="0" step="0.01">
                            </div>
                            <div class="mb-3 kasbon-fields text-left" id="jatuh_tempo_field" style="display: none;">
                                <label class="form-label small fw-bold">Jatuh Tempo (untuk kasbon)</label>
                                <input type="date" name="jatuh_tempo"  min="{{ date('Y-m-d') }}" class="form-control form-control-sm">
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-outline-success">
                            Ya, Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
