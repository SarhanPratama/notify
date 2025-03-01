@extends('layouts.master')

@section('content')

<style>
.preview-img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border: 3px solid #ddd;
    border-radius: 10px;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s;
}

.preview-img:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}
</style>

@include('layouts.breadcrumbs')

<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <!-- Form Tambah Produk -->
      <div class="card shadow-lg">
        <div class="card-header text-white d-flex flex-row align-items-center justify-content-between" style="background-color: #6777ef">
            <h6 class="m-0 font-weight-bold">Form Tambah Produk</h6>
            <a href="{{ route('produk.index')}}" class="btn btn-sm btn-outline-light">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
        </div>
        <div class="card-body">
          <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 text-sm">
                <!-- Kode Produk -->
                <div class="col-lg-4 col-md-6">
                    <label for="kode" class="form-label fw-bold">Kode Produk</label>
                    <input type="text" class="form-control form-control-sm" id="kode" name="kode" placeholder="Masukkan kode produk" required>
                </div>

                <!-- Nama Produk -->
                <div class="col-lg-4 col-md-6">
                    <label for="nama" class="form-label fw-bold">Nama Produk</label>
                    <input type="text" class="form-control form-control-sm" id="nama" name="nama" placeholder="Masukkan nama produk" required>
                </div>

                <!-- Stok -->
                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                    <label for="stok" class="form-label fw-bold">Stok</label>
                    <input type="number" class="form-control form-control-sm" id="stok" name="stok" placeholder="Masukkan jumlah stok" required>
                </div>

                <!-- Harga Modal -->
                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                    <label for="harga_modal" class="form-label fw-bold">Harga Modal</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control form-control-sm" id="harga_modal" name="harga_modal" placeholder="Masukkan harga modal" required>
                    </div>
                </div>

                <!-- Harga Jual -->
                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                    <label for="harga_jual" class="form-label fw-bold">Harga Jual</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control form-control-sm" id="harga_jual" name="harga_jual" placeholder="Masukkan harga jual" required>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                    <label for="status" class="form-label fw-bold">Status</label>
                    <select class="form-select form-select-sm" id="status" name="status" required>
                        <option selected disabled>Pilih Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <!-- Kategori -->
                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                    <label for="id_kategori" class="form-label fw-bold">Kategori</label>
                    <select class="form-select form-select-sm" id="id_kategori" name="id_kategori" required>
                        <option selected disabled>Pilih Kategori</option>
                        @foreach ($kategori as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Merek -->
                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                    <label for="id_merek" class="form-label fw-bold">Merek</label>
                    <select class="form-select form-select-sm" id="id_merek" name="id_merek" required>
                        <option selected disabled>Pilih Merek</option>
                        @foreach ($merek as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Deskripsi -->
                <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-4">
                    <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                    <textarea class="form-control form-control-sm" name="deskripsi" id="deskripsi" rows="2" placeholder="Masukkan deskripsi produk"></textarea>
                </div>
            </div>

            <!-- Upload Foto -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-sm mt-3">
                <label for="foto" class="form-label fw-bold">Foto Produk</label>
                <div class="input-group">
                    <input type="file" name="foto" class="form-control form-control-sm" id="fotoInput" onchange="previewImage(event)">
                    <label class="input-group-text text-sm">Upload</label>
                </div>
            </div>

            <!-- Preview Foto -->
            <div class="col-12 text-center mt-3 text-sm">
                <label class="form-label fw-bold">Preview Foto</label>
                <div class="preview-container d-flex justify-content-center">
                    <img id="fotoPreview" src="" class="img-thumbnail preview-img m-auto">
                </div>
                <p class="text-muted small mt-2">Unggah gambar untuk melihat pratinjau</p>
            </div>

            <!-- Submit Button -->
            <div class="d-flex justify-content-end mt-4">
                <button type="reset" class="btn btn-outline-secondary btn-sm me-2">Reset</button>
                <button type="submit" class="btn btn-outline-primary btn-sm">Simpan</button>
            </div>
          </form>
        </div>
      </div>
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

@endsection
