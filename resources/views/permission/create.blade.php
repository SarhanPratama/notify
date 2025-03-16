<div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #6777ef">
          <h1 class="modal-title fs-6 font-weight-bold text-light" id="permissionModalLabel">Form Tambah</h1>
          <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('permission.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body text-sm fw-bold">
                <div class="mb-3">
                    <label for="cabang">Permission <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-sm"  placeholder="Masukkan permission">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-outline-primary btn-sm">Save</button>
            </div>
        </form>
      </div>
    </div>
  </div>
