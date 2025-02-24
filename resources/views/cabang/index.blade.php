@extends('layouts.master')

@section('content')


    @include('layouts.breadcrumbs')

  <div class="container">
    <div class="row">
        <div class="col col-lg-12">
        <!-- Simple Tables -->

            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #6777ef">
                    <form action="{{ route('cabang.index') }}" method="GET" class="d-flex align-items-center">
                        <select name="per_page" id="per_page" class="form-select form-select-sm " onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </form>
                    <div class="dropup-center dropup">
                        <a class="" type="" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-search fa-fw text-light"></i>
                        </a>
                        <ul class="dropdown-menu mb-4 px-2" style="width: 300px; background-color: #6777ef;">
                            <form action="{{ route('cabang.index')}}" method="get">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                                    <button class="btn btn-outline-light btn-sm" type="submit" id="button-addon2"><i class="fas fa-search fa-fw"></i></button>
                                </div>
                            </form>
                        </ul>
                    </div>

                    <button type="button" class="btn btn-outline-light btn-sm btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tambah
                    </button>
                </div>
                <div class="table-responsive">
                <table class="table table-striped text-sm">
                    <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Cabang</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($cabang as $item)
                        <tr>
                            <td>{{ ($cabang->currentPage() - 1) * $cabang->perPage() + $loop->iteration }}</td>
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
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5 font-weight-bold" id="cabangDestroyModalLabel">Konfirmasi Hapus</h1>
                                        <i class="bi bi-x-lg btn btn-outline-danger btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah anda yakin ingin menghapus Permission <strong>{{ $item->nama }}</strong>?</p>
                                    </div>
                                    <form action="{{ route('cabang.destroy', $item->id) }}" method="POST">
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
                        {{ $cabang->appends(['search' => request('search')], ['per_page' => request('per_page')])->links('pagination::bootstrap-4') }}
                    </div>

            </div>
        </div>
    </div>
  </div>
  @include('cabang.create')
  {{-- @include('cabang.update') --}}
@endsection
