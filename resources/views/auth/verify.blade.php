@include('layouts.link')
@php
    $title = 'Verifikasi Email';
@endphp

<title>Seroo - {{ $title }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<main class="main-content">
    <section class="vh-100" style="background-color: #f8f9fa;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-4">
                    <div class="card shadow-lg" style="border-radius: 1rem; border: none; background-color: #9c1515;">
                        <!-- Card Header (Logo) -->
                        <div class="card-header text-center">
                            <img src="{{ asset('assets/img/logo/brand.png') }}" alt="Logo" class="img-fluid mx-auto" style="max-width: 100px;">
                        </div>
                        <!-- Card Body -->
                        <div class="card-body px-4">
                            <!-- Title -->
                            <div class="text-center">
                                    <label class="card-title text-light" style="font-size: 20px; font-weight:bold;">
                                        <p>
                                            Verifikasi Email
                                        </p>
                                    </label>
                            </div>
                        @if (session('status') === 'verification-link-sent')
                            <div class="text-light text-center">
                                Kami telah mengirimkan email verifikasi ke alamat email Anda. Silakan periksa inbox Anda dan klik tautan verifikasi untuk mengaktifkan akun Anda.
                            </div>
                        @else
                            <div class="text-center text-light my-4">
                                Silakan klik tombol di bawah ini untuk mengirim email verifikasi ke alamat email Anda.
                            </div>
                        <!-- Tombol untuk mengirim email verifikasi -->
                        <form action="{{ route('verification.send') }}" method="POST">
                            @csrf
                            <div class="text-center">
                                <button type="submit" class="btn btn-sm btn-outline-light">
                                    Kirim Email Verifikasi
                                </button>
                            </div>
                        </form>
                        @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
