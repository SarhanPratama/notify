@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary py-3 d-flex align-items-center">
                        <a href="{{ route('kas.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fa fa-arrow-left"></i>
                        </a>
                        <h5 class="mb-0 ms-3 text-light">Form Input Cash Flow</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('kas.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm" name="tanggal" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Keterangan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="keterangan" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nominal <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" name="nominal" step="0.01" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Jenis Transaksi <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" name="jenis_transaksi" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="debit">Debit (Masuk)</option>
                                    <option value="kredit">Kredit (Keluar)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Sumber Dana <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" name="sumber_dana" required>
                                    <option value="">Pilih Sumber</option>
                                    <option value="kas seroo">Kas Seroo</option>
                                    <option value="kas rekening">Kas Rekening</option>
                                    <option value="dana peminjaman">Dana Peminjaman</option>
                                    <option value="piutang">Piutang</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Keperluan (Opsional)</label>
                                <textarea class="form-control form-control-sm" name="keperluan" rows="2"></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-primary btn-sm">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
