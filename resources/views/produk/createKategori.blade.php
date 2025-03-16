<div class="modal fade" id="kategoriModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelmerek"
aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header bg-maron">
            <h5 class="modal-title text-light fw-bold" id="exampleModalLabelmerek">Form Tambah Kategori</h5>
            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf
            <div class="modal-body text-sm">
                    <div>
                        <label class="fw-bold">Kategori <span
                            class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="nama" placeholder="Input Kategori">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
            </div>
        </form>
    </div>
</div>
</div>
