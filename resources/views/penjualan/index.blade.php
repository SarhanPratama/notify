@extends('layouts.master')

@section('content')

@include('layouts.breadcrumbs')
<div class="row">
    <div class="col-lg-12">
        {{--
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="font-weight-bold text-primary mb-0">
                    <i class="fas fa-filter mr-2"></i>Filter Data
                </h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('penjualan.index') }}">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="tanggal_mulai" class="form-label fw-bold">Tanggal Mulai</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-calendar-alt text-maron"></i></span>
                                <input class="form-control" type="date" id="tanggal_mulai" name="tanggal_mulai"
                                    value="{{ request('tanggal_mulai') }}">
                            </div>
                        </div>
                        <div class="col-md-5 mb-2 mb-md-0">
                            <label for="tanggal_sampai" class="form-label fw-bold">Tanggal Sampai</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-calendar-alt text-maron"></i></span>
                                <input class="form-control" type="date" id="tanggal_sampai" name="tanggal_sampai"
                                    value="{{ request('tanggal_sampai') }}">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-outline-primary w-100 mb-2 mb-md-0">
                                Filter
                            </button>
                        </div>
                    </div>
                    @if (request('tanggal_mulai') || request('tanggal_sampai'))
                        <div class="row mt-3">
                            <div class="col-12">
                                <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-outline-secondary">
                                    Reset
                                </a>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div> --}}
        {{-- <!-- <a href="{{ route('penjualan.create') }}" class="btn btn-outline-primary fw-bold mb-3">
            Tambah
        </a> --> --}}
        <!-- Card with improved styling -->
        <div class="card shadow-sm border-0">
            <div
                class="card-header bg-maron text-white d-flex flex-wrap align-items-center justify-content-between py-3">
                <h6 class="font-weight-bold text-sm">
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
                                <th class="text-nowrap">Outlet</th>
                                <th class="text-nowrap">Total</th>
                                <th class="text-nowrap">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penjualan as $item)
                                <tr class="border-bottom">
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">
                                        <span class="badge badge-light p-2 text-dark">
                                            <i class="far fa-calendar-alt text-maron mr-1"></i>
                                            {{ $item->tanggal->translatedFormat('l, d M Y') }}
                                        </span>
                                    </td>
                                    <td class="align-middle font-weight-bold">{{ $item->nobukti }}</td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-light text-maron p-2 rounded-circle mr-2">
                                                <i class="fas fa-store"></i>
                                            </span>
                                            <strong>
                                                {{ $item->outlet->nama }}
                                            </strong>
                                        </div>
                                    </td>
                                    <td class="align-middle text-success font-weight-bold text-nowrap">
                                        Rp. {{ number_format($item->total, 0, ',', '.') }}
                                    </td>
                                    <td class="align-middle">
                                        @if ($item->status_gudang === 'approved' && $item->status_keuangan === 'pending')
                                            <span class="badge bg-success">Gudang: Approved</span>
                                        @elseif($item->status_gudang === 'rejected')
                                            <span class="badge bg-danger">Gudang: Rejected</span>
                                        @elseif($item->status_gudang === 'pending')
                                            <span class="badge bg-warning">Gudang: Pending</span>
                                        @elseif ($item->status_keuangan === 'approved' && $item->status_gudang === 'approved' && $item->status_pembayaran !== 'lunas')
                                            <span class="badge bg-success">Keuangan: Approved</span>
                                        @elseif($item->status_keuangan === 'rejected')
                                            <span class="badge bg-danger">Keuangan: Rejected</span>
                                        @elseif($item->status_keuangan === 'pending')
                                            <span class="badge bg-warning">Keuangan: Pending</span>
                                        @elseif($item->status_pembayaran === 'lunas' && $item->status_keuangan === 'approved' && $item->status_gudang === 'approved')
                                            <span class="badge bg-success">Pembayaran: Lunas</span>
                                        @endif
                                    </td>

                                    <td class="text-center align-middle">
                                        <div class="btn-group btn-group-sm" role="group">
                                            {{-- <a href="{{ route('penjualan.edit', $item->nobukti) }}"
                                                class="btn btn-sm btn-outline-warning rounded-left" title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </a> --}}
                                            <a href="{{ route('penjualan.show', $item->nobukti) }}"
                                                class="btn btn-outline-success" title="Detail">
                                                <i class="far fa-eye"></i>
                                            </a>
                                            @if ($item->status !== 'approved' && $item->status !== 'completed')
                                                <button class="btn btn-outline-danger rounded-right"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal{{ $item->id }}" title="Hapus">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                {{-- @include('penjualan.show') --}}
                                @include('penjualan.destroy')
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
