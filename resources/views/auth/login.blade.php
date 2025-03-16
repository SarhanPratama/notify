@include('layouts.link')
@php
    $title = 'Login';
@endphp

<title>Seroo - {{ $title }}</title>
<style>
    .text-maron {
        color: #861414;
    }

    .bg-maron {
        background-color: #861414;
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
                            {{-- <img src="..." class="card-img-top" alt="..."> --}}
                            <img src="{{ asset('assets/img/logo/brand.png') }}" alt="Logo"
                                class="card-img-top mx-auto w-25">
                        </div>
                        <div class="card-body px-3">
                            <div class="text-center">

                                <label class="card-title" style="font-size: 20px; font-weight:bold;">
                                    <p>
                                        Login
                                    </p>
                                </label>
                                @if($errors->any())
                                <div class="alert alert-light alert-dismissible fade show d-flex align-content-center" role="alert">
                                    <i class="bi bi-exclamation-circle me-2 text-maron"></i>
                                        <p class="text-sm">Email atau Password tidak valid.</p>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                            @endif
                            </div>
                            <form action="{{ route('login') }}" method="POST" class="text-sm">
                                @csrf
                                <div class="mb-3 font-weight-bold">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control form-control-sm" name="email"
                                        placeholder="Email" aria-label="email" required>
                                </div>

                                <div class="mb-4 font-weight-bold">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control form-control-sm" name="password"
                                        placeholder="Password" aria-label="password" required>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-sm btn-outline-light w-100 text-center mb-3">
                                    <strong>
                                        Login
                                    </strong>
                                </button>

                                <div class="card-footer d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('register') }}"
                                            class="text-light text-decoration-underline fw-bold">
                                            Create Account
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('password.request') }}"
                                            class="text-decoration-underline text-light fw-bold">
                                            Forgot Password
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

@include('layouts.script')
