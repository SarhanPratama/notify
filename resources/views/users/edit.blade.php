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

    .form-control {
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
            <!-- Form Edit Karyawan -->
            <div class="card shadow-lg">
                <div class="card-header py-3 text-white d-flex flex-row align-items-center justify-content-between bg-warning">
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-light">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- Form Input (Kiri) -->
                            <div class="col-lg-8">
                                <div class="row g-3 fw-bold text-sm">
                                    <!-- Nama Lengkap -->
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                        <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="nama" name="name" value="{{ $user->name }}" placeholder="Masukkan nama" required>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                        <label class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm"
                                            name="email" placeholder="Masukkan email"  value="{{ $user->email}}" required>
                                    </div>

                                    {{-- <div class="col-6">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control form-control-sm" name="password"
                                            placeholder="Password" aria-label="password"
                                            required>

                                    </div> --}}


                                    {{-- <div class="col-6">
                                        <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control form-control-sm"
                                            name="password_confirmation" placeholder="Confirm Password"
                                            aria-label="password_confirmation" required>
                                    </div> --}}

                                    <!-- Tanggal Lahir -->
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control form-control-sm" id="tanggal_lahir" name="tgl_lahir" value="{{ $user->tgl_lahir }}" required>
                                    </div>

                                    <!-- Telepon -->
                                    {{-- <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                                        <label for="telepon" class="form-label">Telepon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="telepon" name="telepon" value="{{ $user->telepon }}" placeholder="Masukkan telepon" required>
                                    </div> --}}

                                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                                        <label for="telepon" class="form-label">Telepon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="telepon" name="telepon" pattern="^\+62\d{8,12}$" value="+62{{ $user->telepon }}" placeholder="Masukkan telepon" required>
                                    </div>

                                    <!-- Role -->
                                    <div class="col-lg-6 col-md-6">
                                        <label for="jabatan" class="form-label">Role <span class="text-danger">*</span></label>
                                        <select class="form-select select2-single" id="jabatan" name="id_roles" required>
                                            <option selected disabled>Pilih Role</option>
                                            @foreach ($roles as $id => $name)
                                            <option value="{{ $id }}" {{ in_array($name, $userRole->toArray()) ? 'selected' : '' }}>{{ $name }}</option>                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Cabang -->
                                    <div class="col-lg-6 col-md-6">
                                        <label for="cabang" class="form-label">Cabang <span class="text-danger">*</span></label>
                                        <select class="form-select select2-single" id="cabang" name="id_cabang" required>
                                            <option selected disabled>Pilih Cabang</option>
                                            @foreach ($cabang as $id => $nama)
                                            <option value="{{ $id }}" {{ $user->id_cabang == $id ? 'selected' : '' }}>{{ $nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Alamat -->
                                    <div class="col-lg-12">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <textarea class="form-control form-control-sm" name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat">{{ $user->alamat }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Foto dan Input Foto (Kanan) -->
                            <div class="col-lg-4">
                                <div class="sticky-top" style="top: 20px;">
                                    <!-- Preview Foto -->
                                    <div class="text-center mb-3 fw-bold text-sm">
                                        <label class="form-label">Preview Foto</label>
                                        <div class="preview-container">
                                            @if ($user->foto)
                                                <img id="fotoPreview" src="{{ asset('storage/' . $user->foto) }}" class="img-thumbnail preview-img m-auto">
                                            @else
                                                <img id="fotoPreview" src="" class="img-thumbnail preview-img m-auto">
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Input Foto -->
                                    <div class="text-center">
                                        <div class="input-group input-group-sm">
                                            <input type="file" name="foto" class="form-control" id="fotoInput" onchange="previewImage(event)">
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
                                <button type="submit" class="btn btn-sm btn-outline-warning">Update</button>
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

            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection
