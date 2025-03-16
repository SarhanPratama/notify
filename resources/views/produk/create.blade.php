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

        .preview-img:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .form-control {
            /* border-radius: 8px; */
            border: 1px solid #ddd;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: #8e1616;
            box-shadow: 0 0 5px rgba(142, 22, 22, 0.5);
        }

        .preview-container {
            background-color: #f8f9fa;
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 20px;
            transition: border-color 0.3s ease-in-out;
        }

        .preview-container:hover {
            border-color: #8e1616;
        }
    </style>

    @include('layouts.breadcrumbs')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- Form Tambah Produk -->
                <div class="card shadow-lg">
                    <div
                        class="card-header text-white d-flex flex-row align-items-center justify-content-between bg-maron py-3">
                        <a href="{{ route('produk.index') }}" class="btn btn-sm btn-outline-light">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        </a>
                        {{-- <h5 class="mb-0">Tambah Produk</h5> --}}
                    </div>

                    <div class="card-body">
                        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Form Input (Kiri) -->
                                <div class="col-lg-8">
                                    <div class="row g-3 text-sm">
                                        <!-- Kode Produk -->
                                        <div class="col-lg-6 col-md-6">
                                            <label for="kode" class="form-label fw-bold">Kode Produk <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm" id="kode"
                                                name="kode" value="{{ $kode }}"
                                                placeholder="Masukkan kode produk" readonly>
                                        </div>

                                        <!-- Nama Produk -->
                                        <div class="col-lg-6 col-md-6">
                                            <label for="nama" class="form-label fw-bold">Nama Produk <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm" id="nama"
                                                name="nama" placeholder="Masukkan nama produk" required>
                                        </div>

                                        <!-- Stok -->
                                        <div class="col-lg-4 col-md-6">
                                            <label for="stok" class="form-label fw-bold">Stok <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-sm" id="stok"
                                                name="stok" placeholder="Masukkan jumlah stok" min="1" required>
                                        </div>

                                        <!-- Harga Modal -->
                                        <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                                            <label for="harga_modal" class="form-label fw-bold">Harga Modal <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control form-control-sm" id="harga_modal"
                                                    name="harga_modal" placeholder="Masukkan harga modal" required>
                                            </div>
                                        </div>

                                        <!-- Harga Jual -->
                                        <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                                            <label for="harga_jual" class="form-label fw-bold">Harga Jual <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control" id="harga_jual" name="harga_jual"
                                                    placeholder="Masukkan harga jual" required>
                                            </div>
                                        </div>

                                        <!-- Kategori -->
                                        <div class="col-lg-6 col-md-6">
                                            <div class="d-flex justify-content-between">
                                                <label for="selectSingle" class="form-label fw-bold">Kategori <span
                                                        class="text-danger">*</span></label>
                                                <a class="text-danger text-decoration-underline" href="javascript:void(0);"
                                                    data-toggle="modal" data-target="#kategoriModal">Tambah Kategori</a>
                                            </div>
                                            <select class="select-single form-select form-select-sm " id="selectSingle"
                                                name="id_kategori" required>
                                                <option selected disabled>Pilih Kategori</option>
                                                @foreach ($kategori as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Merek -->
                                        <div class="col-lg-6 col-md-6">
                                            <div class="d-flex justify-content-between">
                                                <label for="select2Single" class="form-label fw-bold">Merek <span
                                                        class="text-danger">*</span></label>
                                                <a class="text-danger text-decoration-underline" href="javascript:void(0);"
                                                    data-toggle="modal" data-target="#merekModal">Tambah Merek</a>
                                            </div>
                                            <select class="select2-single form-select form-select-sm" id="select2Single"
                                                name="id_merek" required>
                                                <option selected disabled>Pilih Merek</option>
                                                @foreach ($merek as $id => $nama)
                                                    <option value="{{ $id }}">{{ $nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Deskripsi -->
                                        <div class="col-lg-12 mb-3">
                                            <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
                                            <textarea class="form-control form-control-sm" name="deskripsi" id="deskripsi" rows="3"
                                                placeholder="Masukkan deskripsi produk"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview Foto dan Input Foto (Kanan) -->
                                <div class="col-lg-4">
                                    <div class="sticky-top text-sm" style="top: 20px;">
                                        <!-- Preview Foto -->
                                        <div class="text-center mb-3">
                                            <label class="form-label fw-bold">Preview Foto</label>
                                            <div class="preview-container">
                                                <img id="fotoPreview" src=""
                                                    class="img-thumbnail preview-img m-auto">
                                            </div>
                                            {{-- <p class="text-muted small mt-2">Unggah gambar untuk melihat pratinjau</p> --}}
                                        </div>

                                        <!-- Input Foto -->
                                        <div class="text-center">
                                            {{-- <label for="foto" class="form-label fw-bold">Upload Foto</label> --}}
                                            <div class="input-group input-group-sm">
                                                <input type="file" name="foto" class="form-control" id="fotoInput"
                                                    onchange="previewImage(event)">
                                                <label class="input-group-text">Pilih File</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button type="reset" class="btn btn-sm btn-outline-danger me-2">Reset</button>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Simpan</button>
                                </div>
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

                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @include('produk.createMerek')
    @include('produk.createKategori')
@endsection
