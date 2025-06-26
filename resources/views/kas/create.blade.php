@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary bg-gradient py-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('kas.index') }}" class="btn btn-light btn-sm rounded-circle shadow-sm me-3">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                            <h5 class="mb-0 text-white fw-bold">Form Input Cash Flow</h5>
                        </div>
                        <span class="badge bg-light text-primary">Keuangan</span>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('kas.store') }}" method="POST">
                            @csrf

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="far fa-calendar-alt me-1 text-primary"></i> Tanggal <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                            name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="fas fa-money-bill-wave me-1 text-primary"></i> Nominal <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                                name="nominal" step="0.01" value="{{ old('nominal') }}" required>
                                        </div>
                                        @error('nominal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="fas fa-file-alt me-1 text-primary"></i> Keterangan <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('keterangan') is-invalid @enderror"
                                            name="keterangan" value="{{ old('keterangan') }}" placeholder="Masukkan keterangan transaksi" required>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="fas fa-exchange-alt me-1 text-primary"></i> Jenis Transaksi <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('jenis_transaksi') is-invalid @enderror" name="jenis_transaksi" required>
                                            <option value="" disabled selected>Pilih Jenis</option>
                                            <option value="debit" {{ old('jenis_transaksi') == 'debit' ? 'selected' : '' }}>
                                                <span class="text-success">Debit (Masuk)</span>
                                            </option>
                                            <option value="kredit" {{ old('jenis_transaksi') == 'kredit' ? 'selected' : '' }}>
                                                <span class="text-danger">Kredit (Keluar)</span>
                                            </option>
                                        </select>
                                        @error('jenis_transaksi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="fas fa-wallet me-1 text-primary"></i> Sumber Dana <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('sumber_dana') is-invalid @enderror" name="sumber_dana" required>
                                            <option value="" disabled selected>Pilih Sumber</option>
                                            <option value="kas seroo" {{ old('sumber_dana') == 'kas seroo' ? 'selected' : '' }}>Kas Seroo</option>
                                            <option value="kas rekening" {{ old('sumber_dana') == 'kas rekening' ? 'selected' : '' }}>Kas Rekening</option>
                                            <option value="dana peminjaman" {{ old('sumber_dana') == 'dana peminjaman' ? 'selected' : '' }}>Dana Peminjaman</option>
                                            <option value="piutang" {{ old('sumber_dana') == 'piutang' ? 'selected' : '' }}>Piutang</option>
                                        </select>
                                        @error('sumber_dana')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="fas fa-align-left me-1 text-primary"></i> Keperluan (Opsional)
                                        </label>
                                        <textarea class="form-control @error('keperluan') is-invalid @enderror"
                                            name="keperluan" rows="3" placeholder="Deskripsi detail keperluan...">{{ old('keperluan') }}</textarea>
                                        @error('keperluan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Silakan masukkan detail keperluan atau informasi tambahan</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                                <a href="{{ route('kas.index') }}" class="btn btn-outline-secondary">
                                    Batal
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
