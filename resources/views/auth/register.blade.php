@include('layouts.link')
@php
    $title = 'Register';
@endphp

<title>Seroo - {{ $title }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<main class="main-content">
    <section class="vh-100" style="background-color: #f8f9fa;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-4">
                    <div class="card text-light shadow-lg" style="background-color: #9c1515;">
                        <div class="card-header">
                            {{-- <img src="..." class="card-img-top" alt="..."> --}}
                            <img src="{{ asset('assets/img/logo/brand.png') }}" alt="Logo"
                                class="card-img-top mx-auto w-25">
                        </div>
                        <div class="card-body px-3">
                            <div class="text-center">

                                <label class="card-title" style="font-size: 20px; font-weight:bold;">
                                    <p>
                                        Register
                                    </p>
                                </label>

                            </div>
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="row text-sm">
                                    <div class="mb-3 font-weight-bold">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control form-control-sm" name="name"
                                            placeholder="Masukkan name" aria-label="name"
                                            aria-describedby="basic-addon1" required>
                                    </div>
                                    <!-- Email Input -->
                                    <div class="mb-3 font-weight-bold">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control form-control-sm" name="email"
                                            placeholder="Masukkan email" aria-label="email"
                                            aria-describedby="basic-addon1" required>
                                    </div>

                                    <!-- Password Input -->
                                    <div class="col-6 mb-3 font-weight-bold">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control form-control-sm" name="password"
                                            placeholder="Password" aria-label="password" aria-describedby="basic-addon1"
                                            required>
                                            <small class="form-text text-light">Pass minimal 8 karakter</small>
                                    </div>

                                    <!-- Confirm Password Input -->
                                    <div class="col-6 mb-4 font-weight-bold">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" class="form-control form-control-sm"
                                            name="password_confirmation" placeholder="Confirm Password"
                                            aria-label="password_confirmation" aria-describedby="basic-addon1" required>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-sm btn-outline-light w-100 mb-3 font-weight-bold">
                                    Register
                                </button>

                                <div class="card-footer text-center">
                                    <a href="{{ route('login') }}" class="text-light text-decoration-underline fw-bold">
                                        Login
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
