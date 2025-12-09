<div class="modal fade" id="confirmRegenerateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('outlet.barcode.regenerate', $outlet->id) }}" method="POST">
                @csrf
                @method('POST') {{-- Pastikan method POST --}}

                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="fas fa-qrcode me-2"></i>Regenerate Barcode
                    </h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-warning text-black">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Perhatian!</strong> Regenerate barcode akan membuat QR code lama tidak dapat digunakan
                        lagi.
                    </div>

                    <p>Apakah Anda yakin ingin membuat barcode baru untuk outlet <strong>{{ $outlet->nama }}</strong>?
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        Ya, Regenerate!
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
