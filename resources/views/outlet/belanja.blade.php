@extends('layouts.outlet')
@section('content')
<!-- Section Belanja -->
<div id="section-menu" class="content-section">
    <div class="row mb-4">
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" class="form-control" id="searchProduct" placeholder="Cari bahan baku..."
                    onkeyup="filterProducts()">
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($bahanBaku as $item)
            <div class="col-6 col-lg-3 col-md-4 col-sm-6 mb-4 product-item" data-name="{{ strtolower($item->nama) }}">
                <div class="card product-card">
                    <div class="position-relative">
                        @if ($item->foto && Storage::disk('public')->exists($item->foto))
                            <img src="{{ Storage::url($item->foto) }}" alt="{{ $item->nama }}"
                                class="product-image">
                        @else
                            <div class="product-placeholder">
                                <i class="fas fa-box fa-3x text-muted"></i>
                            </div>
                        @endif
                        <span class="badge badge-stock bg-primary">
                            Stok: {{ $item->viewStok->stok_akhir ?? 0 }} {{ $item->satuan->nama }}
                        </span>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title text-truncate" title="{{ $item->nama }}">
                            {{ ucwords($item->nama) }}
                        </h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            {{-- <span class="badge bg-success">{{ $item->kategori->nama }}</span>
                        <span class="text-muted small">{{ $item->satuan->nama }}</span> --}}
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 mb-0 text-maron">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </span>
                        </div>
                        <button class="btn btn-add-cart"
                            onclick="addToCart({{ $item->id }}, '{{ $item->nama }}', {{ $item->harga }}, '{{ $item->satuan->nama }}', {{ $item->viewStok->stok_akhir ?? 0 }})">
                            <i class="fas fa-cart-plus me-1"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada bahan baku tersedia</h5>
            </div>
        @endforelse
    </div>
</div>

@endsection


