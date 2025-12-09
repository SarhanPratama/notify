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
                <form action="{{ route('approval-pembelian.update', $detailPembelian->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            {{-- <i class="fas fa-question-circle fa-3x text-success mb-3"></i> --}}
                            <p class="mb-0">Apakah Anda yakin ingin menyetujui transaksi pembelian ini?</p>
                            <p class="text-muted small">Kode: <strong>{{ $detailPembelian->nobukti }}</strong></p>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="approval_note" class="form-label">Catatan Persetujuan (Opsional)</label>
                            <textarea class="form-control" id="approval_note" name="approval_note" rows="3"
                                placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        </div> --}}
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
