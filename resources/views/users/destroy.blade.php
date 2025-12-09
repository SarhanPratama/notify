<div class="modal fade" id="usersDestroyModal{{ $item->id }}" tabindex="-1"
    aria-labelledby="usersDestroyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h6 class="modal-title text-light font-weight-bold"
                    id="usersDestroyModalLabel">Konfirmasi Hapus</h6>
                <button type="button" class="close text-light" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('users.destroy', $item->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Apakah anda yakin ingin menghapus users
                        <strong>"{{ $item->name }}"</strong>?
                    </p>

                    <div class="my-3">
                        <label for="password" class="form-label">Masukkan password
                            user <span class="text-danger">*</span></label>
                        <input type="password" class="form-control form-control-sm"
                            id="password" name="password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-outline-primary btn-sm"
                            data-dismiss="modal">Close</button>
                        <button type="submit"
                            class="btn btn-outline-danger btn-sm">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
