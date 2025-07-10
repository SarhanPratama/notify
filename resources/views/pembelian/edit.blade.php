@extends('layouts.master')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/trix.css') }}">
@endsection
@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">

        <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary mb-3 fw-bold">
            <i class="fa fa-arrow-left me-2"></i>Kembali
        </a>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-warning py-3 text-center">
                        <h1 class="mb-0 fs-5 fw-bolder text-light">Form Edit Pembelian</h1>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('pembelian.update', $detailPembelian->nobukti) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mb-4">
                                {{-- <div class="col-lg-6 col-md 6 col-sm-12 col-12">
                                    <label for="">Karyawan</label>
                                    <input type="text" class="form-control form-control-sm" name="id_user" value="{{ Auth::user()->name }}" readonly>
                                </div> --}}
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold" for="tanggal">Tanggal <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control form-control-sm border-0 bg-light shadow-none" type="date"
                                        id="tanggal" name="tanggal" value="{{ $detailPembelian->tanggal }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Supplier</span></label>
                                    <select class="form-select form-select-sm" name="id_supplier">
                                        <option value="">Pilih Supplier</option>
                                        @foreach ($suppliers as $id => $nama)
                                            <option value="{{ $id }}"
                                                {{ $detailPembelian->id_supplier == $id ? 'selected' : '' }}>
                                                {{ $nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Sumber Dana</span></label>
                                    <select class="form-select form-select-sm" name="id_sumber_dana">
                                        <option value="">-- Pilih Sumber Dana --</option>
                                        @foreach ($sumberDana as $id => $nama)
                                            <option value="{{ $id }}"
                                                {{ optional($detailPembelian->transaksi->first())->id_sumber_dana == $id ? 'selected' : '' }}>
                                                {{ $nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Product Table -->
                            <div class="table-responsive mb-4">
                                <div class="mb-3">
                                    <button type="button" id="tambah-detail" class="btn btn-outline-primary btn-sm">
                                        Tambah Baris
                                    </button>
                                </div>
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Produk</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th>Total per item</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-pembelian">
                                        @foreach ($detailPembelian->mutasi as $detail)
                                            <tr class="detail-item">
                                                <td>
                                                    <select class="form-select form-select-sm" name="bahanBaku[]"
                                                        style="min-width: 200px" required>
                                                        <option value="">Pilih Bahan Baku</option>
                                                        @foreach ($produk as $data)
                                                            <option value="{{ $data->id }}"
                                                                {{ $detail->id_bahan_baku == $data->id ? 'selected' : '' }}
                                                                data-satuan="{{ $data->satuan ? $data->satuan->nama : '' }}">
                                                                {{ $data->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm" style="min-width: 120px">
                                                        <input type="number" class="form-control form-control-sm quantity"
                                                            name="quantity[]" min="1"
                                                            value="{{ $detail->quantity }}" required>
                                                        <span class="input-group-text satuan-display"></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm harga"
                                                        name="harga[]" min="0" style="min-width: 120px"
                                                        value="{{ $detail->harga }}" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm total"
                                                        value="{{ $detail->sub_total }}" style="min-width: 120px" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Total Section -->
                            <div class="row">
                                <div class="col-md-4 offset-md-8 col-8 offset-4 mb-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Total</span>
                                        <input type="text" class="form-control font-weight-bold total"
                                            id="total-keseluruhan"
                                            value="{{ number_format($detailPembelian->mutasi->sum('sub_total'), 0, ',', '.') }}"
                                            readonly>
                                    </div>
                                </div>
                                 <div class="col-lg-6 col-md-6 col-sm12 col-12">
                                    <label for="catatan"><strong>Catatan (Opsional)</strong></label>
                                    <input name="catatan" type="hidden" id="catatan"
                                        value="{{ $detailPembelian->catatan }}" rows="10" class="form-control">
                                    <trix-editor input="catatan"></trix-editor>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/trix.js') }}"></script>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi tambah baris
        document.getElementById('tambah-detail').addEventListener('click', function() {
            const newRow = document.querySelector('.detail-item').cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelector('select').selectedIndex = 0;
            document.querySelector('#detail-pembelian').appendChild(newRow);

            // Bind event listeners untuk baris baru
            setupRowEventListeners(newRow);
        });

        // Fungsi untuk setup event listeners pada baris
        function setupRowEventListeners(row) {
            const qtyInput = row.querySelector('.quantity');
            const hargaInput = row.querySelector('.harga');
            const produkSelect = row.querySelector('select[name="produk[]"]');

            qtyInput.addEventListener('input', function() {
                calculateTotal(row);
            });

            hargaInput.addEventListener('input', function() {
                calculateTotal(row);
            });

            produkSelect.addEventListener('change', function() {
                updateSatuan(row);
            });
        }

        // Fungsi hapus baris
        document.querySelector('#detail-pembelian').addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                const row = e.target.closest('tr');
                if (document.querySelectorAll('#detail-pembelian tr').length > 1) {
                    row.remove();
                    updateTotal();
                }
            }
        });

        function calculateTotal(row) {
            const qty = parseFloat(row.querySelector('.quantity').value) || 0;
            const harga = parseFloat(row.querySelector('.harga').value) || 0;
            const total = qty * harga;
            const totalInput = row.querySelector('.total');

            totalInput.value = formatRupiah(total);
            totalInput.dataset.rawTotal = total.toFixed(2);
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('#detail-pembelian tr').forEach(row => {
                const qty = parseFloat(row.querySelector('.quantity').value) || 0;
                const harga = parseFloat(row.querySelector('.harga').value) || 0;
                total += qty * harga;
            });
            document.getElementById('total-keseluruhan').value = formatRupiah(total);
        }

        function updateSatuan(row) {
            const selectProduk = row.querySelector('select[name="produk[]"]');
            const selectedOption = selectProduk.options[selectProduk.selectedIndex];
            const satuan = selectedOption.getAttribute('data-satuan');
            const satuanDisplay = row.querySelector('.satuan-display');

            if (satuan) {
                satuanDisplay.textContent = `${satuan}`;
            } else {
                satuanDisplay.textContent = '';
            }
        }

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        document.querySelectorAll('#detail-pembelian tr.detail-item').forEach(row => {
            setupRowEventListeners(row);
            updateSatuan(row); // Update satuan untuk baris yang sudah ada
        });
    });
</script>
