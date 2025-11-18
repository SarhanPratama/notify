@include('layouts.link')

<title>Seroo - {{ $title ?? 'Error' }}</title>

@php
    // Normalize and provide safe defaults to avoid "Undefined variable" notices
    $type = $type ?? 'general';
    // Map possible error message keys used by controllers
    $error_message = $error_message ?? ($error ?? ($message ?? null));
    $outlet_name = $outlet_name ?? null;
    $maintenance_end = $maintenance_end ?? null;
@endphp

<style>
    /* Brand palette and quick utilities */
    .bg-maron { background-color: #9c1515; }
    .text-maron { color: #861414; }

    /* Align with layouts.outlet header appearance */
    .outlet-header {
        background: linear-gradient(135deg, #9c1515 0%, #7d1111 100%);
        color: white;
        padding: 20px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }
    .outlet-name { font-size: 1.35rem; font-weight: 700; }

    /* Error card visuals */
    .error-icon { font-size: 3.5rem; opacity: 0.85; }
    .error-code {
        font-size: 5.5rem;
        font-weight: 900;
        opacity: 0.08;
        position: absolute;
        top: -10px; right: -10px;
    }
    .card-error { position: relative; overflow: hidden; border: 0; }

    /* Buttons */
    .btn-primary-custom { background-color: #9c1515; border-color: #9c1515; }
    .btn-primary-custom:hover { background-color: #7d1111; border-color: #7d1111; }

    /* Navbar mimic from layouts.outlet */
    .navbar.bg-maron .nav-link { color: rgba(255,255,255,0.9) !important; padding: .5rem 1rem; }
    .navbar.bg-maron .nav-link:hover, .navbar.bg-maron .nav-link.active { color: #fff !important; background-color: rgba(255,255,255,0.1); border-radius: 5px; }

    @media (max-width: 768px) {
        .outlet-name { font-size: 1.1rem; }
    }
</style>

<body style="background-color: #f8f9fc;">

    <!-- Outlet-like Header (matches layouts.outlet style) -->
    <div class="outlet-header bg-maron">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start mb-3 mb-md-0">
                    <img src="{{ asset('assets/img/logo/brand.png') }}" width="90" alt="Seroo Logo" class="img-fluid m-auto">
                </div>
                <div class="col-md-8 text-center">
                    <div class="outlet-name">
                        {{ isset($outlet) ? strtoupper($outlet->nama) : 'Seroo - Sistem Manajemen Outlet' }}
                    </div>
                    <small class="d-block text-white-50 mt-1">Terjadi kesalahan dalam mengakses sistem</small>
                </div>
                <div class="col-md-2 text-center text-md-end">
                    @if(isset($totalPiutang))
                        <div class="text-white">
                            <small class="d-block text-white-50">Total Piutang</small>
                            <h6 class="mb-0">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation mimic (only when outlet context exists) -->
    @if(isset($outlet) && isset($outlet->barcode_token))
    <nav class="navbar navbar-expand-lg navbar-dark bg-maron sticky-top">
        <div class="container-fluid">
            <div class="navbar-collapse justify-content-center" id="navbarNav">
                <ul class="d-flex justify-content-between">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('outlet.belanja', ['token' => $outlet->barcode_token]) }}">Belanja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('outlet.pesanan', ['token' => $outlet->barcode_token]) }}">Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('outlet.tagihan', ['token' => $outlet->barcode_token]) }}">Piutang</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endif

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @if($type === 'invalid_token')
                <!-- Invalid Token Error -->
                <div class="card card-error shadow-sm border-0">
                    <div class="error-code text-danger">404</div>
                    <div class="card-body text-center p-5">
                        <div class="error-icon text-danger mb-4">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h2 class="mb-3 text-danger">QR Code Tidak Valid</h2>
                        <p class="lead text-muted mb-4">
                            QR Code yang Anda scan tidak valid atau sudah tidak aktif.
                        </p>
                        <div class="alert alert-warning text-start">
                            <h6 class="mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Kemungkinan Penyebab:</h6>
                            <ul class="mb-0">
                                <li>QR Code sudah kadaluarsa atau dinonaktifkan</li>
                                <li>Link QR Code rusak atau tidak lengkap</li>
                                <li>Outlet belum terdaftar dalam sistem</li>
                                <li>Akses tidak diizinkan untuk outlet ini</li>
                            </ul>
                        </div>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <button onclick="window.location.reload()" class="btn btn-primary-custom">
                                <i class="fas fa-sync-alt me-1"></i>
                                Coba Lagi
                            </button>
                            <button onclick="contactSupport()" class="btn btn-outline-primary">
                                <i class="fas fa-headset me-1"></i>
                                Hubungi Support
                            </button>
                        </div>
                    </div>
                </div>

                @elseif($type === 'outlet_inactive')
                <!-- Outlet Inactive Error -->
                <div class="card card-error shadow-sm border-0">
                    <div class="error-code text-warning">403</div>
                    <div class="card-body text-center p-5">
                        <div class="error-icon text-warning mb-4">
                            <i class="fas fa-store-slash"></i>
                        </div>
                        <h2 class="mb-3 text-warning">Outlet Tidak Aktif</h2>
                        <p class="lead text-muted mb-4">
                            Outlet <strong>{{ $outlet_name ?? 'ini' }}</strong> sedang tidak aktif dalam sistem.
                        </p>
                        <div class="alert alert-info text-start">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Informasi:</h6>
                            <ul class="mb-0">
                                <li>QR Code untuk outlet ini telah dinonaktifkan</li>
                                <li>Sistem pemesanan sementara tidak tersedia</li>
                                <li>Hubungi admin untuk mengaktifkan kembali</li>
                            </ul>
                        </div>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <button onclick="contactAdmin()" class="btn btn-primary-custom">
                                <i class="fas fa-phone me-1"></i>
                                Hubungi Admin
                            </button>
                            <button onclick="window.history.back()" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali
                            </button>
                        </div>
                    </div>
                </div>

                @elseif($type === 'system_error')
                <!-- System Error -->
                <div class="card card-error shadow-sm border-0">
                    <div class="error-code text-danger">500</div>
                    <div class="card-body text-center p-5">
                        <div class="error-icon text-danger mb-4">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h2 class="mb-3 text-danger">Terjadi Kesalahan Sistem</h2>
                        <p class="lead text-muted mb-4">
                            Mohon maaf, sistem sedang mengalami gangguan teknis.
                        </p>
                        <div class="alert alert-danger text-start">
                            <h6 class="mb-2"><i class="fas fa-bug me-2"></i>Detail Error:</h6>
                            <p class="mb-0">{{ $error_message ?? 'Internal server error occurred' }}</p>
                        </div>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <button onclick="window.location.reload()" class="btn btn-primary-custom">
                                <i class="fas fa-sync-alt me-1"></i>
                                Muat Ulang
                            </button>
                            <button onclick="reportError()" class="btn btn-outline-danger">
                                <i class="fas fa-bug me-1"></i>
                                Laporkan Error
                            </button>
                        </div>
                    </div>
                </div>

                @elseif($type === 'maintenance')
                <!-- Maintenance Mode -->
                <div class="card card-error shadow-sm border-0">
                    <div class="error-code text-info">503</div>
                    <div class="card-body text-center p-5">
                        <div class="error-icon text-info mb-4">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h2 class="mb-3 text-info">Sistem Sedang Maintenance</h2>
                        <p class="lead text-muted mb-4">
                            Kami sedang melakukan pemeliharaan sistem untuk meningkatkan pelayanan.
                        </p>
                        <div class="alert alert-info text-start">
                            <h6 class="mb-2"><i class="fas fa-clock me-2"></i>Informasi Maintenance:</h6>
                            <ul class="mb-0">
                                <li>Estimasi selesai: {{ $maintenance_end ?? 'Dalam beberapa jam' }}</li>
                                <li>Fitur yang terpengaruh: Semua fitur pemesanan</li>
                                <li>Status update: Cek kembali dalam 30 menit</li>
                            </ul>
                        </div>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <button onclick="checkStatus()" class="btn btn-primary-custom">
                                <i class="fas fa-sync-alt me-1"></i>
                                Cek Status
                            </button>
                            <button onclick="setReminder()" class="btn btn-outline-info">
                                <i class="fas fa-bell me-1"></i>
                                Set Reminder
                            </button>
                        </div>
                    </div>
                </div>

                @else
                <!-- General Error -->
                <div class="card card-error shadow-sm border-0">
                    <div class="error-code text-secondary">???</div>
                    <div class="card-body text-center p-5">
                        <div class="error-icon text-secondary mb-4">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h2 class="mb-3 text-secondary">Terjadi Kesalahan</h2>
                        <p class="lead text-muted mb-4">
                            Maaf, terjadi kesalahan yang tidak terduga.
                        </p>
                        <div class="alert alert-secondary text-start">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Apa yang bisa dilakukan:</h6>
                            <ul class="mb-0">
                                <li>Refresh halaman dan coba lagi</li>
                                <li>Pastikan koneksi internet stabil</li>
                                <li>Hubungi support jika masalah berlanjut</li>
                            </ul>
                        </div>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <button onclick="window.location.reload()" class="btn btn-primary-custom">
                                <i class="fas fa-sync-alt me-1"></i>
                                Coba Lagi
                            </button>
                            <button onclick="contactSupport()" class="btn btn-outline-primary">
                                <i class="fas fa-headset me-1"></i>
                                Hubungi Support
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Help Section -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-maron">
                            <i class="fas fa-question-circle me-2"></i>
                            Butuh Bantuan?
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <div class="p-3">
                                    <i class="fas fa-phone fa-2x text-success mb-2"></i>
                                    <h6>Telepon</h6>
                                    <p class="text-muted mb-0">
                                        <a href="tel:+62811234567" class="text-decoration-none">
                                            +62 811-234-567
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="p-3">
                                    <i class="fab fa-whatsapp fa-2x text-success mb-2"></i>
                                    <h6>WhatsApp</h6>
                                    <p class="text-muted mb-0">
                                        <a href="https://wa.me/62811234567" target="_blank" class="text-decoration-none">
                                            Chat Support
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="p-3">
                                    <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                                    <h6>Email</h6>
                                    <p class="text-muted mb-0">
                                        <a href="mailto:support@seroo.com" class="text-decoration-none">
                                            support@seroo.com
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.script')

    <script>
        function contactSupport() {
            Swal.fire({
                title: 'Hubungi Support',
                html: `
                    <div class="text-start">
                        <p>Pilih cara untuk menghubungi tim support:</p>
                        <div class="d-grid gap-2">
                            <a href="tel:+62811234567" class="btn btn-outline-success">
                                <i class="fas fa-phone me-2"></i>Telepon Langsung
                            </a>
                            <a href="https://wa.me/62811234567" target="_blank" class="btn btn-outline-success">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp Chat
                            </a>
                            <a href="mailto:support@seroo.com" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i>Kirim Email
                            </a>
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                showCloseButton: true,
                width: '400px'
            });
        }

        function contactAdmin() {
            Swal.fire({
                title: 'Hubungi Administrator',
                text: 'Silakan hubungi administrator untuk mengaktifkan kembali akses outlet Anda.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'WhatsApp Admin',
                cancelButtonText: 'Tutup',
                confirmButtonColor: '#25d366'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open('https://wa.me/62811234567?text=Halo, saya memerlukan bantuan untuk mengaktifkan kembali akses outlet.', '_blank');
                }
            });
        }

        function reportError() {
            Swal.fire({
                title: 'Laporkan Error',
                html: `
                    <div class="text-start">
                        <p>Bantu kami memperbaiki sistem dengan melaporkan error ini:</p>
                        <div class="form-group mb-3">
                            <label class="form-label">Deskripsi masalah:</label>
                            <textarea class="form-control" id="errorDescription" rows="3" placeholder="Jelaskan apa yang terjadi..."></textarea>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Kirim Laporan',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const description = document.getElementById('errorDescription').value;
                    if (!description) {
                        Swal.showValidationMessage('Mohon jelaskan masalah yang terjadi');
                        return false;
                    }
                    return description;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const message = encodeURIComponent(`Error Report:\n${result.value}\n\nURL: ${window.location.href}\nTime: ${new Date().toLocaleString()}`);
                    window.open(`https://wa.me/62811234567?text=${message}`, '_blank');

                    Swal.fire('Terima kasih!', 'Laporan error Anda telah dikirim.', 'success');
                }
            });
        }

        function checkStatus() {
            Swal.fire({
                title: 'Mengecek Status...',
                text: 'Memverifikasi status sistem',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            setTimeout(() => {
                window.location.reload();
            }, 3000);
        }

        function setReminder() {
            Swal.fire({
                title: 'Reminder Diaktifkan',
                text: 'Browser akan mengingatkan Anda untuk mengecek kembali dalam 30 menit.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });

            // Set reminder untuk 30 menit
            setTimeout(() => {
                if (Notification.permission === 'granted') {
                    new Notification('Seroo - Cek Status Sistem', {
                        body: 'Waktu untuk mengecek apakah maintenance sudah selesai.',
                        icon: '{{ asset("assets/img/logo/brand.png") }}'
                    });
                } else {
                    alert('Cek status sistem - Maintenance mungkin sudah selesai!');
                }
            }, 30 * 60 * 1000);

            // Request notification permission
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        }
    </script>

</body>
</html>
