<div class="modal fade" id="approvePenjualanModal{{ $item->id }}" tabindex="-1"
    aria-labelledby="approveModalLabel" aria-hidden="true">
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
            <form action="{{ route('pemasukan.approve-penjualan', $item->id) }}"
                method="POST">
                @csrf
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <p class="mb-2">Apakah Anda yakin ingin menyetujui transaksi penjualan ini?</p>
                        <p class="text-muted small mb-2">No. Bukti: <strong>{{ $item->nobukti }}</strong></p>
                        <p class="text-muted small mb-0">Outlet: <strong>{{ $item->outlet->nama }}</strong></p>
                        <p class="text-muted small mb-0">Nama: <strong>{{ $item->outlet->penanggung_jawab }}</strong></p>

                    </div>
                    <div class="alert alert-info mb-0 text-info" role="alert">
                        <small>
                            <strong>Catatan:</strong> Transaksi akan otomatis masuk ke <strong>Piutang</strong>
                            {{-- karena outlet akan membayar setelah barang diterima. --}}
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-success">
                        Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
