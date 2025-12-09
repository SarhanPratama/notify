@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        @include('layouts.breadcrumbs')
        <div class="row text-sm">
            <!-- Profil Card -->
            <div class="col-md-4 mb-3">
                <div class="card shadow-lg h-100">
                    <div class="card-body text-center p-4">
                        <div class="d-flex flex-column align-items-center mb-4 gap-2">
                            <div>
                                <img src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('assets/img/boy.png') }}"
                                    class="rounded-circle img-thumbnail"
                                    style="width: 150px; height: 150px; object-fit: cover;" alt="Foto Profil">
                            </div>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $user->name }} </h4>
                        <p class="text-muted mb-2">{{ $user->email }}</p>
                        <!-- Added Role and Branch information -->
                        <p class="text-muted mb-3"><i class="bi bi-clock me-1"></i>Terakhir diperbarui:
                            {{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-white py-3">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold btn-outline-primary" id="info-tab"
                                    data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab"
                                    aria-controls="info" aria-selected="true">
                                    <i class="fa fa-user fs-6 me-2" aria-hidden="true"></i>Informasi Profile
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">
                            <!-- Informasi Profil Tab -->
                            <div class="tab-pane fade show active" id="info" role="tabpanel"
                                aria-labelledby="info-tab">
                                <div class="row fw-bold">
                                    <div class="col-lg-12 col-md-3 mb-3 col-sm-6 col-12">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text color"> <i class="fa fa-user text-light fs-6"
                                                    aria-hidden="true"></i></span>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ $user->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-3 col-sm-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text color input-group-sm"><i
                                                    class="fa fa-envelope text-light fw-6" aria-hidden="true"></i></span>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ $user->email }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-6 col-sm-6 mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text color input-group-sm"><i
                                                    class="fa fa-shield text-light fs-6" aria-hidden="true"></i></span>
                                            <input type="text" class="form-control" id="role" name="role"
                                                value="{{ $user->roles->first()->name ?? '-' }}" readonly>
                                        </div>
                                        <div class="form-text">Informasi Profile hanya dapat diubah oleh administrator</div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#changepasswordModal">
                                            Ubah Password
                                        </button>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#deleteAccountModal">
                                            Hapus Akun
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('profile.ubah-password')
    @include('profile.hapus-akun')

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Script -->
    <script>
        // Script untuk toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    </script>
@endsection
