@extends('layouts.master')

@section('content')


    @include('layouts.breadcrumbs')

  <div class="container">
    <div class="row">
        <div class="col col-lg-12">
        <!-- Simple Tables -->

            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #6777ef">
                <h6 class="font-weight-bold text-light text-sm">Cabang</h6>

                    <button type="button" class="btn btn-outline-light btn-sm btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tambah
                    </button>
                </div>
                <div class="table-responsive">
                <table class="table table-striped text-sm" id="dataTableHover">
                    <thead class="thead-light">
                    <tr>
                        <th class="text-start">No</th>
                        <th>Cabang</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($cabang as $item)
                        <tr>
                            <td class="text-start">{{ $loop->iteration }}</td>
                            <td>
                                {{ $item->nama}}</td>
                            <td>{{ $item->telepon}}</td>
                            <td class="text-truncate" style="max-width: 200px;"  data-bs-toggle="tooltip" title="{{ $item->alamat }}">
                                {{ Str::limit($item->alamat, 30, '...') }}
                            </td>
                            <td class="d-flex justify-content-center text-nowrap gap-2">
                                <div>
                                    <a href="{{ route('cabang.edit', $item->id)}}"  class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#cabangDestroyModal{{ $item->id }}">
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

                        <div class="modal fade" id="cabangDestroyModal{{ $item->id }}" tabindex="-1" aria-labelledby="cabangDestroyModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #6777ef">
                                        <h1 class="modal-title fs-5 font-weight-bold text-light" id="cabangDestroyModalLabel">Konfirmasi Hapus</h1>
                                        <i class="bi bi-x-lg btn btn-outline-light btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah anda yakin ingin menghapus Permission <strong>{{ $item->nama }}</strong>?</p>
                                    </div>
                                    <form action="{{ route('cabang.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Delete</button>
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
  @include('cabang.create')
  {{-- @include('cabang.update') --}}
@endsection
