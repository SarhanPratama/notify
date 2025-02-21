@include('layouts.link')
<div class="container d-flex flex-column justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 text-center" style="max-width: 500px; width: 100%;">
        <div class="card-body">
            <!-- Icon -->
            <div class="mb-4">
                <i class="bi bi-envelope-check-fill text-success" style="font-size: 3rem;"></i>
            </div>

            <!-- Title -->
            <h2 class="mb-3">Verify Your Email</h2>

            <!-- Message -->
            <p class="text-muted mb-3">
                We've sent a verification email to your registered email address. Please check your inbox and click the verification link to activate your account.
            </p>

            <!-- Resend Email Form -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-outline-primary w-100">Resend Verification Email</button>
            </form>

        </div>
    </div>
</div>
