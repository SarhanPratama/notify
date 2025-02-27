@extends('layouts.master')
@section('content')

@include('layouts.breadcrumbs')

<div class="container">
    <div class="card shadow">
        <div class="card-header text-white text-center" style="background-color: #6777ef">
            <h4 class="mb-0 font-weight-bold">Detail Karyawan</h4>
        </div>
        <div class="card-body ">
            <!-- Foto Karyawan -->
            <div class="d-flex flex-column align-items-center mb-4 ">
                <div>

                    <img src="{{ asset('uploads/karyawan/' . ($user->karyawan->foto ?? 'profile.jpg'))  }}"
                         class="rounded-circle img-thumbnail shadow-sm"
                         style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="text-center">
                    <h4 class="mt-3 mb-1">{{ $user->name }}</h4>
                    <span class="badge bg-success">{{ $user->roles->first()->name }}</span>
                </div>
            </div>

            <!-- Informasi Karyawan -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-hover text-sm">
                            <tbody>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fas fa-birthday-cake me-2"></i>Usia
                                    </th>
                                    <td>{{ $user->karyawan->usia ?? '-' }} Tahun</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fas fa-phone me-2"></i>Telepon
                                    </th>
                                    <td>{{ $user->karyawan->telepon ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fas fa-building me-2"></i>Cabang
                                    </th>
                                    <td>{{ $user->karyawan->cabang->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fas fa-map-marker-alt me-2"></i>Alamat
                                    </th>
                                    <td>{{ $user->karyawan->alamat ?? '-' }}</td>
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
