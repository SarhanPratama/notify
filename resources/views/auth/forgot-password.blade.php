@include('layouts.link')
@php
    $title = 'Forgot Password';
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
                            <div class="text-center mb-4">
                                    <label class="card-title text-light" style="font-size: 20px; font-weight:bold;">
                                        <p>
                                            Forgot Password
                                        </p>
                                    </label>

                                <!-- Penjelasan di bawah judul -->
                                <p class="text-white mt-2" style="font-size: 14px;">
                                    Masukkan email Anda, dan link reset password akan dikirim ke email tersebut.
                                </p>
                            </div>

                            <!-- Error Messages -->
                            @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            <!-- Success Message -->
                            @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            <!-- Form -->
                            <form action="{{ route('password.email') }}" method="POST" class="text-sm">
                                @csrf
                                <div class="mb-4 fw-bold">
                                    <label for="email" class="form-label text-white">Email</label>
                                    <input type="email" class="form-control form-control-sm" name="email" placeholder="Masukkan email yang terdaftar" aria-label="email" required autofocus>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-sm btn-outline-light w-100 mb-3">
                                    <strong>
                                        Send Link
                                    </strong>
                                </button>

                                <div class="card-footer d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('login') }}" class="text-decoration-underline text-light fw-bold">
                                            Login
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('register') }}" class="text-light text-decoration-underline fw-bold">
                                            Create Account
                                        </a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
