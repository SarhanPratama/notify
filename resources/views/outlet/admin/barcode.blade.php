@extends('layouts.master')

@section('title', 'Generate Barcode - ' . $cabang->nama)

@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-qrcode me-2"></i>
                    Barcode QR - {{ $cabang->nama }}
                </h1>
                <nav aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('outlet.index') }}">Outlet</a></li>
                        <li class="breadcrumb-item active">Barcode QR</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('outlet.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>
                    Kembali ke Daftar Cabang
                </a>
            </div>
        </div>

        <div class="row">
            <!-- QR Code Display -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-qrcode me-2"></i>
                            QR Code Outlet
                        </h6>
                        <div>
                            @if ($cabang->barcode_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body text-center">
                        @if ($cabang->barcode_token)
                            <div id="qrCodeContainer" class="mb-4">
                                <!-- QR Code will be generated here -->
                                <div class="d-flex justify-content-center">
                                    <div class="p-4 bg-white border rounded qr-container">
                                        {{-- {!! $qrCode !!} --}}
                                        <img src="{{ $qrCode }}" alt="QR Code" class="img-fluid" />
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>URL Akses:</strong><br>
                                    <code class="text-break">{{ $cabang->barcode_url }}</code>
                                </small>
                            </div>

                            <div class="text-muted small mb-3">
                                <i class="fas fa-calendar me-1"></i>
                                Dibuat:
                                {{ $cabang->barcode_generated_at ? $cabang->barcode_generated_at->format('d F Y, H:i') : '-' }}
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <button onclick="downloadQR()" class="btn btn-success btn-sm">
                                    <i class="fas fa-download me-1"></i>
                                    Download PNG
                                </button>

                                <button onclick="printQR()" class="btn btn-info btn-sm">
                                    <i class="fas fa-print me-1"></i>
                                    Print QR
                                </button>

                                <button onclick="copyURL()" class="btn btn-warning btn-sm">
                                    <i class="fas fa-copy me-1"></i>
                                    Copy URL
                                </button>
                            </div>
                        @else
                            <div class="py-5">
                                <i class="fas fa-qrcode fa-5x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum Ada QR Code</h5>
                                <p class="text-muted">QR Code belum dibuat untuk outlet ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Management Options -->
            <div class="col-lg-6">
                <!-- Outlet Info -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-store me-2"></i>
                            Informasi Outlet
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="35%" class="text-muted">Nama Outlet</td>
                                <td class="fw-bold">{{ $cabang->nama }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Alamat</td>
                                <td>{{ $cabang->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Telepon</td>
                                <td>{{ $cabang->telepon ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status QR</td>
                                <td>
                                    @if ($cabang->barcode_active)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-pause-circle me-1"></i>Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Token</td>
                                <td>
                                    @if ($cabang->barcode_token)
                                        <code class="small">{{ Str::limit($cabang->barcode_token, 20, '...') }}</code>
                                    @else
                                        <span class="text-muted">Belum dibuat</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-bolt me-2"></i>
                            Kelola QR Code
                        </h6>
                    </div>
                    <div class="card-body">

                        @if (!$cabang->barcode_token)
                            <!-- Generate New QR -->
                            <button class="btn btn-success w-100 mb-3" data-toggle="modal" data-target="#confirmGenerateModal">
                                <i class="fas fa-plus-circle me-2"></i>
                                Generate QR Code Baru
                            </button>
                        @else
                            <!-- Toggle Status -->
                            <button onclick="toggleStatus()"
                                class="btn btn-{{ $cabang->barcode_active ? 'warning' : 'success' }} w-100 mb-3">
                                <i class="fas fa-{{ $cabang->barcode_active ? 'pause' : 'play' }}-circle me-2"></i>
                                {{ $cabang->barcode_active ? 'Nonaktifkan' : 'Aktifkan' }} QR Code
                            </button>

                            <!-- Regenerate QR -->
                            <button onclick="regenerateBarcode()" class="btn btn-info w-100 mb-3">
                                <i class="fas fa-sync-alt me-2"></i>
                                Regenerate QR Code
                            </button>
                        @endif

                        <!-- Test QR -->
                        @if ($cabang->barcode_token && $cabang->barcode_active)
                            <button onclick="testQR()" class="btn btn-outline-primary w-100 mb-3">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Test QR Code
                            </button>
                        @endif

                        <!-- View Orders -->
                        <a href="{{ route('outlet.show', $cabang->id) }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-list me-2"></i>
                            Lihat Data Outlet
                        </a>
                    </div>
                </div>

                <!-- Usage Instructions -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-question-circle me-2"></i>
                            Cara Menggunakan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small text-muted">
                            <ol class="mb-0 ps-3">
                                <li class="mb-2">
                                    <strong>Generate QR Code</strong> untuk outlet ini
                                </li>
                                <li class="mb-2">
                                    <strong>Download atau Print</strong> QR Code
                                </li>
                                <li class="mb-2">
                                    <strong>Tempel QR Code</strong> di lokasi yang mudah diakses outlet
                                </li>
                                <li class="mb-2">
                                    <strong>Outlet scan QR</strong> untuk akses form pemesanan
                                </li>
                                <li class="mb-0">
                                    <strong>Monitor pesanan</strong> melalui sistem admin
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Print Modal -->
    <div class="modal fade" id="printModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-print me-2"></i>
                        Print QR Code - {{ $cabang->nama }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center" id="printContent">
                    <div class="mb-4">
                        <img src="{{ asset('assets/img/logo/brand.png') }}" alt="Logo" width="80"
                            class="mb-3">
                        <h4>{{ $cabang->nama }}</h4>
                        <p class="text-muted">Scan untuk Akses Sistem Pemesanan</p>
                    </div>

                    <div class="d-flex justify-content-center mb-4">
                        <div class="p-4 bg-white border rounded">
                            {!! $qrCode ?? '' !!}
                        </div>
                    </div>

                    <div class="small text-muted">
                        <p><strong>Petunjuk Penggunaan:</strong></p>
                        <p>1. Buka aplikasi kamera atau scanner QR<br>
                            2. Arahkan ke QR Code di atas<br>
                            3. Ikuti link yang muncul<br>
                            4. Isi form pemesanan yang tersedia</p>
                    </div>

                    <hr>
                    <div class="small text-muted">
                        <p class="mb-1">Seroo - Sistem Manajemen Outlet</p>
                        <p class="mb-0">Generated: {{ now()->format('d F Y, H:i') }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="executePrint()">
                        <i class="fas fa-print me-1"></i>
                        Print Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmGenerateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('outlet.barcode.generate', $cabang->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-maron">
                        <h5 class="modal-title text-white fw-bold">Konfirmasi Generate</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        Generate QR Code baru untuk outlet <strong>{{ $cabang->nama }}</strong>?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-outline-success">Ya,
                            Generate!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        function generateBarcode() {
            // Konfirmasi bawaan JavaScript
            const konfirmasi = confirm("Generate QR Code baru untuk outlet {{ $cabang->nama }} ?");

            if (!konfirmasi) return;

            // Tampilkan loading sederhana
            alert("Sedang membuat QR Code baru...");

            fetch('{{ route('outlet.barcode.generate', $cabang->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("QR Code berhasil dibuat.");
                        location.reload();
                    } else {
                        alert("Gagal generate QR Code: " + (data.message || ""));
                    }
                })
                .catch(error => {
                    alert("Error: " + error.message);
                });
        }

        function toggleStatus() {
            const isActive = {{ $cabang->barcode_active ? 'true' : 'false' }};
            const action = isActive ? 'nonaktifkan' : 'aktifkan';

            Swal.fire({
                title: `${action.charAt(0).toUpperCase() + action.slice(1)} QR Code?`,
                text: `QR Code akan ${action}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: isActive ? '#BF1A1A' : '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Ya, ${action.charAt(0).toUpperCase() + action.slice(1)}!`,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('outlet.barcode.toggle', $cabang->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: `QR Code berhasil ${action}`,
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Gagal mengubah status');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', error.message, 'error');
                        });
                }
            });
        }

        function regenerateBarcode() {
            Swal.fire({
                title: 'Regenerate QR Code?',
                text: 'QR Code lama akan diganti dengan yang baru. URL lama tidak akan bisa digunakan lagi.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Regenerate!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Membuat QR Code baru',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('{{ route('outlet.barcode.regenerate', $cabang->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'QR Code baru telah dibuat',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Gagal regenerate QR Code');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', error.message, 'error');
                        });
                }
            });
        }

        function downloadQR() {
            window.open('{{ route('outlet.barcode.download', $cabang->id) }}', '_blank');
        }

        function printQR() {
            const modal = new bootstrap.Modal(document.getElementById('printModal'));
            modal.show();
        }

        function executePrint() {
            const printContent = document.getElementById('printContent').innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;

            // Reload to restore functionality
            setTimeout(() => {
                location.reload();
            }, 100);
        }

        function copyURL() {
            const url = '{{ $cabang->barcode_url }}';
            navigator.clipboard.writeText(url).then(() => {
                Swal.fire({
                    title: 'Copied!',
                    text: 'URL berhasil disalin ke clipboard',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                Swal.fire({
                    title: 'Copied!',
                    text: 'URL berhasil disalin ke clipboard',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        }

        function testQR() {
            window.open('{{ $cabang->barcode_url }}', '_blank');
        }
    </script>
@endpush
