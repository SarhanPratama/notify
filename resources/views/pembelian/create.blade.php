@extends('layouts.master')

@section('content')

<div class="container-fluid">
        @include('layouts.breadcrumbs')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary fw-bold">
                <i class="fa fa-arrow-left me-2"></i>Kembali
            </a>
            {{-- @if (count($cartItems) > 0)
                <form action="{{ route('pembelian.cart.create.clear') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger"
                        onclick="return confirm('Yakin ingin mengosongkan keranjang?')">
                        Reset Keranjang
                    </button>
                </form>
            @endif --}}
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0 fw-bold">Tambah Item</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pembelian.cart.create.add') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Bahan Baku <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_bahan_baku" required>
                                    <option value="">-- Pilih Bahan Baku --</option>
                                    @foreach ($bahanBaku as $data)
                                        <option value="{{ $data->id }}">
                                            {{ $data->nama }} ({{ $data->satuan->nama ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold">Qty <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="quantity" min="1" value="1"
                                        required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold">Harga <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="harga" min="0" required
                                        placeholder="0">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-outline-primary w-100">
                                Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Keranjang & Form Final Submit -->
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-maron text-white py-3">
                        <h6 class="mb-0 fw-bold">Keranjang Pembelian</h6>
                    </div>
                    <div class="card-body">
                        <!-- Tabel Keranjang -->
                        <div class="table-responsive mb-3">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-nowrap">Bahan Baku</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cartItems as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item['nama_bahan_baku'] }}</td>
                                            <td class="text-center text-nowrap">{{ $item['quantity'] }} {{ $item['satuan'] }}</td>
                                            <td class="text-end">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                            <td class="text-end fw-bold">Rp
                                                {{ number_format($item['sub_total'], 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('pembelian.cart.create.remove', $index) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('Hapus item ini?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fa fa-shopping-cart fa-2x mb-2 d-block"></i>
                                                Keranjang masih kosong. Tambahkan item terlebih dahulu.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if (count($cartItems) > 0)
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">Total:</td>
                                            <td class="text-end fw-bold">Rp
                                                {{ number_format(collect($cartItems)->sum('sub_total'), 0, ',', '.') }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>

                        <!-- Form Final Submit -->
                        @if (count($cartItems) > 0)
                            <hr class="mb-3">
                            <form action="{{ route('pembelian.store') }}" method="POST">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Supplier</label>
                                        <select class="form-select" name="id_supplier">
                                            <option value="">-- Pilih Supplier (Opsional) --</option>
                                            @foreach ($suppliers as $id => $nama)
                                                <option value="{{ $id }}">{{ $nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Catatan</label>
                                        <input type="text" class="form-control" name="catatan"
                                            placeholder="Catatan (opsional)">
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-outline-success"
                                        onclick="return confirm('Yakin ingin menyimpan pembelian ini?')">
                                        Simpan Pembelian
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- {
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
</script> --}}
