@extends('layouts.master')
@section('content')

@include('layouts.breadcrumbs')

<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header text-white bg-success">
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-light">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
        </div>
        <div class="card-body ">
            <!-- Foto Karyawan -->
            <div class="d-flex flex-column align-items-center mb-4 ">
                <div>

                    <img src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('assets/img/boy.png') }}"
                    class="rounded-circle img-thumbnail shadow-sm"
                    style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="text-center">
                    <h4 class="mt-3 mb-1 fw-bold">{{ $user->name }}</h4>
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
                                        <i class="fa fa-envelope me-2"></i>Email
                                    </th>
                                    <td>{{ $user->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fas fa-birthday-cake me-2"></i>Usia
                                    </th>
                                    <td>{{ \Carbon\Carbon::parse($user->tgl_lahir)->age ?? '-' }} Tahun </td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fa fa-calendar me-2"></i>Tanggal Lahir
                                    </th>
                                    <td>{{ $user->tgl_lahir ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fas fa-phone me-2"></i>Telepon
                                    </th>
                                    <td>{{ $user->telepon ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fas fa-building me-2"></i>Cabang
                                    </th>
                                    <td>{{ $user->cabang->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fas fa-map-marker-alt me-2"></i>Alamat
                                    </th>
                                    <td>{{ $user->alamat ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- <div class="text-center mt-4 ">
                <a href="{{ route('users.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div> --}}
        </div>
    </div>
</div>
@endsection
