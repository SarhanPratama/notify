@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-light font-weight-bold" id="editModalLabel">
                    Form Edit
                </h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('bahan-baku.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body text-sm fw-bold">
                    <div class="row">
                        <div class="mb-3">
                            <label for="nama">Bahan Baku <span class="text-danger">*</span></label>
                            <input type="text" id="nama" name="nama" class="form-control form-control-sm"
                                value="{{ $item->nama }}" placeholder="Masukkan nama bahan baku">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="harga">Harga <span class="text-danger">*</span></label>
                            <input type="number" id="harga" name="harga" class="form-control form-control-sm"
                                value="{{ (int) $item->harga }}" placeholder="Masukkan harga">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 mb-3">
                            <label for="stok_awal">Stok Awal<span class="text-danger">*</span></label>
                            <input type="number" id="stok_awal" name="stok_awal" class="form-control form-control-sm"
                                value="{{ $item->stok_awal }}" placeholder="Masukkan stok awal">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12 mb-3">
                            <label for="stok_minimum">Stok Minimum <span class="text-danger">*</span></label>
                            <input type="number" id="stok_minimum" name="stok_minimum"
                                class="form-control form-control-sm" value="{{ $item->stok_minimum }}"
                                placeholder="Masukkan stok minimum">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="id_satuan">Satuan <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="id_satuan" name="id_satuan" required>
                                <option selected disabled>Pilih Satuan</option>
                                @foreach ($satuan as $id => $nama)
                                    <option value="{{ $id }}" {{ $item->id_satuan == $id ? 'selected' : '' }}>
                                        {{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 mb-3">
                            <label for="id_kategori" class="form-label">Kategori
                                <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="id_kategori" name="id_kategori" required>
                                <option selected disabled>Pilih Kategori</option>
                                @foreach ($kategori as $id => $nama)
                                    <option value="{{ $id }}"
                                        {{ $item->id_kategori == $id ? 'selected' : '' }}>
                                        {{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="foto{{ $item->id }}">Foto Bahan Baku</label>

                            <!-- Tampilkan gambar yang sudah ada -->
                            @if($item->foto && Storage::disk('public')->exists($item->foto))
                                <div class="mb-2">
                                    <label class="form-label">Foto Saat Ini:</label>
                                    <div class="text-center">
                                        <img src="{{ Storage::url($item->foto) }}"
                                             alt="{{ $item->nama }}"
                                             class="img-thumbnail"
                                             style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                    </div>
                                </div>
                            @endif

                            <input type="file"
                                   id="foto{{ $item->id }}"
                                   name="foto"
                                   class="form-control form-control-sm"
                                   accept="image/*"
                                   onchange="previewEditImage(event, '{{ $item->id }}')">
                            <small class="text-muted">Format yang didukung: JPG, JPEG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</small>

                            <!-- Preview Container -->
                            <div id="editPreviewContainer{{ $item->id }}" class="mt-3" style="display: none;">
                                <label class="form-label">Preview Foto Baru:</label>
                                <div class="text-center">
                                    <img id="editImagePreview{{ $item->id }}"
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
                    <button type="button" class="btn btn-outline-primary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-outline-warning btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
