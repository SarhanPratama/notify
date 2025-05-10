<!DOCTYPE html>
<html lang="en">
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
                            <img src="{{ asset('assets/img/logo/brand.webp') }}" alt="Logo" width="100"
                                class="mx-auto">
                        </div>
                        <div class="card-body px-3">
                            <div class="text-center">

                                    <p class="card-title" style="font-size: 20px; font-weight:bold;">
                                        Register
                                    </p>

                            </div>
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="row text-sm">
                                    <div class="mb-3 font-weight-bold">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control form-control-sm" name="name" id="name"
                                            placeholder="Masukkan name" aria-label="name" autocomplete="name"
                                            required>
                                    </div>
                                    <!-- Email Input -->
                                    <div class="mb-3 font-weight-bold">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control form-control-sm" name="email" id="email"
                                            placeholder="Masukkan email" aria-label="email" autocomplete="email"
                                            required>
                                    </div>

                                    <!-- Password Input -->
                                    <div class="col-6 mb-3 font-weight-bold">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control form-control-sm" name="password" id="password"
                                            placeholder="Password" aria-label="password"
                                            autocomplete="current-password" required>
                                            <small class="form-text text-light">Pass minimal 8 karakter</small>
                                    </div>

                                    <!-- Confirm Password Input -->
                                    <div class="col-6 mb-4 font-weight-bold">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" class="form-control form-control-sm" id="password_confirmation"
                                            name="password_confirmation" placeholder="Confirm Password"
                                            aria-label="password_confirmation" required>
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
</html>
