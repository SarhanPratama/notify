<!-- Modal Reject -->
<div class="modal fade" id="rejectPenjualanModal{{ $item->id }}" tabindex="-1"
    role="dialog" aria-labelledby="rejectModalLabel{{ $item->id }}"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectModalLabel{{ $item->id }}">
                    Tolak Penjualan {{ $item->nobukti }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pemasukan.reject-penjualan', $item->id) }}"
                method="POST">
                @csrf
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menolak penjualan ini? Stok akan
                        dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
