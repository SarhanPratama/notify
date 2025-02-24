<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #6777ef">
          <h1 class="modal-title fs-6 text-light font-weight-bold" id="exampleModalLabel">Form input cabang</h1>
          <i class="bi bi-x-lg btn btn-outline-danger btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
          {{-- <button type="button" class="btn-close btn-outline-primary" data-bs-dismiss="modal" aria-label="Close"></button> --}}
        </div>
        <form action="{{ route('cabang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body text-sm">
                <div class="mb-3">
                    <label for="cabang">Cabang</label>
                    <input type="text" name="nama" class="form-control form-control-sm"  placeholder="Masukkan nama cabang">
                    @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="telepon">Telepon</label>
                    <input type="text" name="telepon" class="form-control form-control-sm"  placeholder="Masukkan nama telepon">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Alamat</label>
                    <textarea class="form-control form-control-sm" name="alamat" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="formFileSm" class="form-label">Foto</label>
                    <input class="form-control form-control-sm" name="foto" id="formFileSm" type="file">
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
