<div class="modal fade" id="confirmGenerateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-maron">
                <h5 class="modal-title text-white fw-bold">Konfirmasi Generate</h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('outlet.barcode.generate', $outlet->id) }}" method="POST">
                @csrf

                <div class="modal-body">
                    Generate QR Code baru untuk outlet <strong>{{ $outlet->nama }}</strong>?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-success">Ya,
                        Generate!</button>
                </div>
            </form>
        </div>
    </div>
</div>
