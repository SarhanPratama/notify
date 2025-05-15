@extends('layouts.master')

@section('content')


    @include('layouts.breadcrumbs')

  <div class="container-fluid">
    <div class="row">
        <div class="col col-lg-12">
        <!-- Simple Tables -->
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}</h6>

                    <a href="{{ route('produk.create')}}" type="button" class="btn btn-outline-light btn-sm btn-lg">
                        Tambah
                    </a>
                </div>
                <div class="table-responsive">
                <table class="table table-striped text-sm" id="dataTableHover">
                    <thead class="thead-light">
                    <tr class="text-nowrap">
                        <th class="text-start">No</th>
                        <th>Produk</th>
                        <th>Stok</th>
                        <th>Harga Modal</th>
                        <th>Harga Jual</th>
                        <th>Kategori</th>
                        <th>Merek</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($produk as $item)
                        <tr>
                            <td class="align-middle text-nowrap">{{ $loop->iteration }}</td>
                            <td class="align-middle text-nowrap">{{ ucwords($item->nama) }}</td>
                            <td class="align-middle text-nowrap"> <span class="badge badge-success">{{ $item->stok}}</span></td>
                            <td class="align-middle text-nowrap">Rp. {{ number_format($item->harga_modal, 0, ',', '.')}}</td>
                            <td class="align-middle text-nowrap">Rp. {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td class="align-middle text-nowrap">{{ ucwords($item->kategori->nama) }}</td>
                            <td class="align-middle text-nowrap">{{ ucwords($item->merek->nama) }}</td>

                            <td class="d-flex justify-content-center text-nowrap gap-2">
                                <div>
                                    <a href="{{ route('produk.edit', $item->id)}}"  class="btn btn-sm btn-outline-warning">
                                        <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('produk.show', $item->id) }}"  class="btn btn-sm btn-outline-success">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-danger"
                                        data-toggle="modal"
                                        data-target="#DestroyModal{{ $item->id }}">
                                        <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                    </button>
                                </div>

                            </td>
                        </tr>
                        <div class="modal fade" id="DestroyModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="DestroyModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-maron">
                                        <h5 class="modal-title font-weight-bold text-light" id="DestroyModalLabel">Konfirmasi Hapus</h5>
                                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah anda yakin ingin menghapus produk <strong>"{{ $item->nama }}"</strong>?</p>
                                    </div>
                                    <form action="{{ route('cabang.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
