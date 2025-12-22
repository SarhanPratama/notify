<!-- Delete Modal -->
<div class="modal fade" id="deletePemasukanModal{{ $item->id }}" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-maron text-white">
                <h6 class="modal-title">Konfirmasi Hapus</h6>
                <button type="button" class="close text-light"
                    data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data pemasukan dengan No Bukti
                    "<strong>{{ $item->nobukti }}</strong>"?</p>
            </div>
            <form action="{{ route('pemasukan.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
