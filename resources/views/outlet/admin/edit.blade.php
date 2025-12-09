<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-warning text-white">
                <h6 class="modal-title font-weight-bold" id="editModalLabel">Form Edit</h6>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('outlet.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <!-- Form Input (Kiri) -->
                        <div class="col-lg-8">
                            <div class="row g-3 text-sm">
                                <div class="col-md-6">
                                    <label for="kode" class="form-label fw-bold">Kode
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="kode" class="form-control form-control-sm"
                                        value="{{ $item->kode }}" placeholder="Masukkan kode" required>
                                    @error('kode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Nama Outlet -->
                                <div class="col-md-6">
                                    <label for="nama" class="form-label fw-bold">Nama Outlet
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control form-control-sm"
                                        value="{{ $item->nama }}" placeholder="Masukkan nama outlet" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="penanggung_jawab" class="form-label fw-bold">Penanggung
                                        Jawab<span class="text-danger"> *</span></label>
                                    <input type="text" name="penanggung_jawab" class="form-control form-control-sm"
                                        value="{{ $item->penanggung_jawab }}" placeholder="Masukkan penanggung jawab" required>
                                    @error('penanggung_jawab')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Telepon -->
                                <div class="col-md-6">
                                    <label for="telepon" class="form-label fw-bold">Telepon
                                        <span class="text-danger">*</span></label>
                                    <input type="text" name="telepon" class="form-control form-control-sm"
                                        value="{{ $item->telepon }}" placeholder="Masukkan telepon" required>
                                </div>


                                <!-- Alamat -->
                                <div class="col-12">
                                    <label for="alamat" class="form-label fw-bold">Alamat
                                        <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-control-sm" name="alamat" id="alamat" rows="3"
                                        placeholder="Masukkan alamat" required>{{ $item->alamat }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label for="lokasi" class="form-label fw-bold">Url
                                        Lokasi<span class="text-danger"> *</span></label>
                                    <input type="text" name="lokasi" id="lokasi"
                                        class="form-control form-control-sm" value="{{ $item->lokasi }}"
                                        placeholder="Masukkan url lokasi" required>
                                </div>

                                <!-- Upload Foto -->
                                <div class="mb-3">
                                    <label for="foto" class="form-label fw-bold">Upload
                                        Foto</label>
                                    <div class="input-group input-group-sm">
                                        <input type="file" name="foto" class="form-control">
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
                                        @if ($item->foto)
                                            <img id="fotoPreview{{ $item->id }}"
                                                src="{{ asset('storage/' . $item->foto) }}"
                                                class="img-thumbnail preview-img m-auto">
                                        @else
                                            <img id="fotoPreview{{ $item->id }}" src=""
                                                class="img-thumbnail preview-img m-auto">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-warning btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
