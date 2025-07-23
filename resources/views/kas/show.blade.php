@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <!-- ===== HEADER HALAMAN ===== -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary font-weight-bold">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </button>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <!-- INFO TRANSAKSI -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-success text-white py-3">
                        <h6 class="mb-0 font-weight-bold">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informasi Transaksi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <small class="text-muted font-weight-bold">Tanggal</small>
                            </div>
                            <div class="col-sm-8">
                                <span class="font-weight-medium">{{ $transaksi->tanggal->format('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <small class="text-muted font-weight-bold">Tipe</small>
                            </div>
                            <div class="col-sm-8">
                                <span class="badge {{ $transaksi->tipe === 'credit' ? 'badge-danger' : 'badge-success' }} px-3 py-2">
                                    {{ ucfirst($transaksi->tipe) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <small class="text-muted font-weight-bold">Jumlah</small>
                            </div>
                            <div class="col-sm-8">
                                <span class="font-weight-bold text-primary h5">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <small class="text-muted font-weight-bold">Kas Masuk</small>
                            </div>
                            <div class="col-sm-8">
                                <span>{{ $transaksi->sumberDana->nama ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <small class="text-muted font-weight-bold">Deskripsi</small>
                            </div>
                            <div class="col-sm-8">
                                <span>{{ $transaksi->deskripsi }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFO CABANG/SUPPLIER -->
            <div class="col-lg-6 mb-4">
                @if ($transaksi->referenceable instanceof App\Models\Penjualan)
                    @php $referensi = $transaksi->referenceable; @endphp
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-success text-white py-3">
                            <h6 class="mb-0 font-weight-bold">
                                <i class="fas fa-store mr-2"></i>
                                Informasi Outlet
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <small class="text-muted font-weight-bold">Nama Outlet</small>
                                </div>
                                <div class="col-sm-8">
                                    <span class="font-weight-bold">{{ $referensi->cabang->nama ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <small class="text-muted font-weight-bold">Telepon</small>
                                </div>
                                <div class="col-sm-8">
                                    <span>{{ $referensi->cabang->telepon ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <small class="text-muted font-weight-bold">Alamat</small>
                                </div>
                                <div class="col-sm-8">
                                    <span>{{ $referensi->cabang->alamat ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <small class="text-muted font-weight-bold">No Bukti</small>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge badge-outline-info px-3 py-2">{{ $referensi->nobukti }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($transaksi->referenceable instanceof App\Models\Pembelian)
                    @php $referensi = $transaksi->referenceable; @endphp
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-success text-white py-3">
                            <h6 class="mb-0 font-weight-bold">
                                <i class="fas fa-truck mr-2"></i>
                                Informasi Supplier
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <small class="text-muted font-weight-bold">Nama Supplier</small>
                                </div>
                                <div class="col-sm-8">
                                    <span class="font-weight-bold">{{ $referensi->supplier->nama ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <small class="text-muted font-weight-bold">Telepon</small>
                                </div>
                                <div class="col-sm-8">
                                    <span>{{ $referensi->supplier->telepon ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <small class="text-muted font-weight-bold">Alamat</small>
                                </div>
                                <div class="col-sm-8">
                                    <span>{{ $referensi->supplier->alamat ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <small class="text-muted font-weight-bold">No Bukti</small>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge badge-outline-warning px-3 py-2">{{ $referensi->nobukti }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- ===== RINCIAN ITEM ===== -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white py-3">
                        <h6 class="mb-0 font-weight-bold">
                            <i class="fas fa-boxes mr-2"></i>
                            Rincian Produk
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-top-0 font-weight-bold px-4 py-3">Produk</th>
                                        <th class="border-top-0 font-weight-bold text-center py-3">Kuantitas</th>
                                        <th class="border-top-0 font-weight-bold text-right py-3">Harga Satuan</th>
                                        <th class="border-top-0 font-weight-bold text-right py-3 pr-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $items = $referensi->mutasi ?? []; @endphp
                                    @forelse ($items as $item)
                                        <tr>
                                            <td class="px-4 py-3 align-middle">
                                                <div class="font-weight-medium">{{ $item->bahanBaku->nama ?? 'Produk Dihapus' }}</div>
                                                @if($item->bahanBaku->deskripsi)
                                                    <small class="text-muted">{{ $item->bahanBaku->deskripsi }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center py-3 align-middle">
                                                <span class="badge badge-secondary px-3 py-2">
                                                    {{ $item->quantity }} {{ $item->bahanBaku->satuan->nama ?? '' }}
                                                </span>
                                            </td>
                                            <td class="text-right py-3 align-middle">
                                                <span class="font-weight-medium">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="text-right py-3 align-middle pr-4">
                                                <span class="font-weight-bold text-success">Rp {{ number_format($item->sub_total, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div class="py-4">
                                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted h6 mb-0">Tidak ada produk dalam transaksi ini.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if(count($items) > 0)
                                    <tfoot class="bg-light">
                                        <tr>
                                            <th colspan="3" class="text-right font-weight-bold px-4 py-3">Total:</th>
                                            <th class="text-right font-weight-bold text-success pr-4 py-3">
                                                Rp {{ number_format($items->sum('sub_total'), 0, ',', '.') }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge-outline-info {
            color: #17a2b8;
            border: 1px solid #17a2b8;
            background: transparent;
        }
        .badge-outline-warning {
            color: #ffc107;
            border: 1px solid #ffc107;
            background: transparent;
        }
        .font-weight-medium {
            font-weight: 500;
        }
        .card {
            border-radius: 0.5rem;
        }
        .card-header {
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }
        @media print {
            .btn {
                display: none !important;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
        }
    </style>
@endsection
