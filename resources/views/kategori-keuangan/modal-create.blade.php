<div class="modal fade" id="createKategoriKeuanganModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-maron text-white">
                <h6 class="modal-title">Tambah Kategori Keuangan</h6>
                <button type="button" class="close text-light" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('kategori-keuangan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama" required placeholder="Contoh: Operasional">
                    </div>
                    <div class="form-group">
                        <label class="form-label fw-bold">Jenis <span class="text-danger">*</span></label>
                        <select class="form-control" name="jenis" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                            <option value="lainnya">Lainnya</option>
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
