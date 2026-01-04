@extends('layouts.master')

@section('content')

@include('layouts.breadcrumbs')
<div class="row">
    <div class="col col-lg-12">
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
                            <th>No Bukti</th>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>Posisi Kas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kas as $item)
                            <tr>
                                <td class="align-middle text-nowrap">{{ $loop->iteration }}</td>
                                <td class="align-middle text-nowrap">{{ $item->nobukti }}</td>
                                <td class="align-middle text-nowrap">
                                    <span class="badge badge-light p-2 text-dark">
                                        <i class="far fa-calendar-alt text-maron mr-1"></i>
                                        {{ $item->tanggal->format('d M Y') }} - {{ $item->tanggal->translatedFormat('l') }}
                                    </span>
                                </td>
                                <td class="align-middle text-nowrap">
                                    @if ($item->kategoriKeuangan->jenis === 'pengeluaran')
                                        <span class="badge fw-bolder bg-danger">
                                            Pengeluaran
                                        </span>
                                    @else
                                        <span class="badge fw-bolder bg-success">
                                            Pemasukan
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle text-nowrap"> {{ $item->kategoriKeuangan->nama }}</td>
                                <td class="align-middle text-nowrap text-success fw-bold">Rp.
                                    {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                <td class="align-middle text-nowrap">{{ $item->deskripsi }}</td>
                                <td class="align-middle text-nowrap">{{ $item->posisi_kas }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
