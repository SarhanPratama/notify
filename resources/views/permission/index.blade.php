@extends('layouts.master')

@section('content')

@include('layouts.breadcrumbs')

<div class="container">
  <div class="row">
    <div class="col-lg-12 mb-4">
      <!-- Simple Tables -->
      <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            {{-- <h6 class="font-weight-bold text-primary text-sm">Tabel Cabang</h6> --}}

            <div class="dropup-center dropup">
                <a class="" type="" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-search fa-fw"></i>
                </a>
                <ul class="dropdown-menu mb-4 px-2" style="width: 300px;">
                    <form action="{{ route('permission.index')}}" method="get">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                            <button class="btn btn-outline-primary btn-sm" type="submit" id="button-addon2"><i class="fas fa-search fa-fw"></i></button>
                        </div>
                    </form>
                </ul>
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm btn-lg" data-bs-toggle="modal" data-bs-target="#permissionModal">
                Tambah
            </button>
        </div>
        <div class="table-responsive">
        <table class="table table-striped text-sm">
            <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Name</th>
                {{-- <th>Guard Name</th> --}}
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($permission as $item)
                <tr>
                    <td>{{ ($permission->currentPage() - 1) * $permission->perPage() + $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td class="d-flex text-nowrap justify-content-center gap-2">
                        <div>
                            <button class="btn btn-sm btn-outline-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#permissionUpdateModal{{ $item->id }}">
                                <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#permissionDestroyModal{{ $item->id }}">
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

                <div class="modal fade" id="permissionDestroyModal{{ $item->id }}" tabindex="-1" aria-labelledby="permissionDestroyModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5 font-weight-bold" id="permissionDestroyModalLabel">Konfirmasi Hapus</h1>
                                <i class="bi bi-x-lg btn btn-outline-danger btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
                            </div>
                            <div class="modal-body">
                                <p>Apakah anda yakin ingin menghapus Permission <strong>{{ $item->name }}</strong>?</p>
                            </div>
                            <form action="{{ route('permission.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
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
            {{ $permission->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
        </div>
    </div>
    </div>
  </div>
</div>

  @include('permission.create')

@endsection
