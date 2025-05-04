@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
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
                                    <input class="form-control border-0 bg-light shadow-none" type="date"
                                        id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="tanggal_sampai"
                                    class="form-label text-secondary small text-uppercase fw-bold">Tanggal Sampai</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i
                                            class="far fa-calendar-alt text-maron"></i></span>
                                    <input class="form-control border-0 bg-light shadow-none" type="date"
                                        id="tanggal_sampai" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-primary px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-filter"></i> Filter Data
                                    </button>
                                    <a href="{{ route('laporan-penjualan') }}"
                                        class="btn btn-sm btn-outline-secondary px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-undo"></i> Reset
                                    </a>
                                    <a href="{{ route('laporan-penjualan.pdf', ['tanggal_mulai' => request('tanggal_mulai'), 'tanggal_sampai' => request('tanggal_sampai')]) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-danger px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Card with improved styling -->
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-maron text-white d-flex flex-wrap align-items-center justify-content-between py-3">
                        <h6 class="font-weight-bold">
                            {{-- <i class="fas fa-shopping-cart mr-2"></i> --}}
                            {{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
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
                                        <th class="text-nowrap">Cabang</th>
                                        <th class="text-nowrap">Bahan Baku</th>
                                        <th class="text-nowrap">Quantity</th>
                                        <th class="text-nowrap">Harga Satuan</th>
                                        <th class="text-nowrap">Sub Total</th>

                                        {{-- <th class="text-center">Aksi</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporan_penjualan as $item)
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
                                                        <i class="fas fa-store"></i>
                                                    </span>
                                                    <strong>
                                                        
                                                        {{ $item->penjualan->cabang->nama }}
                                                    </strong>
                                                </div>
                                            </td>
                                            <td class="align-middle font-weight-bold">{{ $item->bahanBaku->nama }}</td>
                                            <td class="align-middle font-weight-bold">
                                                <span
                                                class="badge fw-bolder bg-primary">
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
                                            {{-- <td class="align-middle text-success font-weight-bold text-nowrap">
                                                Rp. {{ number_format($item->quantity * $item->harga, 0, ',', '.') }}
                                            </td> --}}
                                            {{-- <td class="text-center align-middle">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button class="btn btn-outline-primary rounded-left"
                                                            data-toggle="modal" data-target="#detailModal{{ $item->id }}"
                                                            title="Detail">
                                                        <i class="far fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger rounded-right"
                                                            data-toggle="modal" data-target="#deleteModal{{ $item->id }}"
                                                            title="Hapus">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" style="text-align: right;">Total Pemasukkan</th>
                                        <th class="text-danger fs-5">
                                            Rp {{ number_format($laporan_penjualan->sum('sub_total'), 0, ',', '.') }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white d-flex justify-content-center py-3">
                        {{-- {{ $pembelian->appends(['tanggal' => request('tanggal')])->links('pagination::bootstrap-4') }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
