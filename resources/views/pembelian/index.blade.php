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
                                    <a href="{{ route('pembelian.index') }}"
                                        class="btn btn-sm btn-outline-secondary px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-undo"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Card with improved styling -->
                <div>
                    <a href="{{ route('pembelian.create') }}"
                        class="btn btn-outline-primary fw-bold mb-3">
                        Tambah
                    </a>
                </div>
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
                                        <th class="text-nowrap">Supplier</th>
                                        <th class="text-nowrap">Total</th>
                                        <th class="text-nowrap">Status</th>
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
                                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                                </span>
                                            </td>
                                            <td class="align-middle font-weight-bold">{{ $item->nobukti }}</td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <span class="bg-light text-maron p-2 rounded-circle mr-2">
                                                        <i class="fas fa-building"></i>
                                                    </span>
                                                    <strong>
                                                        {{ $item->supplier->nama ?? '-' }}
                                                    </strong>
                                                </div>
                                            </td>
                                            <td class="align-middle text-success font-weight-bold text-nowrap">
                                                Rp. {{ number_format($item->total, 0, ',', '.') }}
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="badge fw-bolder {{ $item->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $item->status == 1 ? 'Success' : 'Cancel' }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        @if (is_null($item->deleted_at))
                                                            <a href="{{ route('pembelian.edit', $item->nobukti) }}"
                                                                class="btn btn-outline-warning rounded-left" title="edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>

                                                            <a href="{{ route('pembelian.show', $item->nobukti) }}"
                                                                class="btn btn-outline-success" title="Detail">
                                                                <i class="far fa-eye"></i>
                                                            </a>

                                                            <button class="btn btn-outline-danger rounded-right"
                                                                data-toggle="modal"
                                                                data-target="#deleteModal{{ $item->id }}"
                                                                title="Hapus">
                                                                <i class="far fa-trash-alt"></i>
                                                            </button>
                                                        @endif
                                                    </div>

                                                    <div class="btn-group dropleft ml-auto">
                                                        <a class="btn btn-sm border-none" data-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v fs-5" aria-hidden="true"></i>
                                                        </a>
                                                        <div class="dropdown-menu p-2">
                                                            <!-- Tambahkan dropdown menu di sini -->
                                                            @if ($item->status == 0)
                                                                <form
                                                                    action="{{ route('pembelian.restore', $item->nobukti) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button
                                                                        class="btn btn-sm btn-outline-success w-100 mb-2">Undo</button>
                                                                </form>
                                                            @endif

                                                            {{-- <a class="dropdown-item"
                                                                href="{{ route('pembelian.restore', $item->nobukti) }}">Undo</a> --}}
                                                            <button class="btn btn-sm btn-outline-danger w-100"
                                                                data-toggle="modal"
                                                                data-target="#deletePermanenModal{{ $item->id }}"
                                                                title="Hapus">
                                                                Hapus Permanen
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @include('pembelian.delete-permanent')
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
