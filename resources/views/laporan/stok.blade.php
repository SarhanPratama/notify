@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container">
        <div class="row">
            <div class="col col-lg-12">
                <!-- Simple Tables -->

                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
                        </h6>
                        <div>
                            <a href="{{ route('laporan-stok.exportExcel') }}" target="_blank" class="btn btn-outline-light btn-sm btn-lg">
                                Export Excel
                            </a>
                            <a href="{{ route('laporan-stok.exportPdf') }}" target="_blank" class="btn btn-outline-light btn-sm btn-lg">
                                Export PDF
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="dataTableHover">
                            <thead class="thead-light">
                                <tr class="text-nowrap">
                                    <th class="text-start">No</th>
                                    <th>Bahan Baku </th>
                                    <th>Stok</th>
                                    <th>Stok Masuk</th>
                                    <th>Stok Keluar</th>
                                    <th>Stok Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporan_stok as $item)
                                    <tr class="text-start text-nowrap ">
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ ucwords($item->nama) }}</td>
                                        <td class="align-middle"><span class="badge fw-bolder bg-primary">{{ $item->stok_awal }}
                                                {{ $item->nama_satuan }}</span></td>
                                        <td class="align-middle"><span class="badge fw-bolder bg-success">{{ $item->totalmasuk }}
                                            {{ $item->nama_satuan }}</span></td>
                                        <td class="align-middle"><span class="badge fw-bolder bg-danger">{{ $item->totalkeluar }}
                                            {{ $item->nama_satuan }}</span></td>
                                        <td class="align-middle"><span class="badge fw-bolder bg-warning">{{ $item->saldoakhir }}
                                            {{ $item->nama_satuan }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer d-flex justify-content-center">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

