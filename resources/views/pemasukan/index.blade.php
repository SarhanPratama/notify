@extends('layouts.master')

@section('content')

    <div class="container-fluid">
        @include('layouts.breadcrumbs')
        <div class="row">
            @if (isset($penjualanPending) && $penjualanPending->count() > 0)
                <div class="col col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-warning">
                            <h6 class="font-weight-bold text-white text-sm">Menunggu Persetujuan Penjualan</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped text-sm">
                                <thead class="thead-light">
                                    <tr class="text-nowrap">
                                        <th>Tanggal</th>
                                        <th>No Bukti</th>
                                        <th>Outlet</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penjualanPending as $item)
                                        <tr>
                                            <td class="align-middle">
                                                <span class="badge badge-light p-2 text-dark">
                                                    {{ $item->created_at->translatedFormat('l, d M Y') }}
                                                </span>
                                            </td>
                                            <td class="align-middle font-weight-bold">{{ $item->nobukti }}</td>
                                            <td class="align-middle">{{ $item->outlet->nama ?? '-' }}</td>
                                            <td class="align-middle font-weight-bold text-success">Rp.
                                                {{ number_format($item->total, 0, ',', '.') }}</td>
                                            <td class="align-middle">
                                                <button class="btn btn-sm btn-outline-info fw-bold" data-toggle="modal"
                                                    data-target="#detailModal{{ $item->id }}">
                                                    Detail
                                                </button>
                                                <button class="btn btn-sm btn-outline-success fw-bold" data-toggle="modal"
                                                    data-target="#approvePenjualanModal{{ $item->id }}">
                                                    Approve
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger fw-bold" data-toggle="modal"
                                                    data-target="#rejectPenjualanModal{{ $item->id }}">
                                                    Reject
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col col-lg-12">
                <!-- Simple Tables -->
                <button type="button" class="btn btn-outline-primary mb-3 fw-bold" data-toggle="modal"
                    data-target="#createPemasukanModal">
                    Tambah Pemasukan
                </button>
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">
                            {{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
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
                                @foreach ($pemasukan as $item)
                                    <tr>
                                        <td class="align-middle text-nowrap">{{ $loop->iteration }}</td>
                                        <td class="align-middle text-nowrap">
                                            <span class="badge badge-light p-2 text-dark">
                                                <i class="far fa-calendar-alt text-maron mr-1"></i>
                                                {{ $item->tanggal->translatedFormat('l, d M Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-nowrap font-weight-bold">{{ $item->nobukti }}</td>
                                        <td class="align-middle font-weight-bold">
                                            {{ $item->kategoriKeuangan->nama ?? '-' }}</td>

                                        <td class="align-middle text-nowrap text-success fw-bold">Rp.
                                            {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                        <td class="align-middle text-nowrap">{!! $item->deskripsi !!}</td>
                                        <td class="align-middle text-nowrap">{{ $item->posisi_kas }}</td>
                                        <td class="align-middle text-nowrap">
                                            @if (Str::startsWith($item->nobukti, 'IN'))
                                                <button class="btn btn-sm btn-warning text-white fw-bold"
                                                    data-toggle="modal"
                                                    data-target="#editPemasukanModal{{ $item->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-danger fw-bold" data-toggle="modal"
                                                data-target="#deletePemasukanModal{{ $item->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @include('pemasukan.modal-edit')
                                    @include('pemasukan.modal-delete')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pemasukan.modal-create')

    @foreach ($penjualanPending as $item)
        @include('pemasukan.modal-detail-penjualan')
        @include('pemasukan.modal-approve')
        @include('pemasukan.modal-reject')
    @endforeach


    <script>
        function togglePaymentOptions(id) {
            var type = document.getElementById('payment_type_' + id).value;
            var jatuhTempoGroup = document.getElementById('jatuh_tempo_group_' + id);

            if (type === 'kasbon') {
                jatuhTempoGroup.classList.remove('d-none');
            } else {
                jatuhTempoGroup.classList.add('d-none');
            }
        }
    </script>
@endsection
