@extends('layouts.master')

@section('title', 'Generate Barcode - ' . $outlet->nama)

@section('content')

<!-- Page Header -->
@include('layouts.breadcrumbs')
 <a href="{{ route('outlet.index') }}" class="btn btn-sm btn-outline-secondary mb-3 fw-bold">
    Kembali
</a>

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
                    @if ($outlet->barcode_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Tidak Aktif</span>
                    @endif
                </div>
            </div>
            <div class="card-body text-center">
                @if ($outlet->barcode_token)
                    <div id="qrCodeContainer" class="mb-4">
                        <!-- QR Code will be generated here -->
                        <div class="d-flex justify-content-center">
                            <div class="p-3 bg-white border rounded qr-container">
                                {{-- {!! $qrCode !!} --}}
                                <img src="{{ $qrCode }}" alt="QR Code" class="img-fluid" />
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <strong class="text-info fs-6">URL Akses:</strong><br>
                            <code class="text-break">{{ $outlet->barcode_url }}</code>
                        </small>
                    </div>

                    <div class="text-muted small mb-3">
                        Dibuat:
                        {{ $outlet->barcode_generated_at ? $outlet->barcode_generated_at->format('d F Y, H:i') : '-' }}
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center flex-wrap">
                        <button onclick="downloadQR()" class="btn btn-success btn-sm">
                            <i class="fas fa-download me-1"></i>
                            Download QR Code
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
                        <td class="fw-bold">{{ $outlet->nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alamat</td>
                        <td>{{ $outlet->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Telepon</td>
                        <td>{{ $outlet->telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status QR</td>
                        <td>
                            @if ($outlet->barcode_active)
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
                            @if ($outlet->barcode_token)
                                <code class="small">{{ Str::limit($outlet->barcode_token, 20, '...') }}</code>
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

                @if (!$outlet->barcode_token)
                    <!-- Generate New QR -->
                    <button class="btn btn-success w-100 mb-3" data-toggle="modal" data-target="#confirmGenerateModal">
                        Generate QR Code Baru
                    </button>
                @else
                    <!-- Toggle Status -->
                    <button class="btn btn-{{ $outlet->barcode_active ? 'warning' : 'success' }} w-100 mb-3"
                        data-toggle="modal" data-target="#toggleBarcodeModal">
                        <i class="fas fa-{{ $outlet->barcode_active ? 'pause' : 'play' }}-circle me-2"></i>
                        {{ $outlet->barcode_active ? 'Nonaktifkan' : 'Aktifkan' }} QR Code
                    </button>

                    <!-- Regenerate QR -->
                    <button class="btn btn-info w-100 mb-3" data-toggle="modal" data-target="#confirmRegenerateModal">
                        <i class="fas fa-sync-alt me-2"></i>
                        Regenerate QR Code
                    </button>
                @endif

                <!-- Test QR -->
                @if ($outlet->barcode_token && $outlet->barcode_active)
                    <a href="{{ $outlet->barcode_url }}" target="_blank" class="btn btn-outline-primary w-100 mb-3">
                        <i class="fas fa-external-link-alt me-2"></i>
                        Test QR Code
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>


    @include('outlet.admin.barcode.confirm-generate-barcode')
    @include('outlet.admin.barcode.confirm-regenerate-barcode')
    @include('outlet.admin.barcode.non-aktif-barcode')
@endsection

@push('scripts')
    <script>

function downloadQR() {
    window.open('{{ route('outlet.barcode.download', $outlet->id) }}', '_blank');
}

function testQR() {
    window.open('{{ $outlet->barcode_url }}', '_blank');
}
    </script>
@endpush
