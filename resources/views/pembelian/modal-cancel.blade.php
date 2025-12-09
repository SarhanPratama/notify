<!-- Modal Konfirmasi Pembatalan Permintaan -->
<div class="modal fade" id="cancelModal{{ $item->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-maron">
                <h5 class="modal-title text-light" id="cancelModalLabel{{ $item->id }}">Konfirmasi Pembatalan Permintaan</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin <strong>membatalkan permintaan pembelian bahan baku</strong> dengan kode <strong>{{ $item->nobukti}}</strong>?
            </div>
            <div class="modal-footer">
                <!-- Tombol Batal -->
                <button type="button" class="btn btn-sm btn-outline-primary" data-dismiss="modal">Tutup</button>
                <!-- Tombol Batalkan -->
                <form action="{{ route('pembelian.cancel', $item->nobukti) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan Permintaan</button>
                </form>
            </div>
        </div>
    </div>
</div>
