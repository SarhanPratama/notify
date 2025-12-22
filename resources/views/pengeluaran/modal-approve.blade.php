<!-- Approve Modal -->
<div class="modal fade" id="approveModal{{ $item->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveModalLabel{{ $item->id }}">
                    Konfirmasi Persetujuan Pembayaran
                </h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pengeluaran.approve-pembelian', $item->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <p class="mb-0">Apakah Anda yakin ingin menyetujui pembayaran pembelian ini?</p>
                        <p class="text-muted small">Kode: <strong>{{ $item->nobukti }}</strong></p>
                    </div>
                    <div class="mb-2 text-center">
                        <label class="form-label">Total Pembayaran</label>
                        <input type="text" class="form-control text-center" value="Rp. {{ number_format($item->total, 0, ',', '.') }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="posisi_kas" class="form-label">Posisi Kas <span class="text-danger">*</span></label>
                        <select class="form-select" id="posisi_kas" name="posisi_kas" required>
                            <option value="" selected disabled>Pilih Posisi Kas</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Bank BSI">Bank BSI</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-success">Setujui & Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>
