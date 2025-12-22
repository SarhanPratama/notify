@extends('layouts.master')

@section('content')

<div class="container-fluid">
    @include('layouts.breadcrumbs')
     <div class="row">
        <div class="col col-lg-12">
            <!-- Pending Approval Table -->
            @if ($pembelianPending->count() > 0)
                <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-warning">
                            <h6 class="font-weight-bold text-white text-sm">Menunggu Persetujuan Pembelian</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped text-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>No Bukti</th>
                                        <th>Supplier</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembelianPending as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td class="font-weight-bold">{{ $item->nobukti }}</td>
                                            <td>{{ $item->supplier->nama ?? '-' }}</td>
                                            <td class="text-danger fw-bold">Rp.
                                                {{ number_format($item->total, 0, ',', '.') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info fw-bold" data-toggle="modal"
                                                    data-target="#detailModal{{ $item->id }}">
                                                    Detail
                                                </button>
                                                <button class="btn btn-sm btn-outline-success fw-bold" data-toggle="modal"
                                                    data-target="#approveModal{{ $item->id }}">
                                                    Setujui
                                                </button>
                                                <form action="{{ route('pengeluaran.reject-pembelian', $item->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Tolak pembelian ini?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger fw-bold">
                                                        Tolak
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        @include('pengeluaran.modal-approve')
                                        {{-- @include('pengeluaran.modal-detail') --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Simple Tables -->
                <button type="button" class="btn btn-outline-danger mb-3 fw-bold" data-toggle="modal"
                    data-target="#createPengeluaranModal">
                    Tambah Pengeluaran
                </button>
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="dataTableHover">
                            <thead class="thead-light">
                                <tr class="text-nowrap">
                                    <th class="text-start">No</th>
                                    <th>Tanggal</th>
                                    <th>No Bukti</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Posisi Kas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengeluaran as $item)
                                    <tr>
                                        <td class="align-middle text-nowrap">{{ $loop->iteration }}</td>
                                        <td class="align-middle text-nowrap">
                                            <span class="badge badge-light p-2 text-dark">
                                                <i class="far fa-calendar-alt text-maron mr-1"></i>
                                                {{ $item->tanggal->translatedFormat('l, d M Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-nowrap font-weight-bold">{{ $item->nobukti }}</td>
                                        <td class="align-middle text-nowrap font-weight-bold">{{ $item->kategoriKeuangan->nama ?? '-' }}</td>
                                        <td class="align-middle text-nowrap text-danger fw-bold">Rp.
                                            {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                            <td class="align-middle text-nowrap">{!! $item->deskripsi !!}</td>
                                            <td class="align-middle text-nowrap font-weight-bold">{{ $item->posisi_kas ?? '-' }}</td>
                                        <td class="align-middle text-nowrap">
                                             @if (Str::startsWith($item->nobukti, 'OUT'))
                                            <button class="btn btn-sm btn-warning text-white fw-bold" data-toggle="modal"
                                                data-target="#editPengeluaranModal{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif
                                            <button class="btn btn-sm btn-danger fw-bold" data-toggle="modal"
                                                data-target="#deletePengeluaranModal{{ $item->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
    @foreach ($pembelianPending as $item)
        @include('pengeluaran.modal-detail-pembelian')
    @endforeach
    @foreach ($pengeluaran as $item)
        @include('pengeluaran.modal-edit')
        @include('pengeluaran.modal-delete')
    @endforeach
    @include('pengeluaran.modal-create')
@endsection
