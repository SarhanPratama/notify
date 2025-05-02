@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Filter Form -->
                <div class="card shadow-sm border-0 mb-4 rounded-lg overflow-hidden">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                        <h6 class="font-weight-bold text-primary mb-2">Filter Data</h6>
                    </div>
                    <div class="card-body bg-white p-4">
                        <form method="GET" action="{{ route('pembelian.index') }}" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="tanggal_mulai" class="form-label text-secondary small text-uppercase fw-bold">Tanggal Mulai</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="far fa-calendar-alt text-maron"></i></span>
                                    <input class="form-control border-0 bg-light shadow-none" type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="tanggal_sampai" class="form-label text-secondary small text-uppercase fw-bold">Tanggal Sampai</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="far fa-calendar-alt text-maron"></i></span>
                                    <input class="form-control border-0 bg-light shadow-none" type="date" id="tanggal_sampai" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-filter"></i> Filter Data
                                    </button>
                                    <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-outline-secondary px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-undo"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Card with improved styling -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-maron text-white d-flex flex-wrap align-items-center justify-content-between py-3">
                        <h6 class="font-weight-bold text-sm">
                            {{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
                        </h6>
                        <div>
                            <a href="{{ route('pembelian.create') }}"
                               class="btn btn-primary btn-sm text-light font-weight-bold d-flex align-items-center gap-1">
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
                                        <th class="text-nowrap">Supplier</th>
                                        <th class="text-nowrap">Total</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembelian as $item)
                                        <tr class="border-bottom">
                                            <td class="align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">
                                                <span class="badge badge-light p-2 text-dark">
                                                    <i class="far fa-calendar-alt text-maron mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                                </span>
                                            </td>
                                            <td class="align-middle font-weight-bold">{{ $item->nobukti }}</td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <span class="bg-light text-maron p-2 rounded-circle mr-2">
                                                        <i class="fas fa-building"></i>
                                                    </span>
                                                    <strong>

                                                        {{ $item->supplier->nama }}
                                                    </strong>
                                                </div>
                                            </td>
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
                                                </div>
                                            </td>
                                        </tr>
                                        @include('pembelian.show')
                                        @include('pembelian.destroy')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white d-flex justify-content-center py-3">
                        {{-- {{ $pembelian->appends(['tanggal_mulai' => request('tanggal_mulai'), 'tanggal_sampai' => request('tanggal_sampai')])->links('pagination::bootstrap-4') }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @section('styles')
        <style>
            .form-control {
                transition: border-color 0.3s ease, box-shadow 0.3s ease;
            }
            .form-control:focus {
                border-color: #007bff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }
            .btn.transition-all {
                transition: all 0.3s ease;
            }
            .btn.transition-all:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .card-body {
                background: #f8f9fa;
                border-radius: 0.5rem;
            }
        </style>
    @endsection --}}
@endsection
