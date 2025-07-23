@if ($item->status === 'belum_lunas')
    @php
        $totalDibayar = $item->pembayaran->sum('jumlah');
        $sisaPiutang = $item->jumlah_piutang - $totalDibayar;
    @endphp

    <div class="modal fade" id="bayarModal{{ $item->id }}" tabindex="-1"
        aria-labelledby="bayarModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('piutang.bayar', $item->nobukti) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-maron">
                        <h5 class="modal-title text-light fw-bold">Bayar Piutang #{{ $item->nobukti }}</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4 mb-2">
                                <label class="fw-bold d-block text-muted">Total Piutang</label>
                                <div class="text-dark">Rp. {{ number_format($item->jumlah_piutang, 2, ',', '.') }}</div>
                            </div>

                            <div class="col-4 mb-2">
                                <label class="fw-bold d-block text-muted">Sudah Dibayar</label>
                                <div class="text-success">Rp. {{ number_format($totalDibayar, 2, ',', '.') }}</div>
                            </div>

                            <div class="col-4 mb-3">
                                <label class="fw-bold d-block text-muted">Sisa Piutang</label>
                                <div class="text-danger">Rp. {{ number_format($sisaPiutang, 2, ',', '.') }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nominal Pembayaran <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah" class="form-control" step="any" required
                                    max="{{ $item->jumlah_piutang - $item->pembayaran->sum('jumlah') }}">
                            </div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Pilih Kas Masuk <span class="text-danger">*</span></label>
                            <select name="id_sumber_dana" class="form-select" required>
                                <option disabled selected>-- Pilih Sumber Dana --</option>
                                @foreach ($sumberDana as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-bold"
                            data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-outline-success fw-bold">Bayar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
