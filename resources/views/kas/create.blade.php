@extends('layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/trix.css') }}">
@endsection

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid py-4">

        <a href="{{ route('transaksi.create') }}" type="button" class="btn btn-outline-secondary fw-bold mb-3">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary bg-gradient py-3 d-flex align-items-center justify-content-center">

                        <h5 class="mb-0 text-white fs-5 fw-bold">Form Input Kas</h5>
                    </div>



                    <div class="card-body p-4">
                        <form action="{{ route('transaksi.store') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="far fa-calendar-alt me-1 text-primary"></i> Tanggal <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                            name="tanggal" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="fas fa-money-bill-wave me-1 text-primary"></i> Jumlah <span
                                                class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number"
                                                class="form-control" name="jumlah"
                                                value="{{ old('jumlah') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="fas fa-exchange-alt me-1 text-primary"></i> Jenis Transaksi <span
                                                class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('jenis_transaksi') is-invalid @enderror"
                                            name="jenis_transaksi" required>
                                            <option value="" disabled selected>Pilih Jenis</option>
                                            <option value="debit"
                                                {{ old('jenis_transaksi') == 'debit' ? 'selected' : '' }}>
                                                <span class="text-success">Debit (Masuk)</span>
                                            </option>
                                            <option value="kredit"
                                                {{ old('jenis_transaksi') == 'kredit' ? 'selected' : '' }}>
                                                <span class="text-danger">Kredit (Keluar)</span>
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="fas fa-wallet me-1 text-primary"></i> Sumber Dana <span
                                                class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('id_sumber_dana') is-invalid @enderror"
                                            name="sumber_dana" required>
                                            <option value="" disabled selected>Pilih Sumber</option>
                                            @foreach ($sumberDana as $id => $nama)
                                                <option value="{{ $id }}">{{ $nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="fas fa-align-left me-1 text-primary"></i> Keperluan
                                        </label>
                                        <input name="deskripsi" type="hidden" id="deskripsi">
                                        <trix-editor input="deskripsi" class="form-control"
                                            style="min-height: 150px;"></trix-editor>
                                        <small class="text-muted">Silakan masukkan detail keperluan atau informasi
                                            tambahan</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-outline-primary px-4">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer bg-light py-3 text-center text-muted">
                        <small>Pastikan data yang dimasukkan sudah benar sebelum menyimpan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/trix.js') }}"></script>
@endsection
