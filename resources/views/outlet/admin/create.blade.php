
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-maron text-white">
                <h6 class="modal-title font-weight-bold" id="exampleModalLabel">Form Tambah</h6>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('cabang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Form Input (Kiri) -->
                        <div class="col-lg-8">
                            <div class="row g-3 text-sm">
                                <div class="col-md-6">
                                    <label for="cabang" class="form-label fw-bold">Kode <span class="text-danger">*</span></label>
                                    <input type="text" name="kode" class="form-control form-control-sm" placeholder="Masukkan kode" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Nama Cabang -->
                                <div class="col-md-6">
                                    <label for="cabang" class="form-label fw-bold">Nama Cabang <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control form-control-sm" placeholder="Masukkan nama cabang" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Telepon -->
                                <div class="col-md-6">
                                    <label for="telepon" class="form-label fw-bold">Telepon <span class="text-danger">*</span></label>
                                    <input type="text" name="telepon" class="form-control form-control-sm" placeholder="Masukkan telepon" required>
                                </div>

                                <!-- Alamat -->
                                <div class="col-12">
                                    <label for="alamat" class="form-label fw-bold">Alamat <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-control-sm" name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat" required></textarea>
                                </div>

                                <!-- Upload Foto -->
                                <div class="mb-3">
                                    <label for="foto" class="form-label fw-bold">Upload Foto</label>
                                    <div class="input-group input-group-sm">
                                        <input type="file" name="foto" class="form-control" id="fotoInput" onchange="previewImage(event)">
                                        <label class="input-group-text">Pilih File</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Foto (Kanan) -->
                        <div class="col-lg-4">
                            <div class="sticky-top" style="top: 20px;">
                                <!-- Preview Foto -->
                                <div class="text-center">
                                    <label class="form-label fw-bold">Preview Foto</label>
                                    <div class="preview-container">
                                        <img id="fotoPreview" src="" class="img-thumbnail preview-img m-auto">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close</button>
                    <button type="reset" class="btn btn-outline-warning btn-sm me-2">Reset</button>
                    <button type="submit" class="btn btn-outline-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('fotoPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
