@extends('layouts.master')

@section('content')
    <div class="container-fluid py-4">
        @include('layouts.breadcrumbs')
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-lg">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-warning">
                        {{-- Changed to bg-warning --}}
                        <h6 class="m-0 font-weight-bold text-sm">
                            Edit User
                        </h6>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-light"
                            title="Kembali ke Daftar User">
                            Kembali
                        </a>
                    </div>

                    <div class="card-body p-4">
                        {{-- Ensure $user variable is passed to this view from the controller --}}
                        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <h5 class="mb-3 text-maron-heading">Informasi Pribadi</h5>
                                    <div class="form-row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="name" class="form-label">Nama Lengkap <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap"
                                                required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="email" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ old('email', $user->email) }}"
                                                placeholder="cth: karyawan@email.com" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="password" class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Masukkan password">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" placeholder="Masukkan konfirmasi password"
                                            >
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="id_roles" class="form-label">Role <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control select2-single" id="id_roles" name="id_roles"
                                                data-placeholder="-- Pilih Role --" required>
                                                <option></option>
                                                @foreach ($roles as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ old('id_roles', $userRoleId) == $id ? 'selected' : '' }}>
                                                        {{ ucwords($name) }}
                                                    </option>
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
                                    <button type="submit" class="btn btn-warning custom-update-btn"> {{-- Changed to btn-warning --}}
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
