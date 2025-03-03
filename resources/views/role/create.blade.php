<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header color">
          <h1 class="modal-title fs-6 font-weight-bold text-white" id="roleModalLabel">Form Input Role</h1>
          <i class="bi bi-x-lg btn btn-outline-light btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
          {{-- <button type="button" class="btn-close btn-outline-primary" data-bs-dismiss="modal" aria-label="Close"></button> --}}
        </div>
        <form action="{{ route('role.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body text-sm">
                <div class="mb-3">
                    <label for="cabang">Role</label>
                    <input type="text" name="name" class="form-control form-control-sm"  placeholder="Masukkan nama role">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-outline-primary btn-sm">Save</button>
            </div>
        </form>
      </div>
    </div>
  </div>
