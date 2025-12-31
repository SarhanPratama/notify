@extends('layouts.outlet')
@section('content')
    <!-- Section Belanja -->
    <div class="container-fluid mt-3">
        <div class="mb-4">
            <div class="d-flex justify-content-center align-items-center flex-wrap">
                <div>
                    <h3 class="fs-4 mb-1 text-maron font-weight-bold">
                        Belanja
                    </h3>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show text-success" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show text-danger" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <!-- Kolom Produk -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header text-white bg-maron py-3">
                        <h6 class="mb-0 fw-bold">Pilih Bahan Baku</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('outlet.belanja', $token) }}" id="filterForm">
                            <!-- Search Box -->
                            <div class="row mb-3">
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
                            <div class="d-flex flex-wrap mb-3" id="categoryFilter">
                                <a href="{{ route('outlet.belanja', $token) . '?' . http_build_query(array_merge(request()->query(), ['category' => ''])) }}"
                                    class="btn btn-sm m-1 {{ empty($categoryId) ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    Semua
                                </a>
                                @foreach ($kategoris as $kategori)
                                    <a href="{{ route('outlet.belanja', $token) . '?' . http_build_query(array_merge(request()->query(), ['category' => $kategori->id])) }}"
                                        class="btn btn-sm m-1 {{ $categoryId == $kategori->id ? 'btn-primary' : 'btn-outline-secondary' }}">
                                        {{ $kategori->nama }}
                                    </a>
                                @endforeach
                            </div>
                        </form>

                        <!-- Product Grid (Scrollable) -->
                        <div class="row" style="max-height: 60vh; overflow-y: auto;">
                            @forelse($bahanBaku as $item)
                                <div class="col-6 col-md-4 col-lg-3 mb-3">
                                    <div class="card h-100 shadow-sm border">
                                        <div class="position-relative">
                                            @if ($item->foto && Storage::disk('public')->exists($item->foto))
                                                <img src="{{ Storage::url($item->foto) }}" alt="{{ $item->nama }}"
                                                    class="card-img-top" style="height: 120px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                    style="height: 120px;">
                                                    <i class="fas fa-box fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                            <span class="badge bg-info position-absolute"
                                                style="top: 5px; right: 5px; font-size: 10px;">
                                                Stok: {{ $item->viewStok->stok_akhir ?? 0 }}
                                            </span>
                                        </div>
                                        <div class="card-body p-2">
                                            <h6 class="card-title text-truncate mb-1" style="font-size: 12px;"
                                                title="{{ $item->nama }}">
                                                {{ ucwords($item->nama) }}
                                            </h6>
                                            <p class="text-primary mb-2 fw-bold" style="font-size: 13px;">
                                                Rp
                                                {{ number_format($item->harga, 0, ',', '.') }}/{{ $item->satuan->nama }}
                                            </p>
                                            <form action="{{ route('outlet.cart.add', $token) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id_bahan_baku" value="{{ $item->id }}">
                                                <div class="input-group input-group-sm mb-2">
                                                    <input type="number" class="form-control" id="quantity-{{ $item->id }}" name="quantity"
                                                        value="1" min="1"
                                                        max="{{ $item->viewStok->stok_akhir ?? 0 }}">
                                                    <span class="input-group-text">{{ $item->satuan->nama }}</span>
                                                </div>
                                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                    <i class="fas fa-cart-plus"></i> Tambah
                                                </button>
                                            </form>
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
                </div>
            </div>

            <!-- Kolom Keranjang -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm sticky-top" style="top: 60px;">
                    <div class="card-header bg-maron text-white d-flex justify-content-between align-items-center py-3">
                        <h6 class="mb-0 fw-bold">Keranjang</h6>
                    </div>
                    <div class="card-body p-0">
                        @if (count($cartItems) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tbody>
                                        @foreach ($cartItems as $index => $item)
                                            <tr>
                                                <td class="ps-2">
                                                    <strong
                                                       >{{ $item['nama_bahan_baku'] }}</strong><br>
                                                    <small class="text-muted">
                                                        {{ $item['quantity'] }} {{ $item['satuan'] }} x Rp
                                                        {{ number_format($item['harga'], 0, ',', '.') }}
                                                    </small>
                                                </td>
                                                <td class="text-end pe-1">
                                                    <strong>Rp
                                                        {{ number_format($item['sub_total'], 0, ',', '.') }}</strong>
                                                </td>
                                                <td class="text-center" style="width: 40px;">
                                                    <form action="{{ route('outlet.cart.remove', [$token, $index]) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-link text-danger btn-sm p-0">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-secondary">
                                        <tr>
                                            <td class="ps-2 fw-bold">Total</td>
                                            <td class="text-end pe-1 fw-bold" colspan="1">
                                                Rp {{ number_format(collect($cartItems)->sum('sub_total'), 0, ',', '.') }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Form Checkout -->
                            <div class="p-3 border-top">
                                <form action="{{ route('outlet.order.store', $token) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success w-100"
                                        onclick="return confirm('Yakin ingin membuat pesanan ini?')">
                                        Buat Pesanan
                                    </button>
                                </form>
                                @if (count($cartItems) > 0)
                                    <form action="{{ route('outlet.cart.clear', $token) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger w-100 mt-2"
                                            onclick="return confirm('Kosongkan keranjang?')">
                                            Kosongkan Keranjang
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                <p>Keranjang kosong</p>
                                <small>Pilih bahan baku untuk ditambahkan</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
