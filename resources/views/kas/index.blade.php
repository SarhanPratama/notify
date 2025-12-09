@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row">
            <div class="col col-lg-12">
                <!-- Simple Tables -->
                  <a href="{{ route('transaksi.create') }}" type="button" class="btn btn-outline-primary mb-3 fw-bold">
                        Tambah
                    </a>
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
                                    {{-- <th>Sumber Dana</th> --}}
                                    <th>Type</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kas as $item)
                                    <tr>
                                        <td class="align-middle text-nowrap">{{ $loop->iteration }}</td>
                                        <td class="align-middle text-nowrap">
                                            <span class="badge badge-light p-2 text-dark">
                                                <i class="far fa-calendar-alt text-maron mr-1"></i>
                                                {{ $item->tanggal->format('d M Y') }}
                                            </span>
                                        </td>
                                        {{-- <td class="align-middle text-nowrap"> {{ $item->SumberDana->nama }}</td> --}}
                                        <td class="align-middle text-nowrap"> <span
                                                class="badge fw-bolder {{ $item->tipe === 'credit' ? 'bg-danger' : 'bg-success' }}">
                                                {{ ucwords($item->tipe) }}
                                            </span></td>
                                        <td class="align-middle text-nowrap">Rp.
                                            {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                        <td class="align-middle text-nowrap">{{ $item->deskripsi }}</td>
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
