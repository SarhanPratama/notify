@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                {{-- Filter Card --}}
                <div class="card shadow-sm border-0 mb-4 rounded-lg overflow-hidden">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                        <h6 class="font-weight-bold text-primary mb-2">Filter Data</h6>
                    </div>
                    <div class="card-body bg-white p-4">
                        <form method="GET" action="{{ route('laporan-pembelian') }}" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="tanggal_mulai"
                                    class="form-label text-secondary small text-uppercase fw-bold">Tanggal Mulai</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i
                                            class="far fa-calendar-alt text-maron"></i></span>
                                    {{-- Use $tanggalMulai from controller for consistency, or request() --}}
                                    <input class="form-control border-0 bg-light shadow-none" type="date"
                                        id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai ?? request('tanggal_mulai') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="tanggal_sampai"
                                    class="form-label text-secondary small text-uppercase fw-bold">Tanggal Sampai</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i
                                            class="far fa-calendar-alt text-maron"></i></span>
                                    {{-- Use $tanggalSampai from controller for consistency, or request() --}}
                                    <input class="form-control border-0 bg-light shadow-none" type="date"
                                        id="tanggal_sampai" name="tanggal_sampai" value="{{ $tanggalSampai ?? request('tanggal_sampai') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-primary px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-filter"></i> Filter Data
                                    </button>
                                    <a href="{{ route('laporan-pembelian') }}"
                                        class="btn btn-sm btn-outline-secondary px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-undo"></i> Reset
                                    </a>
                                    {{-- Conditionally enable/construct PDF link --}}
                                    @if($showTable && $laporan_pembelian->isNotEmpty())
                                        <a href="{{ route('laporan-pembelian.pdf', ['tanggal_mulai' => $tanggalMulai, 'tanggal_sampai' => $tanggalSampai]) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-danger px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                            <i class="fas fa-file-pdf"></i> Export PDF
                                        </a>
                                    @else
                                        {{-- <button type="button" title="Filter data terlebih dahulu untuk export PDF"
                                            class="btn btn-sm btn-outline-danger px-4 py-2 d-flex align-items-center gap-2 fw-medium" disabled>
                                            <i class="fas fa-file-pdf"></i> Export PDF
                                        </button> --}}
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Conditionally display table or prompt message --}}
                @if($showTable)
                    <div class="card shadow-sm border-0">
                        <div
                            class="card-header bg-maron text-white d-flex flex-wrap align-items-center justify-content-between py-3">
                            <h6 class="font-weight-bold">
                                {{-- Displaying "Laporan Pembelian" or the last breadcrumb label --}}
                                Laporan Pembelian ({{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalSampai)->format('d M Y') }})
                                {{-- Or use breadcrumb: {{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }} --}}
                            </h6>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped text-sm mb-0" id="dataTableHover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-nowrap">No</th>
                                            <th class="text-nowrap">Tanggal</th>
                                            <th class="text-nowrap">No. Bukti</th>
                                            <th class="text-nowrap">Supplier</th>
                                            <th class="text-nowrap">Bahan Baku</th>
                                            <th class="text-nowrap">Quantity</th>
                                            <th class="text-nowrap">Harga Satuan</th>
                                            <th class="text-nowrap">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($laporan_pembelian as $item)
                                            <tr class="border-bottom">
                                                <td class="align-middle">{{ $loop->iteration }}</td>
                                                <td class="align-middle">
                                                    <span class="badge badge-light p-2 text-dark">
                                                        <i class="far fa-calendar-alt text-maron mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                                    </span>
                                                </td>
                                                <td class="align-middle font-weight-bold">{{ $item->nobukti }}</td>
                                                <td class="align-middle font-weight-bold">
                                                    <div class="d-flex align-items-center">
                                                        <span class="bg-light text-maron p-2 rounded-circle mr-2">
                                                            <i class="fas fa-building"></i>
                                                        </span>
                                                        <strong>
                                                            {{ $item->pembelian->supplier->nama ?? '-' }}
                                                        </strong>
                                                    </div>
                                                </td>
                                                <td class="align-middle font-weight-bold">{{ $item->bahanBaku->nama }}</td>
                                                <td class="align-middle font-weight-bold">
                                                    <span class="badge fw-bolder bg-primary">
                                                        {{ $item->quantity }}
                                                        {{ $item->bahanBaku->satuan->nama }}
                                                    </span>
                                                </td>
                                                <td class="align-middle text-success font-weight-bold text-nowrap">
                                                    Rp. {{ number_format($item->harga, 0, ',', '.') }}
                                                </td>
                                                <td class="align-middle text-success font-weight-bold text-nowrap">
                                                    Rp. {{ number_format($item->sub_total, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @empty
                                            {{-- This row will be shown if $laporan_pembelian is empty AFTER filtering --}}
                                            <tr>
                                                <td colspan="8" class="text-center p-4">
                                                    <div class="alert alert-info mb-0">
                                                        <i class="fas fa-info-circle mr-2"></i>
                                                        Tidak ada data pembelian yang ditemukan untuk rentang tanggal yang dipilih.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    {{-- Table footer: Only show if there are items --}}
                                    @if($laporan_pembelian->isNotEmpty())
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" style="text-align: right;" class="fw-bold">Total Pengeluaran</th>
                                            <th class="text-danger fs-5 fw-bold text-nowrap">
                                                Rp {{ number_format($laporan_pembelian->sum('sub_total'), 0, ',', '.') }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Message to show when table is not displayed (before filtering or if dates are invalid) --}}
                    <div class="card shadow-sm border-0 mb-4 rounded-lg overflow-hidden">
                        <div class="card-body bg-white p-5 text-center">
                            <i class="fas fa-filter fa-3x text-primary mb-3"></i>
                            <h5 class="font-weight-bold text-primary mb-2">Laporan Pembelian</h5>
                            <p class="text-secondary">Silakan pilih rentang tanggal dan klik "Filter Data" untuk menampilkan laporan.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
