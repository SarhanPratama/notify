<div class="modal fade" id="DestroyModal{{ $item->id }}" tabindex="-1"
    aria-labelledby="DestroyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title font-weight-bold text-light"
                    id="DestroyModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close text-light" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin ingin menghapus bahan baku
                    <strong>"{{ $item->nama }}"</strong>?
                </p>
            </div>
            <form action="{{ route('bahan-baku.destroy', $item->id) }}"
                method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary btn-sm"
                        data-dismiss="modal">Close</button>
                    <button type="submit"
                        class="btn btn-outline-danger btn-sm">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
