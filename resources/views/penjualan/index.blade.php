@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Card with improved styling -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-maron text-white d-flex flex-wrap align-items-center justify-content-between py-3">
                        <h6 class="font-weight-bold">
                            {{-- <i class="fas fa-shopping-cart mr-2"></i> --}}
                            {{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
                        </h6>

                        <div class="d-flex flex-wrap align-items-center">
                            <form action="{{ route('penjualan.index') }}" method="GET" class="form-inline mr-3">
                                <div class="input-group input-group-sm">
                                    <input type="date" name="tanggal" class="form-control border-0 shadow-sm"
                                           value="{{ request('tanggal') }}" style="border-radius: 0.25rem 0 0 0.25rem;">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-light border-0 shadow-sm"
                                                >
                                            <i class="fas fa-filter text-light"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <a href="{{ route('penjualan.create') }}"
                               class="btn btn-primary btn-sm text-light font-weight-bold">
                                Tambah
                            </a>
                        </div>
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
                                        <th class="text-nowrap">Total</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penjualan as $item)
                                        <tr class="border-bottom">
                                            <td class="align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                            </td>
                                            <td class="align-middle font-weight-bold">{{ $item->nobukti }}</td>
                                            <td class="align-middle">{{ $item->cabang->nama }}</td>
                                            <td class="align-middle text-success font-weight-bold text-nowrap">
                                                Rp. {{ number_format($item->total, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center align-middle">
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
                                                    <a href="{{ route('penjualan.struk', $item->id)}}">struk</a>
                                                </div>
                                            </td>
                                        </tr>
                                        @include('penjualan.show')
                                        @include('pembelian.destroy')
                                    @endforeach
                                </tbody>
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
