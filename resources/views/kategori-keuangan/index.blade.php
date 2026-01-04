@extends('layouts.master')

@section('content')

    @include('layouts.breadcrumbs')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header py-3 d-flex align-items-center justify-content-between bg-maron">
                <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}</h6>
                <button type="button" class="btn btn-outline-light btn-sm" data-toggle="modal" data-target="#createKategoriKeuanganModal">Tambah</button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped text-sm" id="dataTableHover">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kategori as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucwords($item->nama) }}</td>
                                <td>
                                    @if($item->jenis == 'pemasukan')
                                        <span class="badge badge-success">Pemasukan</span>
                                    @elseif($item->jenis == 'pengeluaran')
                                        <span class="badge badge-danger">Pengeluaran</span>
                                    @else
                                        <span class="badge badge-secondary">Lainnya</span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at ? $item->created_at->format('d-m-Y') : '-' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#editKategoriKeuanganModal{{ $item->id }}"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#deleteKategoriKeuanganModal{{ $item->id }}"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>

                            @include('kategori-keuangan.modal-edit')
                            @include('kategori-keuangan.modal-delete')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    @include('kategori-keuangan.modal-create')

@endsection

@push('scripts')
    <script>
$(document).ready(function() {
    $('#dataTableHover').DataTable();
});
    </script>
@endpush
