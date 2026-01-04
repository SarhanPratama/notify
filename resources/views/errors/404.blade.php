@extends('layouts.master')

@php $title = '404 Not Found'; @endphp

@section('title', '404 Not Found')

@section('content')
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <div class="card shadow-sm border-0 py-5 mb-3"  style="height: 80vh">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center h-100">
                        <h1 class="display-1 text-maron fw-bold">404</h1>
                        <h2 class="h4 mb-3">Halaman Tidak Ditemukan</h2>
                        <p class="text-muted mb-4">Sepertinya halaman yang Anda cari tidak ada atau telah dipindahkan. Periksa kembali URL atau kembali ke halaman sebelumnya.</p>

                        <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-danger">Ke Dashboard</a>
                        </div>

                        {{-- <div class="mt-4 text-muted small">Jika masalah berlanjut, hubungi administrator.</div> --}}
                    </div>
                </div>
            </div>
        </div>

@endsection
