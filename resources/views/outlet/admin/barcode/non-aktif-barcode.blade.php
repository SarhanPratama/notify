{{-- Modal untuk Toggle Status Barcode --}}
<div class="modal fade" id="toggleBarcodeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('outlet.barcode.toggle', $outlet->id) }}" method="POST">
                @csrf
                @method('POST')

                <div class="modal-header" id="toggleModalHeader">
                    @if ($outlet->barcode_active)
                        {{-- Header untuk Nonaktifkan --}}
                        <h5 class="modal-title text-white fw-bold">
                            Nonaktifkan Barcode
                        </h5>
                        {{-- <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button> --}}
                    @else
                        {{-- Header untuk Aktifkan --}}
                        <h5 class="modal-title text-white fw-bold">
                            Aktifkan Barcode
                        </h5>
                        @endif
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>

                <div class="modal-body">
                    @if ($outlet->barcode_active)
                        {{-- Body untuk Nonaktifkan --}}
                        <div class="alert alert-danger text-black">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Perhatian!</strong> Menonaktifkan barcode akan membuat QR code outlet tidak dapat
                            digunakan untuk akses sistem pemesanan.
                        </div>

                        <p>Apakah Anda yakin ingin menonaktifkan barcode untuk outlet
                            <strong>{{ $outlet->nama }}</strong>?</p>
                    @else
                        {{-- Body untuk Aktifkan --}}
                        <div class="alert alert-success text-black">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi!</strong> Mengaktifkan barcode akan memungkinkan outlet mengakses sistem
                            pemesanan kembali.
                        </div>

                        <p>Apakah Anda yakin ingin mengaktifkan barcode untuk outlet
                            <strong>{{ $outlet->nama }}</strong>?</p>
                    @endif

                    {{-- Input hidden untuk status --}}
                    <input type="hidden" name="status"
                        value="{{ $outlet->barcode_active ? 'deactivate' : 'activate' }}">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        Batal
                    </button>
                    @if ($outlet->barcode_active)
                        <button type="submit" class="btn btn-outline-danger">
                            Ya, Nonaktifkan!
                        </button>
                    @else
                        <button type="submit" class="btn btn-outline-success">
                            Ya, Aktifkan!
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script untuk styling dinamis --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('toggleBarcodeModal');
        const header = document.getElementById('toggleModalHeader');

        if ({{ $outlet->barcode_active ? 'true' : 'false' }}) {
            // Jika aktif, header merah untuk nonaktifkan
            header.className = 'modal-header bg-danger';
        } else {
            // Jika tidak aktif, header hijau untuk aktifkan
            header.className = 'modal-header bg-success';
        }
    });
</script>
