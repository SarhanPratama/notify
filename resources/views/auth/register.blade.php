@include('layouts.link')
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<main class="main-content"
{{-- style="background-color: #de0000; --}}
">
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="card-body p-5">
                            <!-- Logo/Header Section -->
                            <div class="text-center mb-5">
                                <h1 class="card-title font-weight-bolder fs-2">Login</h1>
                            </div>

                            <!-- Error Messages -->
                            @if($errors->any())
                            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            <!-- Login Form -->
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="mb-3 font-weight-bold">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Name" aria-label="name" aria-describedby="basic-addon1" required>
                                </div>
                                <!-- Email Input -->
                                <div class="mb-3 font-weight-bold">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email" aria-label="email" aria-describedby="basic-addon1" required>
                                </div>

                                <!-- Password Input -->
                                <div class="mb-3 font-weight-bold">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Password" aria-label="password" aria-describedby="basic-addon1" required>
                                </div>

                                <!-- Confirm Password Input -->
                                <div class="mb-4 font-weight-bold">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" aria-label="password_confirmation" aria-describedby="basic-addon1" required>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-outline-primary w-100 mb-3 font-weight-bold">
                                    Register
                                </button>

                                <!-- Registration Link -->
                                <div class="text-center">
                                    <p class="">Already have an account?
                                        <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">
                                            Login
                                        </a>
                                    </p>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
