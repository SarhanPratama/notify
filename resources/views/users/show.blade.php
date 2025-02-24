@extends('layouts.master')
@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Detail Karyawan</h4>
        </div>
        <div class="card-body">
            <!-- Foto Karyawan -->
            <div class="d-flex flex-column align-items-center  mb-4">
                <div>

                    <img src="{{ asset('uploads/karyawan/' . $user->karyawan->foto) }}"
                         class="rounded-circle img-thumbnail shadow-sm"
                         style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div>
                    <h4 class="mt-3 mb-1">{{ $user->name }}</h4>
                    <span class="badge bg-primary">{{ $user->roles->first()->name }}</span>
                </div>
            </div>

            <!-- Informasi Karyawan -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-hover text-sm">
                            <tbody>
                                <tr>
                                    <th>
                                        <i class="fas fa-birthday-cake me-2"></i>Usia
                                    </th>
                                    <td>{{ $user->karyawan->usia }} Tahun</td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-phone me-2"></i>Telepon
                                    </th>
                                    <td>{{ $user->karyawan->telepon }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-building me-2"></i>Cabang
                                    </th>
                                    <td>{{ $user->karyawan->cabang->nama }}</td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fas fa-map-marker-alt me-2"></i>Alamat
                                    </th>
                                    <td>{{ $user->karyawan->alamat }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4 ">
                <a href="{{ route('users.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
