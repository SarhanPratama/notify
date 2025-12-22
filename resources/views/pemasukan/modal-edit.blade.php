<!-- Edit Modal -->
<div class="modal fade" id="editPemasukanModal{{ $item->id }}" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h6 class="modal-title">Edit Pemasukan</h6>
                <button type="button" class="close text-light"
                    data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('pemasukan.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label fw-bold">Tanggal <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal"
                            value="{{ $item->tanggal->format('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Jumlah <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="jumlah"
                                value="{{ $item->jumlah }}" required min="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label fw-bold">Kategori</label>
                        <select class="form-control" name="id_kategori_keuangan">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $item->id_kategori_keuangan == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label fw-bold">Keterangan <span
                                class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control" rows="3" required>{{ strip_tags($item->deskripsi) }}</textarea>
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
