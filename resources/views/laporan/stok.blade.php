@extends('layouts.master')

@section('content')

        @include('layouts.breadcrumbs')
        <div class="row">
            <div class="col col-lg-12">
                <!-- Simple Tables -->
                <div class="mb-3">
                    <div>
                        <a href="{{ route('laporan-stok.exportExcel', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                            target="_blank" class="btn btn-outline-success">
                            Export Excel
                        </a>
                    </div>

                </div>
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
                        </h6>
                        <form method="GET" action="{{ route('laporan-stok') }}">
                            <div class="d-flex gap-2">

                                <select class="form-control form-control-sm border-0 bg-light shadow-none" id="bulan" name="bulan"
                                    onchange="this.form.submit()">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                                <input class="form-control form-control-sm border-0 bg-light shadow-none" type="number" id="tahun"
                                    name="tahun" value="{{ $tahun }}" min="2020"
                                    onchange="this.form.submit()">
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="dataTableHover">
                            <thead class="thead-light">
                                <tr class="text-nowrap">
                                    <th class="text-start">No</th>
                                    <th>Bahan Baku </th>
                                    <th>Stok Awal</th>
                                    <th>Stok Masuk</th>
                                    <th>Stok Keluar</th>
                                    <th>Stok Akhir</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($laporan_stok as $item)
                                    <tr class="text-start text-nowrap ">
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">
                                            @can('laporan-kartu-stok')
                                                <a href="{{ route('laporan.kartu-stok', ['id_bahan_baku' => $item->id_bahan_baku]) }}"
                                                    class="text-decoration-none">
                                                    {{ ucwords($item->nama) }}
                                                </a>
                                            @else
                                                {{ ucwords($item->nama) }}
                                            @endcan
                                        </td>
                                        <td class="align-middle"><span
                                                class="badge fw-bolder bg-primary">{{ $item->stok_awal }}
                                                {{ $item->nama_satuan }}</span></td>
                                        <td class="align-middle"><span
                                                class="badge fw-bolder bg-success">{{ $item->total_masuk }}
                                                {{ $item->nama_satuan }}</span></td>
                                        <td class="align-middle"><span
                                                class="badge fw-bolder bg-danger">{{ $item->total_keluar }}
                                                {{ $item->nama_satuan }}</span></td>
                                        <td class="align-middle"><span
                                                class="badge fw-bolder bg-secondary">{{ $item->stok_akhir }}
                                                {{ $item->nama_satuan }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

@endsection
