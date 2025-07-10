<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deletePermanenModal{{ $item->id }}" tabindex="-1" aria-labelledby="deletePermanenModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-maron">
                <h5 class="modal-title text-light" id="deletePermanenModalLabel{{ $item->id }}">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus permanen data pembelian dengan kode <strong>{{ $item->nobukti}}</strong>?
            </div>
            <div class="modal-footer">
                <!-- Tombol Batal -->
                <button type="button" class="btn btn-sm btn-outline-primary" data-dismiss="modal">Batal</button>
                <!-- Tombol Hapus -->
                <form action="{{ route('pembelian.forceDelete', $item->id ) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
