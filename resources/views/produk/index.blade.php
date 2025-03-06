@extends('layouts.master')

@section('content')


    @include('layouts.breadcrumbs')

  <div class="container">
    <div class="row">
        <div class="col col-lg-12">
        <!-- Simple Tables -->
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #6777ef">
                <h6 class="font-weight-bold text-light text-sm">Produk</h6>

                    <a href="{{ route('produk.create')}}" type="button" class="btn btn-outline-light btn-sm btn-lg">
                        Tambah
                    </a>
                </div>
                <div class="table-responsive">
                <table class="table table-striped text-sm" id="dataTableHover">
                    <thead class="thead-light">
                    <tr>
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
                            <td class="text-start">{{ $loop->iteration }}</td>
                            <td>{{ ucwords($item->nama) }}</td>
                            <td>{{ $item->stok}}</td>
                            <td>Rp. {{ number_format($item->harga_modal, 0, ',', '.')}}</td>
                            <td>Rp. {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ ucwords($item->kategori->nama) }}</td>
                            <td>{{ ucwords($item->merek->nama) }}</td>

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
                                        data-bs-toggle="modal"
                                        data-bs-target="#produkDestroyModal{{ $item->id }}">
                                        <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                    </button>
                                </div>

                            </td>
                        </tr>

                        <div class="modal fade" id="permissionUpdateModal{{ $item->id }}" tabindex="-1" aria-labelledby="permissionUpdateModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5 font-weight-bold" id="permissionUpdateModalLabel">Form Update role</h1>
                                        <i class="bi bi-x-lg btn btn-outline-danger btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
                                    </div>
                                    <form action="{{ route('permission.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" id="modal_id" name="id">
                                        <div class="modal-body text-sm">
                                            <div class="mb-3">
                                                <label for="cabang">Permission</label>
                                                <input type="text" name="name" class="form-control form-control-sm" value="{{ $item->name }}" placeholder="Masukkan permission" required>
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="produkDestroyModal{{ $item->id }}" tabindex="-1" aria-labelledby="cabangDestroyModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h1 class="modal-title fs-5 font-weight-bold text-light" id="cabangDestroyModalLabel">Konfirmasi Hapus</h1>
                                        <i class="bi bi-x-lg btn btn-outline-light btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah anda yakin ingin menghapus produk <strong>{{ ucwords($item->nama) }}</strong>?</p>
                                    </div>
                                    <form action="{{ route('produk.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Close</button>
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
