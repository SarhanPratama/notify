@extends('layouts.master')

@section('content')

@include('layouts.breadcrumbs')

<div class="row mb-3">
    <!-- Total Piutang Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center px-2">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Piutang
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($totalPiutang, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Terbayar Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center px-2">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Terbayar
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($totalTerbayar, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sisa (Outstanding) Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center px-2">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Sisa Piutang
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($totalSisa, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-maron">
                <div class="d-flex align-items-center">
                    <h6 class="font-weight-bold text-white mb-0">Filter Laporan Piutang</h6>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.piutang') }}" method="GET" class="row align-items-end">
                    <div class="col-md-5">
                        <label for="outlet" class="form-label">Pilih Outlet</label>
                        <select class="form-control" id="outlet" name="outlet" onchange="this.form.submit()">
                            <option value="all" {{ $outlet_id == 'all' ? 'selected' : '' }}>Semua Outlet</option>
                            @foreach($outlets as $o)
                                <option value="{{ $o->id }}" {{ $outlet_id == $o->id ? 'selected' : '' }}>{{ $o->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="status" class="form-label">Status Pembayaran</label>
                        <select class="form-control" id="status" name="status" onchange="this.form.submit()">
                            <option value="all" {{ $status_filter == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="belum_lunas" {{ $status_filter == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas (Sisa Piutang)</option>
                            <option value="lunas" {{ $status_filter == 'lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <a href="{{ route('laporan.piutang.pdf', ['outlet' => $outlet_id, 'status' => $status_filter]) }}" target="_blank" class="btn btn-outline-danger w-100">
                            <i class="fas fa-file-pdf"></i> Cetak PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-maron text-white py-3">
                <h6 class="font-weight-bold text-sm mb-0">
                    <i class="fas fa-file-alt mr-2"></i> Data Laporan Piutang
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped text-sm mb-0" id="dataTableHover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-nowrap">No</th>
                                <th class="text-nowrap">No. Bukti</th>
                                <th class="text-nowrap">Tanggal Bon</th>
                                <th class="text-nowrap">Outlet</th>
                                <th class="text-nowrap text-right">Nilai Piutang</th>
                                <th class="text-nowrap text-right">Terbayar</th>
                                <th class="text-nowrap text-right">Sisa / Outstanding</th>
                                <th class="text-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($piutang as $item)
                                @php
                                    $terbayar = $item->jumlah_piutang - $item->sisa_piutang;
                                @endphp
                                <tr class="border-bottom">
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle font-weight-bold">{{ $item->nobukti }}</td>
                                    <td class="align-middle">
                                        {{ $item->penjualan->created_at->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->penjualan->outlet->nama }}
                                    </td>
                                    <td class="align-middle text-right">
                                        Rp {{ number_format($item->jumlah_piutang, 0, ',', '.') }}
                                    </td>
                                    <td class="align-middle text-right text-success">
                                        Rp {{ number_format($terbayar, 0, ',', '.') }}
                                    </td>
                                    <td class="align-middle text-right text-danger font-weight-bold">
                                        Rp {{ number_format($item->sisa_piutang, 0, ',', '.') }}
                                    </td>
                                    <td class="align-middle">
                                        @if ($item->status === 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-danger">Belum Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Data piutang tidak ditemukan untuk kriteria ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
