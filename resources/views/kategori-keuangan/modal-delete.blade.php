<div class="modal fade" id="deleteKategoriKeuanganModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title">Hapus Kategori Keuangan</h6>
                <button type="button" class="close text-light" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('kategori-keuangan.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kategori <strong>{{ $item->nama }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
