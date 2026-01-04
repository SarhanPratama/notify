@extends('layouts.master')

@section('content')

@include('layouts.breadcrumbs')
<div class="row">
    <div class="col-lg-12">
        <!-- Card with improved styling -->
        <div class="card shadow-sm border-0">
            <div
                class="card-header bg-maron text-white d-flex flex-wrap align-items-center justify-content-between py-3 gap-3">
                <h6 class="font-weight-bold text-sm">
                    {{-- <i class="fas fa-shopping-cart mr-2"></i> --}}
                    {{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
                </h6>
                <div>
                    <form method="GET" action="{{ route('piutang.index') }}">
                        <select class="form-control form-control-sm text-center border-0 bg-light shadow-none"
                            id="outlet" name="outlet"
                            onchange="this.form.submit()"
                            >
                            <option value="">Semua Outlet</option>
                            @foreach ($outlets as $outlet)
                                <option value="{{ $outlet->id }}"
                                    {{ request('outlet') == $outlet->id ? 'selected' : '' }}>
                                    {{ $outlet->nama }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped text-sm mb-0" id="dataTableHover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-nowrap">No</th>
                                <th class="text-nowrap">No. Bukti</th>
                                <th class="text-nowrap">Tanggal</th>
                                <th class="text-nowrap">Jatuh Tempo</th>
                                <th class="text-nowrap">Outlet</th>
                                <th class="text-nowrap">Sisa Piutang</th>
                                <th class="text-nowrap">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($piutang as $item)
                                <tr class="border-bottom">
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle font-weight-bold">{{ $item->nobukti }}</td>
                                    <td class="align-middle">
                                        <span class="badge badge-light p-2 text-dark">
                                            <i class="far fa-calendar-alt text-maron mr-1"></i>
                                            {{ $item->created_at->translatedFormat('l, d M Y') }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge badge-light p-2 text-dark">
                                            <i class="far fa-calendar-alt text-maron mr-1"></i>
                                            {{ $item->jatuh_tempo ? $item->jatuh_tempo->translatedFormat('l, d M Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="align-middle font-weight-bold">
                                        {{ $item->penjualan->outlet->nama }}
                                        <div class="small text-muted">
                                            {{ $item->penjualan->outlet->penanggung_jawab }}</div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-light text-maron p-2 rounded-circle mr-2">
                                                <i class="fas fa-store"></i>
                                            </span>
                                            <strong>
                                                Rp. {{ number_format($item->sisa_piutang, 0, ',', '.') }}
                                            </strong>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @if ($item->status === 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-warning">Belum Lunas</span>
                                        @endif
                                    </td>
                                    {{-- <td class="align-middle text-success font-weight-bold text-nowrap">
                                        Rp. {{ number_format($item->total_piutang, 0, ',', '.') }}
                                    </td> --}}
                                    <td class="text-center align-middle">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('piutang.show', $item->nobukti) }}"
                                                class="btn btn-outline-success" title="Detail">
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
        </div>
    </div>
</div>

@endsection
