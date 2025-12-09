<!-- Modal Konfirmasi Hapus Data (Soft Delete) -->
<div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-maron">
                <h5 class="modal-title text-light" id="deleteModalLabel{{ $item->id }}">Konfirmasi Hapus Data</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin <strong>menghapus</strong> data pembelian dengan kode <strong>{{ $item->nobukti}}</strong>?
            </div>
            <div class="modal-footer">
                <!-- Tombol Batal -->
                <button type="button" class="btn btn-sm btn-outline-primary" data-dismiss="modal">Batal</button>
                <!-- Tombol Hapus (Soft Delete) -->
                <form action="{{ route('pembelian.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
