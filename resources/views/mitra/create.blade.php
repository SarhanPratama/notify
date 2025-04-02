@include('layouts.link')
@php
    $title = 'Pendaftaran Kemitraan';
@endphp

<title>Seroo - {{ $title }}</title>
<style>
    .text-maron {
        color: #861414;
    }

    .bg-maron {
        background-color: #861414;
    }

    .form-control:focus {
        border-color: #861414;
        box-shadow: 0 0 0 0.2rem rgba(134, 20, 20, 0.25);
    }

    /* .form-check-input:checked {
        background-color: #861414;
        border-color: #861414;
    } */

    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(134, 20, 20, 0.25);
    }
</style>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<main class="main-content">
    <section class="vh-100" style="background-color: #f8f9fa;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-4">
                    <div class="card text-light shadow-lg bg-maron">
                        <div class="card-header">
                            <img src="{{ asset('assets/img/logo/brand.png') }}" alt="Logo"
                                class="card-img-top mx-auto w-25">
                        </div>
                        <div class="card-body px-3">
                            <div class="text-center mb-3">
                                <label class="card-title" style="font-size: 20px; font-weight:bold;">
                                    <p>
                                        Formulir Pendaftaran Kemitraan
                                    </p>
                                </label>
                                @if ($errors->any())
                                    <div class="alert alert-light alert-dismissible fade show d-flex align-content-center"
                                        role="alert">
                                        <i class="bi bi-exclamation-circle me-2 text-maron"></i>
                                        <p class="text-sm">Terdapat kesalahan pada formulir, harap periksa kembali.</p>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <form action="{{ route('mitra.store') }}" method="POST" class="text-sm" enctype="multipart/form-data" id="registrationForm">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 font-weight-bold">
                                        <label for="nama" class="form-label">Nama Lengkap ( sesuai KTP ) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="nama"
                                            placeholder="Nama lengkap sesuai KTP" aria-label="nama" required>
                                        <div class="invalid-feedback">Harap isi nama lengkap sesuai KTP.</div>
                                    </div>

                                    <div class="mb-3 font-weight-bold">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control form-control-sm" name="email"
                                            placeholder="Email" aria-label="email" required>
                                        <div class="invalid-feedback">Harap isi email yang valid.</div>
                                    </div>

                                    <div class="col-6 mb-4 font-weight-bold">
                                        <label for="nik" class="form-label">NIK <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="nik"
                                            placeholder="Nomor Induk Kependudukan (NIK)" aria-label="nik" required>
                                        <div class="invalid-feedback">Harap isi NIK yang valid.</div>
                                    </div>

                                    <div class="col-6 mb-4 font-weight-bold">
                                        <label for="telepon" class="form-label">No. Hp <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="telepon"
                                            placeholder="Nomor telepon" aria-label="telepon" required>
                                        <div class="invalid-feedback">Harap isi nomor telepon yang valid.</div>
                                    </div>

                                    <div class="mb-4 font-weight-bold">
                                        <label for="alamat" class="form-label">Alamat Lengkap ( Domisili ) <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="alamat" id="alamat" rows="2" placeholder="Alamat domisili" required></textarea>
                                        <div class="invalid-feedback">Harap isi alamat domisili.</div>
                                    </div>

                                    <div class="mb-3 font-weight-bold">
                                        <label for="bisnis_sebelumnya" class="form-label">Apakah Anda sudah pernah menjalankan bisnis sebelumnya? <span
                                                class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="bisnis_sebelumnya" value="ya" required>
                                            <label class="form-check-label" for="ya">
                                                Ya
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="bisnis_sebelumnya" value="tidak" required>
                                            <label class="form-check-label" for="tidak">
                                                Tidak
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3 font-weight-bold">
                                        <label for="memiliki_bisnis" class="form-label">Apakah saat ini Anda memiliki bisnis lain? <span
                                                class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="memiliki_bisnis" value="ya" required>
                                            <label class="form-check-label" for="ya">
                                                Ya
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="memiliki_bisnis" value="tidak" required>
                                            <label class="form-check-label" for="tidak">
                                                Tidak
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-4 font-weight-bold">
                                        <label for="alasan" class="form-label">Alasan ingin bergabung menjadi mitra <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="alasan" id="alasan" rows="2" placeholder="Alasan bergabung" required></textarea>
                                        <div class="invalid-feedback">Harap isi alasan bergabung.</div>
                                    </div>

                                    <div class="mb-3 font-weight-bold">
                                        <label for="aturan_sop" class="form-label">Apakah Anda bersedia mengikuti aturan dan SOP dari Sero Group? <span
                                                class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="aturan_sop" value="ya" required>
                                            <label class="form-check-label" for="ya">
                                                Ya
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="aturan_sop" value="tidak" required>
                                            <label class="form-check-label" for="tidak">
                                                Tidak
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3 font-weight-bold">
                                        <label for="paket_usaha" class="form-label">Jenis Paket Usaha <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm" name="paket_usaha" id="paket_usaha" required>
                                            <option value="">Pilih Paket Usaha</option>
                                            <option value="ekonomis">Ekonomis</option>
                                            <option value="premium">Premium</option>
                                        </select>
                                        <div class="invalid-feedback">Harap pilih paket usaha.</div>
                                    </div>

                                    <div class="mb-4 font-weight-bold">
                                        <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran <span
                                                class="text-danger">*</span></label>
                                        <input type="file" class="form-control form-control-sm" name="bukti_pembayaran" required>
                                        <div class="invalid-feedback">Harap upload bukti pembayaran.</div>
                                    </div>

                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-sm btn-outline-light w-100 text-center mb-3">
                                    <strong>
                                        Kirim Formulir
                                    </strong>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@include('layouts.script')

<script>
    // Example of real-time validation using JavaScript
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
