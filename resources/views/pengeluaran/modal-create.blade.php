<!-- Create Modal -->
<div class="modal fade" id="createPengeluaranModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-maron text-white">
                <h6 class="modal-title">Tambah Pengeluaran</h6>
                <button type="button" class="close text-light" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('pengeluaran.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Kategori <span
                                class="text-danger">*</span></label>
                        <select class="form-control" name="id_kategori_keuangan" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Keterangan <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Jumlah <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="jumlah" required min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Posisi Kas <span class="text-danger">*</span></label>
                        <select class="form-control" name="posisi_kas" required>
                            <option value="">-- Pilih Posisi Kas --</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Bank BSI">Bank BSI</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
