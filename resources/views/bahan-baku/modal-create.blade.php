    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header bg-maron">
                    <h5 class="modal-title text-light font-weight-bold" id="createModalLabel">Form Tambah</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('bahan-baku.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-sm fw-bold">
                        <div class="row">
                            <div class="mb-3">
                                <label for="nama">Bahan Baku <span class="text-danger">*</span></label>
                                <input type="text" id="nama" name="nama" class="form-control form-control-sm"
                                    placeholder="Masukkan nama bahan baku">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="harga">Harga <span class="text-danger">*</span></label>
                                <input type="number" id="harga" name="harga" class="form-control form-control-sm"
                                    placeholder="Masukkan harga">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="stok_awal">Stok Awal<span class="text-danger">*</span></label>
                                <input type="number" id="stok_awal" name="stok_awal" class="form-control form-control-sm"
                                    placeholder="Masukkan stok">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="stok_minimum">Stok Minimum <span class="text-danger">*</span></label>
                                <input type="number" id="stok_minimum" name="stok_minimum" class="form-control form-control-sm"
                                    placeholder="Masukkan stok minimum">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 mb-3">
                                <label for="id_satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="id_satuan" name="id_satuan" required>
                                    <option selected disabled>Pilih Satuan</option>
                                    @foreach ($satuan as $id => $nama)
                                        <option value="{{ $id }}">{{ $nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-6 col-sm-12 col-12 mb-3 ">
                                <label for="id_kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="id_kategori" name="id_kategori" required>
                                    <option selected disabled>Pilih Kategori</option>
                                    @foreach ($kategori as $id => $nama)
                                        <option value="{{ $id }}">{{ $nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="foto">Foto Bahan Baku</label>
                                <input type="file"
                                       id="foto"
                                       name="foto"
                                       class="form-control form-control-sm"
                                       accept="image/*"
                                       onchange="previewCreateImage(event)">
                                <small class="text-muted">Format yang didukung: JPG, JPEG, PNG, GIF. Maksimal 2MB.</small>

                                <!-- Preview Container -->
                                <div id="createPreviewContainer" class="mt-3" style="display: none;">
                                    <label class="form-label">Preview:</label>
                                    <div class="text-center">
                                        <img id="createImagePreview"
                                             src=""
                                             alt="Preview"
                                             class="img-thumbnail"
                                             style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
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
