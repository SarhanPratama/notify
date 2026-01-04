<!-- Edit Modal -->
<div class="modal fade" id="editPengeluaranModal{{ $item->id }}" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h6 class="modal-title">Edit Pengeluaran</h6>
                <button type="button" class="close text-light"
                    data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('pengeluaran.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tanggal{{ $item->id }}">Tanggal <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal{{ $item->id }}" name="tanggal" value="{{ old('tanggal', $item->tanggal->format('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_kategori_keuangan{{ $item->id }}">Kategori <span
                                class="text-danger">*</span></label>
                        <select class="form-control" id="id_kategori_keuangan{{ $item->id }}" name="id_kategori_keuangan" required>
                            @foreach($categories as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('id_kategori_keuangan', $item->id_kategori_keuangan) == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan <span
                                class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control" rows="3" required>{{ $item->deskripsi }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="jumlah"
                            value="{{ $item->jumlah }}" required min="0">
                    </div>

                    <div class="mb-3">
                        <label for="posisi_kas{{ $item->id }}">Posisi Kas <span
                                class="text-danger">*</span></label>
                        <select class="form-control" id="posisi_kas{{ $item->id }}" name="posisi_kas" required>
                            <option value="Tunai" {{ old('posisi_kas', $item->posisi_kas) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="Bank BSI" {{ old('posisi_kas', $item->posisi_kas) == 'Bank BSI' ? 'selected' : '' }}>Bank BSI</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
