@if ($item->status === 'belum_lunas')
    <div class="modal fade" id="bayarModal{{ $item->id }}" tabindex="-1"
        aria-labelledby="bayarModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('piutang.bayar', $item->nobukti) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-maron">
                        <h5 class="modal-title text-light fw-bold">Bayar Piutang #{{ $item->nobukti }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Jumlah Piutang: <strong>Rp {{ number_format($item->jumlah_piutang, 0, ',', '.') }}</strong>
                        </p><br>

                        <div class="mb-3">
                            <label class="form-label">Pilih Sumber Dana<span class="text-danger">*</span></label>
                            <select name="id_sumber_dana" class="form-select" required>
                                <option disabled selected>-- Pilih --</option>
                                @foreach ($sumberDana as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-bold" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-outline-success fw-bold">Bayar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
