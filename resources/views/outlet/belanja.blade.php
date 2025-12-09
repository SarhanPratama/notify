@extends('layouts.outlet')
@section('content')
<!-- Section Belanja -->
    <div class="mb-4">
        <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1 text-maron fs-4 fw-bold">
                    Belanja
                </h3>
            </div>
        </div>
    </div>
<div id="section-menu" class="content-section">
    <form method="GET" action="{{ route('outlet.belanja', $token) }}" id="filterForm">
        <div class="row mb-4">
            <div class="col-12">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchProduct" name="search"
                           placeholder="Cari bahan baku..." value="{{ $search }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Kategori -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center gap-2" id="categoryFilter">
                    <a href="{{ route('outlet.belanja', $token) . '?' . http_build_query(array_merge(request()->query(), ['category' => ''])) }}"
                       class="btn btn-sm category-btn {{ empty($categoryId) ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="fas fa-th-large me-1"></i>Semua
                    </a>
                    @foreach($kategoris as $kategori)
                        <a href="{{ route('outlet.belanja', $token) . '?' . http_build_query(array_merge(request()->query(), ['category' => $kategori->id])) }}"
                           class="btn btn-sm category-btn {{ $categoryId == $kategori->id ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ $kategori->nama }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        @forelse($bahanBaku as $item)
            <div class="col-12 col-lg-3 col-md-4 col-sm-6 mb-4 product-item">
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
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="card-title text-truncate" title="{{ $item->nama }}">
                                {{ ucwords($item->nama) }}
                            </h6>

                            <span class="h5 mb-0 text-maron">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
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

@push('scripts')
<script>
    // // Fungsi untuk menambah ke keranjang
    // function addToCart(id, nama, harga, satuan, stok) {
    //     // Implementasi keranjang belanja akan ditambahkan di sini
    //     console.log('Menambah ke keranjang:', { id, nama, harga, satuan, stok });
    // }

    // Auto-submit form ketika mengetik di search box (debounce)
    let searchTimeout;
    document.getElementById('searchProduct').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500); // Delay 500ms sebelum submit
    });
</script>
@endpush


