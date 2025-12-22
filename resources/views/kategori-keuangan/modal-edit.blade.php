<div class="modal fade" id="editKategoriKeuanganModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h6 class="modal-title">Edit Kategori Keuangan</h6>
                <button type="button" class="close text-light" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('kategori-keuangan.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama" value="{{ $item->nama }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Jenis <span class="text-danger">*</span></label>
                        <select class="form-control" name="jenis" required>
                            <option value="pemasukan" {{ $item->jenis == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ $item->jenis == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                            <option value="lainnya" {{ $item->jenis == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
