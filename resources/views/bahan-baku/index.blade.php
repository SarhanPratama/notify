@extends('layouts.master')

@section('content')


    @include('layouts.breadcrumbs')

  <div class="container">
    <div class="row">
        <div class="col col-lg-12">
        <!-- Simple Tables -->

            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #6777ef">
                <h6 class="font-weight-bold text-light text-sm">Bahan Baku</h6>

                    <button type="button" class="btn btn-outline-light btn-sm btn-lg" data-bs-toggle="modal" data-bs-target="#createModal">
                        Tambah
                    </button>
                </div>
                <div class="table-responsive">
                <table class="table table-striped text-sm" id="dataTableHover">
                    <thead class="thead-light">
                    <tr>
                        <th class="text-start">No</th>
                        <th>Bahan Baku </th>
                        <th>Stok</th>
                        <th>Stok Minimum</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($bahan_baku as $item)
                        <tr class="text-start">
                            <td class="text-start">{{ $loop->iteration }}</td>
                            <td> {{ ucwords($item->nama) }}</td>
                            <td>{{ $item->stok }} <small class="badge bg-success">{{ $item->satuan->nama }}</small></td>
                            <td>{{ $item->stok_minimum }} <small class="badge bg-success">{{ $item->satuan->nama }}</small></td>

                            <td class="d-flex justify-content-center text-nowrap gap-2">
                                <div>
                                    <button  class="btn btn-sm btn-outline-warning"
                                        data-bs-toggle="modal" data-bs-target="#bahanBakuUpdateModal{{ $item->id }}">
                                        <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#bahanBakuDestroyModal{{ $item->id }}">
                                        <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                    </button>
                                </div>

                            </td>
                        </tr>

                        <div class="modal fade" id="bahanBakuUpdateModal{{ $item->id }}" tabindex="-1" aria-labelledby="bahanBakuUpdateModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h1 class="modal-title fs-6 text-light font-weight-bold" id="bahanBakuUpdateModalLabel">Form Update Bahan Baku</h1>
                                        <i class="bi bi-x-lg btn btn-outline-light btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
                                    </div>
                                    <form action="{{ route('bahan-baku.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body text-sm fw-bold">
                                            <div class="mb-3">
                                                <label>Bahan Baku</label>
                                                <input type="text" name="nama" class="form-control form-control-sm"  value="{{ $item->nama}}" placeholder="Masukkan nama bahan baku">
                                                @error('nama')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label>Stok</label>
                                                    <input type="text" name="stok" class="form-control form-control-sm" value="{{ $item->stok }}"  placeholder="Masukkan stok">
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label>Stok Minimum</label>
                                                    <input type="text" name="stok_minimum" class="form-control form-control-sm" value="{{ $item->stok_minimum }}"  placeholder="Masukkan stok minimum">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Satuan</label>
                                                <select class="form-select form-select-sm" id="id_satuan" name="id_satuan" required>
                                                    <option selected disabled>Pilih Satuan</option>
                                                    @foreach ($satuan as $id => $nama)
                                                    <option value="{{ $id }}" {{ $item->id_satuan == $id ? 'selected' : ''}}>{{ $nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-warning btn-sm">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="bahanBakuDestroyModal{{ $item->id }}" tabindex="-1" aria-labelledby="bahanBakuDestroyModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h1 class="modal-title fs-5 font-weight-bold text-light" id="bahanBakuDestroyModalLabel">Konfirmasi Hapus</h1>
                                        <i class="bi bi-x-lg btn btn-outline-light btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah anda yakin ingin menghapus Permission <strong>{{ $item->nama }}</strong>?</p>
                                    </div>
                                    <form action="{{ route('bahan-baku.destroy', $item->id) }}" method="POST">
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


  <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header color">
          <h1 class="modal-title fs-6 text-light font-weight-bold" id="createModalLabel">Form input Bahan Baku</h1>
          <i class="bi bi-x-lg btn btn-outline-light btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
          {{-- <button type="button" class="btn-close btn-outline-primary" data-bs-dismiss="modal" aria-label="Close"></button> --}}
        </div>
        <form action="{{ route('bahan-baku.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body text-sm fw-bold">
                <div class="mb-3">
                    <label>Bahan Baku</label>
                    <input type="text" name="nama" class="form-control form-control-sm"  placeholder="Masukkan nama bahan baku">
                    @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label>Stok</label>
                        <input type="text" name="stok" class="form-control form-control-sm"  placeholder="Masukkan stok">
                    </div>
                    <div class="col-6 mb-3">
                        <label>Stok Minimum</label>
                        <input type="text" name="stok_minimum" class="form-control form-control-sm"  placeholder="Masukkan stok minimum">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <select class="form-select form-select-sm" id="id_satuan" name="id_satuan" required>
                        <option selected disabled>Pilih Satuan</option>
                        @foreach ($satuan as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-outline-primary btn-sm">Save</button>
            </div>
        </form>
      </div>
    </div>
  </div>

@endsection
