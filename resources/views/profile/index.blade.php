@extends('layouts.master')

@section('content')

@include('layouts.breadcrumbs')

<div class="container">
    <div class="row text-sm">
        <!-- Profil Card -->
        <div class="col-md-4 mb-4 ">
            <div class="card shadow-lg h-100">
                <div class="card-body text-center p-4">
                    <div class="d-flex flex-column align-items-center mb-4 gap-2">
                        <div>
                            <img src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('assets/img/boy.png') }}"
                                 class="rounded-circle img-thumbnail"
                                 style="width: 150px; height: 150px; object-fit: cover;" alt="Foto Profil">
                        </div>

                        <div class="position-absolute top-0 end-0 p-2 rounded-circle">
                            <form id="photo-form" action="{{ route('profile.foto', $user->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <!-- Ikon Kamera untuk Memicu Input File -->
                                <label for="fotoInput" style="cursor: pointer;">
                                    <i class="bi bi-camera mt-2 fs-4"></i>
                                </label>

                                <!-- Input File (Dihilangkan dari tampilan) -->
                                <input type="file" name="foto" class="d-none" id="fotoInput" onchange="submitForm()">
                            </form>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $user->name }} ({{ \Carbon\Carbon::parse($user->tgl_lahir)->age }})</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <!-- Added Role and Branch information -->
                    <p class="text-muted mb-3"><i class="bi bi-clock me-1"></i>Terakhir diperbarui: {{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y, H:i') }}</p>
                    <div class="d-grid">
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key me-2 fs-6"></i>Ubah Password
                        </button>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <div class="d-flex justify-content-around">
                        <div class="text-center">
                            <h5 class="mb-0">342</h5>
                            <small class="text-muted">Total Pengguna</small>
                        </div>
                        <div class="text-center">
                            <h5 class="mb-0">2 tahun</h5>
                            <small class="text-muted">Masa Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white py-3">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold btn-outline-primary" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true" >
                                <i class="fa fa-user fs-6 me-2" aria-hidden="true"></i>Informasi Profile
                            </button>
                        </li>
                        {{-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">
                                <i class="bi bi-gear me-2"></i>Pengaturan
                            </button>
                        </li> --}}
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <!-- Informasi Profil Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                            <form id="profile-form" action="{{ route('profile.update', $user->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row fw-bold">
                                    <div class="col-md-3 mb-3 col-sm-6 col-12">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text color"> <i class="fa fa-user text-light fs-6" aria-hidden="true"></i></span>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text color input-group-sm"><i class="fa fa-envelope text-light fw-6" aria-hidden="true" ></i></span>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text color"><i class="fa fa-calendar-o text-light fs-6" aria-hidden="true"></i></span>
                                            <input type="date" class="form-control" name="tgl_lahir" value="{{ $user->tgl_lahir ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-12 mb-3">
                                        <label for="phone" class="form-label">Nomor Telepon</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text color input-group-sm"><i class="fa fa-phone text-light fs-6" aria-hidden="true"></i></span>
                                            <input type="text" class="form-control" id="phone" name="telepon" value="{{ $user->telepon ?? '' }}">
                                        </div>
                                    </div>

                                <div class="col-md-6 col-sm-6 mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text color input-group-sm"><i class="fa fa-shield text-light fs-6" aria-hidden="true"></i></span>
                                        <input type="text" class="form-control" id="role" name="role" value="{{ $user->roles->first()->name ?? '-' }}" readonly>
                                    </div>
                                    <div class="form-text">Role hanya dapat diubah oleh administrator</div>
                                </div>

                                <div class="col-md-6 col-sm-6 mb-3">
                                    <label for="cabang" class="form-label">Cabang</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text color input-group-sm"><i class="fa fa-building text-light fs-6" aria-hidden="true"></i></span>
                                        <input type="text" class="form-control" id="cabang" name="cabang" value="{{ $user->cabang->nama ?? '-' }}" readonly>
                                    </div>
                                    <div class="form-text">Cabang hanya dapat diubah oleh administrator</div>
                                </div>

                                <div class="mb-4">
                                    <label for="bio" class="form-label">Alamat</label>
                                    <textarea class="form-control text-sm" id="bio" name="alamat" rows="3">{{ $user->alamat ?? '' }}</textarea>
                                </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="submit" class="btn btn-outline-primary btn-sm">
                                            Update
                                        </button>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                            Hapus Akun
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Pengaturan Tab -->
                        {{-- <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                            <form action="#" method="POST">
                                <div class="mb-3">
                                    <label class="form-label d-block">Notifikasi Email</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="emailLogin" name="notify_login" checked>
                                        <label class="form-check-label" for="emailLogin">Kirim notifikasi saat login</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label d-block">Tema Dashboard</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="theme" id="themeLight" value="light" checked>
                                        <label class="form-check-label" for="themeLight">Terang</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="theme" id="themeDark" value="dark">
                                        <label class="form-check-label" for="themeDark">Gelap</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="theme" id="themeAuto" value="auto">
                                        <label class="form-check-label" for="themeAuto">Otomatis (Sesuai Sistem)</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="language" class="form-label">Bahasa</label>
                                    <select class="form-select" id="language" name="language">
                                        <option value="id" selected>Indonesia</option>
                                        <option value="en">English</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>Simpan Pengaturan
                                    </button>
                                </div>
                            </form>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header color">
                <h5 class="modal-title text-light fw-bold" id="changePasswordModalLabel">Ubah Password</h5>
                <i class="bi bi-x-lg btn btn-outline-light btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
            </div>
            <form action="{{ route('user-password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body text-sm">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="input-group input-group-sm">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <button class="btn toggle-password" type="button" style="background-color: #6777ef"  data-target="current_password">
                                <i class="fa fa-eye text-light" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <div class="input-group input-group-sm">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn toggle-password" type="button" style="background-color: #6777ef"  data-target="password">
                                <i class="fa fa-eye text-light" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="form-text">Password minimal 8 karakter</div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <div class="input-group input-group-sm">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <button class="btn toggle-password" type="button" style="background-color: #6777ef"  data-target="password_confirmation">
                                <i class="fa fa-eye text-light" ></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteAccountModalLabel">Hapus Akun</h5>
                <i class="bi bi-x-lg btn btn-outline-light btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
            </div>
            <form action="{{ route('profile.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="my-3">
                        <label for="password" class="form-label">Masukkan Password Anda</label>
                        <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

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


    document.addEventListener("DOMContentLoaded", function () {
        let cameraIcon = document.querySelector("label[for='fotoInput']");
        let fileInput = document.getElementById("fotoInput");

    });

    function submitForm() {
        document.getElementById('photo-form').submit();
    }
    // document.getElementById('submit-btn').addEventListener('click', function() {
    //     // Trigger both form submissions
    //     document.getElementById('photo-form').submit();  // Submit the photo form
    //     document.getElementById('profile-form').submit();  // Submit the profile form
    // });

</script>
@endsection
