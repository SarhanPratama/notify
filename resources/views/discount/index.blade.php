@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        @include('layouts.breadcrumbs')

        <div class="card shadow-lg ">
            <div class="card-header bg-maron text-white py-3">
                <h4 class="mb-0">
                    <i class="fas fa-tag me-2"></i>Tambah Diskon Baru
                </h4>
            </div>

            <div class="card-body text-sm">
                <form action="" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <!-- Minimal Qty -->
                    <div class="mb-4">
                        <label for="minimal_qty" class="form-label fw-bold">
                            Minimal Quantity
                            <span class="text-danger">*</span>
                            <small class="text-muted">(Jumlah minimal pembelian)</small>
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-maron text-white">
                                <i class="fas fa-cubes"></i>
                            </span>
                            <input type="number"
                                   name="minimal_qty"
                                   id="minimal_qty"
                                   value="{{ old('minimal_qty') }}"
                                   class="form-control form-control-lg"
                                   min="1"
                                   required>
                        </div>
                        @error('minimal_qty')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="form-label fw-bold">
                            Status Diskon
                            <span class="text-danger">*</span>
                        </label>
                        <select name="status"
                                id="status"
                                class="form-select form-select-sm"
                                required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                <i class="fas fa-check-circle me-2"></i>Aktif
                            </option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                <i class="fas fa-times-circle me-2"></i>Tidak Aktif
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date Range -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="tanggal_mulai" class="form-label fw-bold">
                                Tanggal Mulai
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-maron text-white">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                                <input type="date"
                                       name="tanggal_mulai"
                                       id="tanggal_mulai"
                                       value="{{ old('tanggal_mulai') }}"
                                       class="form-control form-control-lg"
                                       required>
                            </div>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_selesai" class="form-label fw-bold">
                                Tanggal Selesai
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-maron text-white">
                                    <i class="far fa-calendar-check"></i>
                                </span>
                                <input type="date"
                                       name="tanggal_selesai"
                                       id="tanggal_selesai"
                                       value="{{ old('tanggal_selesai') }}"
                                       class="form-control form-control-lg"
                                       required>
                            </div>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="fas fa-save me-2"></i>Simpan Diskon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 15px;
            border: none;
        }

        .form-control-lg, .form-select-lg {
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #3b82f6, #2563eb);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }
    </style>

    <script>
        // Validasi tanggal
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('tanggal_mulai');
            const endDate = document.getElementById('tanggal_selesai');

            startDate.addEventListener('change', function() {
                endDate.min = this.value;
            });

            endDate.addEventListener('change', function() {
                if (this.value < startDate.value) {
                    this.setCustomValidity('Tanggal selesai tidak boleh sebelum tanggal mulai');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    </script>
@endsection
