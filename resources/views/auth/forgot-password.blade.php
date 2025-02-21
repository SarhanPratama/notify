@include('layouts.link')
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<main class="main-content">
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card" style="border-radius: 1rem;">
                        <div class="card-body p-5">
                            <!-- Logo/Header Section -->
                            <div class="text-center mb-5">
                                <h1 class="card-title font-weight-bolder fs-1">Forgot Password</h1>
                                {{-- <p class="text-light">Enter your email to reset your password</p> --}}
                            </div>

                            <!-- Status Messages -->
                            @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            <!-- Password Reset Form -->
                            <form action="{{ route('password.email') }}" method="POST">
                                @csrf

                                <div class="mb-5 font-weight-bold">

                                    <input type="email" class="form-control" name="email"
                                           placeholder="Enter your registered email"
                                           aria-label="email"
                                           required
                                           autofocus>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-outline-primary w-100 mb-3">
                                    Send Password Reset Link
                                </button>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
