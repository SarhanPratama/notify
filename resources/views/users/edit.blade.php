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
      <!-- Form Tambah Karyawan -->
      <div class="card shadow-lg">
        <div class="card-header text-white d-flex flex-row align-items-center justify-content-between" style="background-color: #6777ef">
            {{-- <h6 class="m-0 font-weight-bold">Form Tambah Karyawan</h6> --}}
            <a href="{{ route('users.index')}}" class="btn btn-sm btn-outline-light">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Menambahkan metode PUT untuk pembaruan -->
                <div class="row g-3 text-sm ">

                    <div class="col-lg-4 col-md-6">
                        <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control form-control-sm" name="name" value="{{ $user->name }}" placeholder="Masukkan nama karyawan" required>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                        <label for="usia" class="form-label fw-bold">Usia</label>
                        <input type="number" class="form-control form-control-sm" name="usia" value="{{ $user->Karyawan->usia}}" placeholder="Masukkan usia" required>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-6 col-6 ">
                        <label for="telepon" class="form-label fw-bold">Telepon</label>
                        <input type="text" class="form-control form-control-sm" name="telepon" value="{{ $user->Karyawan->telepon }}" placeholder="Masukkan telepon" required>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                        <label for="jabatan" class="form-label fw-bold">Jabatan</label>
                        <select class="form-select form-select-sm" name="id_roles" required>
                            @foreach ($roles as $id => $name)
                            <option value="{{ $id }}" {{ $user->Karyawan->id_roles == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                        <label for="cabang" class="form-label fw-bold">Cabang</label>
                        <select class="form-select form-select-sm" name="id_cabang" required>
                            <option selected disabled>Pilih Cabang</option>
                            @foreach ($cabang as $id => $nama)
                            <option value="{{ $id }}" {{ $user->Karyawan->id_cabang == $id ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-4">
                        <label for="alamat" class="form-label fw-bold">Alamat</label>
                        <textarea class="form-control form-control-sm" name="alamat" id="alamat" rows="1">{{ $user->Karyawan->alamat }}</textarea>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-sm">
                    <label for="foto" class="form-label fw-bold">Foto</label>
                    <div class="input-group">
                        <input type="file" name="foto" class="form-control form-control-sm" id="fotoInput" onchange="previewImage(event)">
                        <label class="input-group-text text-sm">Upload</label>
                    </div>
                </div>

                <div class="col-12 text-center mt-2 text-sm">
                    <label class="form-label fw-bold">Preview Foto</label>
                    <div class="preview-container d-flex justify-content-center">
                        <img id="fotoPreview" src="{{ $user->Karyawan->foto ? asset('uploads/karyawan/' . $user->Karyawan->foto) : '' }}" class="img-thumbnail preview-img m-auto" alt="Preview Foto">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-outline-primary btn-sm">Save</button>
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
