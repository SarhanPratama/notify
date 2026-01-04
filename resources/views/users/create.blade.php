@extends('layouts.master')

@section('content')

    @include('layouts.breadcrumbs')
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-lg">
                    <div
                        class="card-header py-3 text-white d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="m-0 font-weight-bold text-sm">
                            Tambah User
                        </h6>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-light"
                            title="Kembali ke Daftar User">
                            Kembali
                        </a>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div>
                                    <h5 class="mb-3" style="color: #8e1616;">Informasi Pribadi</h5>
                                    <div class="form-row">
                                        <div class="form-group col-lg-6">
                                            <label for="name" class="form-label">Nama Lengkap <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Masukkan nama lengkap" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="email" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="cth: karyawan@email.com" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="password" class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Masukkan password" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" placeholder="Masukkan konfirmasi password"
                                                required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="id_roles" class="form-label">Role <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control select2-single" id="id_roles" name="id_roles"
                                                data-placeholder="-- Pilih Role --" required>
                                                <option></option>
                                                @foreach ($roles as $id => $name)
                                                    <option value="{{ $id }}">{{ ucwords($name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4 pt-3 border-top">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="reset" class="btn btn-outline-secondary mr-2">
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-maron">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
