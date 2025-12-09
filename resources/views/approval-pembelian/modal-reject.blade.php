    <!-- Modal Tolak -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">
                        Konfirmasi Penolakan
                    </h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('approval-pembelian.destroy', $detailPembelian->id) }}" method="POST">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <p class="mb-0">Apakah Anda yakin ingin menolak transaksi pembelian ini?</p>
                            <p class="text-muted small">Kode: <strong>{{ $detailPembelian->nobukti }}</strong></p>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Alasan Penolakan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3"
                                placeholder="Jelaskan alasan penolakan..." required></textarea>
                            <small class="text-muted">Alasan penolakan wajib diisi untuk dokumentasi</small>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-outline-danger">
                            Ya, Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
