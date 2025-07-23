@extends('layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/trix.css') }}">
@endsection

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- Back Button -->
                <div class="mb-4">
                    <a href="{{ route('penjualan.index') }}" class="btn btn-outline-secondary fw-bold">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>

                <!-- Main Form Card -->
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h4 class="mb-0 font-weight-bold">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Form Tambah Penjualan
                        </h4>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('penjualan.store') }}" method="POST">
                            @csrf

                            <!-- Informasi Umum -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 font-weight-bold text-primary">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Informasi Umum
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label font-weight-bold">
                                                <i class="fas fa-calendar-alt mr-1 text-primary"></i>
                                                Tanggal <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control" type="date" name="tanggal" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label font-weight-bold">
                                                <i class="fas fa-building mr-1 text-primary"></i>
                                                Cabang <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control" name="id_cabang" required>
                                                <option value="">-- Pilih Cabang --</option>
                                                @foreach ($cabang as $id => $nama)
                                                    <option value="{{ $id }}">{{ $nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label font-weight-bold">
                                                <i class="fas fa-wallet mr-1 text-primary"></i>
                                                Kas Masuk <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control" name="id_sumber_dana" required>
                                                <option value="">-- Pilih Kas Masuk --</option>
                                                @foreach ($sumberDana as $id => $nama)
                                                    <option value="{{ $id }}">{{ $nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 font-weight-bold text-primary">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        Metode Pembayaran
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card border-primary h-100">
                                                <div class="card-body text-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="tunai" value="tunai" required>
                                                        <label class="form-check-label font-weight-bold" for="tunai">
                                                            <i class="fas fa-money-bill-wave text-success fa-2x d-block mb-2"></i>
                                                            Tunai
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-warning h-100">
                                                <div class="card-body text-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="kasbon" value="kasbon">
                                                        <label class="form-check-label font-weight-bold" for="kasbon">
                                                            <i class="fas fa-handshake text-warning fa-2x d-block mb-2"></i>
                                                            Kasbon
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Produk -->
                            <div class="card mb-4">
                                <div class="card-header bg-light d-flex flex-column gap-3">
                                    <h6 class="mb-0 font-weight-bold text-primary">
                                        <i class="fas fa-box mr-2"></i>
                                        Detail Produk
                                    </h6>
                                    <div>
                                        <button type="button" id="tambah-detail" class="btn btn-outline-primary btn-sm">
                                            {{-- <i class="fas fa-plus mr-1"></i> --}}
                                            Tambah Item
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead class="bg-primary text-white text-nowrap">
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Bahan Baku <span class="text-danger">*</span></th>
                                                    <th class="text-center">Qty <span class="text-danger">*</span></th>
                                                    <th class="text-center">Harga <span class="text-danger">*</span></th>
                                                    <th class="text-center">Total <span class="text-danger">*</span></th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="detail-pembelian" class="text-nowrap">
                                                <tr class="detail-item">
                                                    <td class="text-center align-middle">
                                                        <span class="badge badge-primary row-number">1</span>
                                                    </td>
                                                    <td>
                                                        <select class="form-control form-control-sm" name="bahanBaku[]" required>
                                                            <option value="">-- Pilih Bahan Baku --</option>
                                                            @foreach ($produk as $data)
                                                                <option value="{{ $data->id }}"
                                                                        data-harga="{{ $data->harga }}"
                                                                        data-satuan="{{ $data->satuan ? $data->satuan->nama : '' }}">
                                                                    {{ $data->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" class="form-control quantity"
                                                                   name="quantity[]" min="1" value="1" required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text satuan-display">Unit</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="number" class="form-control harga"
                                                                   name="harga[]" min="0" step="0.01" required readonly>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control total bg-light" readonly>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="bg-light">
                                                <tr>
                                                    <td colspan="4" class="text-right font-weight-bold">
                                                        <h6 class="mb-0">Total Keseluruhan:</h6>
                                                    </td>
                                                    <td colspan="2">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text font-weight-bold">Rp</span>
                                                            </div>
                                                            <input type="text" class="form-control font-weight-bold bg-warning text-dark"
                                                                   id="total-keseluruhan-display" readonly>
                                                        </div>
                                                        <input type="hidden" id="total-keseluruhan" name="total_keseluruhan">
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Catatan -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 font-weight-bold text-primary">
                                        <i class="fas fa-sticky-note mr-2"></i>
                                        Catatan (Opsional)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <input name="catatan" type="hidden" id="catatan">
                                    <trix-editor input="catatan" class="form-control" style="min-height: 150px;"></trix-editor>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="card">
                                <div class="card-body text-center">
                                    <button type="submit" class="btn btn-outline-success">
                                        {{-- <i class="fas fa-save mr-2"></i> --}}
                                        Submit
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary">
                                        {{-- <i class="fas fa-redo mr-2"></i> --}}
                                        Reset Form
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowCounter = 1;

            // Fungsi untuk update nomor urut
            function updateRowNumbers() {
                document.querySelectorAll('.row-number').forEach((element, index) => {
                    element.textContent = index + 1;
                });
            }

            // Fungsi tambah baris
            document.getElementById('tambah-detail').addEventListener('click', function() {
                rowCounter++;
                const newRow = document.querySelector('.detail-item').cloneNode(true);

                // Reset values
                newRow.querySelectorAll('input').forEach(input => {
                    if (input.type === 'number' && input.classList.contains('quantity')) {
                        input.value = '1';
                    } else if (!input.readOnly) {
                        input.value = '';
                    }
                });
                newRow.querySelector('select').selectedIndex = 0;
                newRow.querySelector('.satuan-display').textContent = 'Unit';

                // Append new row
                document.querySelector('#detail-pembelian').appendChild(newRow);
                updateRowNumbers();

                // Bind event listeners untuk baris baru
                setupRowEventListeners(newRow);
            });

            // Fungsi untuk setup event listeners pada baris
            function setupRowEventListeners(row) {
                const qtyInput = row.querySelector('.quantity');
                const hargaInput = row.querySelector('.harga');
                const produkSelect = row.querySelector('select[name="bahanBaku[]"]');

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
                        if (confirm('Yakin ingin menghapus item ini?')) {
                            row.remove();
                            updateRowNumbers();
                            updateTotal();
                        }
                    } else {
                        alert('Minimal harus ada satu item dalam transaksi!');
                    }
                }
            });

            function calculateTotal(row) {
                const qty = parseFloat(row.querySelector('.quantity').value) || 0;
                const harga = parseFloat(row.querySelector('.harga').value) || 0;
                const total = qty * harga;
                row.querySelector('.total').value = formatNumber(total);
                updateTotal();
            }

            function updateTotal() {
                let total = 0;
                document.querySelectorAll('.total').forEach(input => {
                    const value = input.value.replace(/[^0-9]/g, '');
                    total += parseFloat(value) || 0;
                });

                document.getElementById('total-keseluruhan-display').value = formatNumber(total);
                document.getElementById('total-keseluruhan').value = total;
            }

            function updateSatuan(row) {
                const selectProduk = row.querySelector('select[name="bahanBaku[]"]');
                const selectedOption = selectProduk.options[selectProduk.selectedIndex];
                const satuan = selectedOption.getAttribute('data-satuan');
                const harga = selectedOption.getAttribute('data-harga');
                const satuanDisplay = row.querySelector('.satuan-display');
                const hargaInput = row.querySelector('.harga');

                satuanDisplay.textContent = satuan || 'Unit';

                if (harga) {
                    hargaInput.value = parseFloat(harga);
                    calculateTotal(row);
                }
            }

            function formatNumber(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            }

            // Setup event listeners untuk baris yang sudah ada
            document.querySelectorAll('#detail-pembelian tr.detail-item').forEach(row => {
                setupRowEventListeners(row);
                updateSatuan(row);
            });

            // Set default tanggal hari ini
            const today = new Date();
            const dateString = today.toISOString().split('T')[0];
            document.querySelector('input[name="tanggal"]').value = dateString;

            // Highlight selected payment method
            document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.card.border-primary, .card.border-warning').forEach(card => {
                        card.classList.remove('border-success', 'bg-light');
                    });

                    const selectedCard = this.closest('.card');
                    selectedCard.classList.add('border-success', 'bg-light');
                });
            });

            // Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                const rows = document.querySelectorAll('#detail-pembelian tr');
                let hasValidItems = false;

                rows.forEach(row => {
                    const select = row.querySelector('select[name="bahanBaku[]"]');
                    const qty = row.querySelector('.quantity');
                    const harga = row.querySelector('.harga');

                    if (select.value && qty.value && harga.value) {
                        hasValidItems = true;
                    }
                });

                if (!hasValidItems) {
                    e.preventDefault();
                    alert('Minimal harus ada satu item yang valid dalam transaksi!');
                    return false;
                }

                // Konfirmasi sebelum submit
                if (!confirm('Yakin ingin menyimpan data penjualan ini?')) {
                    e.preventDefault();
                    return false;
                }
            });

            // Reset form functionality
            document.querySelector('button[type="reset"]').addEventListener('click', function() {
                if (confirm('Yakin ingin mereset semua data?')) {
                    // Reset ke satu baris saja
                    const tbody = document.querySelector('#detail-pembelian');
                    const firstRow = tbody.querySelector('tr').cloneNode(true);
                    tbody.innerHTML = '';
                    tbody.appendChild(firstRow);

                    // Reset values
                    firstRow.querySelectorAll('input').forEach(input => {
                        if (input.type === 'number' && input.classList.contains('quantity')) {
                            input.value = '1';
                        } else if (!input.readOnly) {
                            input.value = '';
                        }
                    });
                    firstRow.querySelector('select').selectedIndex = 0;
                    firstRow.querySelector('.satuan-display').textContent = 'Unit';

                    setupRowEventListeners(firstRow);
                    updateRowNumbers();
                    updateTotal();

                    // Reset payment method cards
                    document.querySelectorAll('.card.border-primary, .card.border-warning').forEach(card => {
                        card.classList.remove('border-success', 'bg-light');
                    });
                }
            });
        });
    </script>
@endsection
