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
                        <div class="row g-3 align-items-end">
                            <div class="col-md-8">
                                <label class="form-label text-secondary small text-uppercase fw-bold">Status</label>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.pesanan.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status == 'pending' ? 'btn-primary' : 'btn-outline-primary' }} px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-clock"></i> Pending
                                    </a>
                                    <a href="{{ route('admin.pesanan.index', ['status' => 'approved']) }}" class="btn btn-sm {{ $status == 'approved' ? 'btn-success' : 'btn-outline-success' }} px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-check"></i> Approved
                                    </a>
                                    <a href="{{ route('admin.pesanan.index', ['status' => 'completed']) }}" class="btn btn-sm {{ $status == 'completed' ? 'btn-info' : 'btn-outline-info' }} px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-check-double"></i> Completed
                                    </a>
                                    <a href="{{ route('admin.pesanan.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }} px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-times"></i> Rejected
                                    </a>
                                    <a href="{{ route('admin.pesanan.index', ['status' => 'all']) }}" class="btn btn-sm {{ $status == 'all' ? 'btn-secondary' : 'btn-outline-secondary' }} px-4 py-2 d-flex align-items-center gap-2 fw-medium">
                                        <i class="fas fa-list"></i> All
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card with improved styling -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-maron text-white d-flex flex-wrap align-items-center justify-content-between py-3">
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
                                        <th class="text-nowrap">Items</th>
                                        <th class="text-nowrap">Total</th>
                                        <th class="text-nowrap">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr class="border-bottom">
                                            <td class="align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">
                                                <span class="badge badge-light p-2 text-dark">
                                                    <i class="far fa-calendar-alt text-maron mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                                                </span>
                                            </td>
                                            <td class="align-middle font-weight-bold">{{ $order->nobukti }}</td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <span class="bg-light text-maron p-2 rounded-circle mr-2">
                                                        <i class="fas fa-store"></i>
                                                    </span>
                                                    <strong>{{ $order->cabang->nama ?? '-' }}</strong>
                                                </div>
                                            </td>
                                            <td class="align-middle">{{ $order->mutasi->count() }}</td>
                                            <td class="align-middle text-success font-weight-bold text-nowrap">
                                                Rp. {{ number_format($order->total, 2, ',', '.') }}
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge bg-{{ $order->status == 'pending' ? 'warning text-dark' : ($order->status == 'approved' ? 'success' : ($order->status == 'completed' ? 'info text-dark' : 'danger')) }}">{{ ucfirst($order->status) }}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.pesanan.show', $order->id) }}" class="btn btn-outline-success" title="Lihat">
                                                        <i class="far fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white d-flex justify-content-center py-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
