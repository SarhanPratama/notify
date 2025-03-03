@extends('layouts.master')
@section('content')

@include('layouts.breadcrumbs')

<div class="container">
    <div class="card shadow">
        <div class="card-header text-white bg-success">
            <a href="{{ route('produk.index') }}" class="btn btn-sm btn-outline-light">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
        </div>
        <div class="card-body ">
            <!-- Foto Produk -->
            <div class="d-flex flex-column align-items-center mb-4 ">
                <div>
                    <img src="{{ asset('storage/' . $produk->foto) }}"
                    class="rounded img-thumbnail shadow-sm"
                    style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="text-center">
                    <h4 class="mt-3 mb-1 fw-bold">{{ $produk->nama }}</h4>
                    <span class="badge bg-success">{{ $produk->status ?? 'nonaktif' }}</span>
                </div>
            </div>

            <!-- Informasi Produk -->
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-hover text-sm">
                            <tbody>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fa fa-barcode me-2"></i>Kode Produk
                                    </th>
                                    <td>{{ $produk->kode ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fa fa-cube me-2"></i>Stok
                                    </th>
                                    <td>{{ $produk->stok ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fa fa-money-bill-wave me-2"></i>Harga Modal
                                    </th>
                                    <td>Rp {{ number_format($produk->harga_modal, 2, ',', '.') ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fa fa-money-bill me-2"></i>Harga Jual
                                    </th>
                                    <td>Rp {{ number_format($produk->harga_jual, 2, ',', '.') ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fa fa-tags me-2"></i>Kategori
                                    </th>
                                    <td>{{ $produk->kategori->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fa fa-tag me-2"></i>Merek
                                    </th>
                                    <td>{{ $produk->merek->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap">
                                        <i class="fa fa-info-circle me-2"></i>Deskripsi
                                    </th>
                                    <td>{{ $produk->deskripsi ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- <div class="text-center mt-4 ">
                <a href="{{ route('products.index') }}"
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div> --}}
        </div>
    </div>
</div>
@endsection
