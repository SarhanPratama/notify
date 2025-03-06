@extends('layouts.master')

@section('content')

@include('layouts.breadcrumbs')

<div class="container">
  <div class="row">
    <div class="col-lg-12 mb-4">
      <!-- Simple Tables -->
      <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between color">
            <h6 class="font-weight-bold text-light text-sm">Karyawan</h6>
            {{-- <form action="{{ route('users.index') }}" method="GET" class="d-flex align-items-center">

                <select name="per_page" id="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                </select>
            </form> --}}

            {{-- <div class="dropup-center dropup">

                <a class="" type="" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-search fa-fw text-light"></i>
                </a>

                <ul class="dropdown-menu mb-4 px-2" style="width: 300px; background-color: #6777ef">
                    <form action="{{ route('users.index')}}" method="get">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                            <button class="btn btn-outline-light btn-sm" type="submit" id="button-addon2"><i class="fas fa-search fa-fw"></i></button>
                        </div>
                    </form>
                </ul>
            </div> --}}

            <a href="{{ route('users.create')}}" class="btn btn-outline-light btn-sm btn-lg">
                Tambah
            </a>
        </div>

    <div class="table-responsive">
        <table class="table table-striped text-sm text-nowrap" id="dataTableHover">
            <thead class="thead-light">
            <tr>
                <th class="text-start">No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>cabang</th>
                <th>Telepon</th>
                {{-- <th>Alamat</th> --}}
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)

                <tr>
                    <td class="text-start">{{ $loop->iteration }}</td>
                    <td>{{ ucwords($item->name) }}</td>
                    <td>{{ Ucwords($item->roles->first()->name ?? '-') }}</td>
                    <td>{{ ucwords($item->cabang->nama ?? '-') }}</td>
                    <td>{{ $item->telepon ?? '-'}}</td>
                    {{-- <td class="text-truncate" style="max-width: 200px;"  data-bs-toggle="tooltip" title="{{ $item->alamat }}">
                        {{ Str::limit($item->alamat, 30, '...') }}
                    </td> --}}

                    <td class="d-flex justify-content-center  text-nowrap gap-2">
                        <div>
                            <a href="{{ route('users.edit', $item->id) }}"  class="btn btn-sm btn-outline-warning">
                                <i class="fa fa-pencil fs-6 aria-hidden="true"></i>
                            </a>
                        </div>

                        <div>
                            <a href="{{ route('users.show', $item->id) }}"  class="btn btn-sm btn-outline-success">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                        </div>

                        <div>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#usersDestroyModal{{ $item->id }}">
                                <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <div class="modal fade" id="usersDestroyModal{{ $item->id }}" tabindex="-1"
                    aria-labelledby="usersDestroyModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h1 class="modal-title fs-6 text-light font-weight-bold"
                                    id="usersDestroyModalLabel">Konfirmasi Hapus</h1>
                                <i class="bi bi-x-lg btn btn-outline-light btn-sm"
                                    data-bs-dismiss="modal" aria-label="Close"></i>
                            </div>
                            <div class="modal-body">
                                <p>Apakah anda yakin ingin menghapus users <strong> {{ $item->name }}
                                    </strong>?</p>
                            </div>
                            <form action="{{ route('users.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit"
                                        class="btn btn-outline-danger btn-sm">Delete</button>
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
                {{-- {{ $users->appends(['search' => request('search')], ['per_page' => request('per_page')])->links('pagination::bootstrap-4') }} --}}
            </div>

    </div>
    </div>
  </div>
</div>
@endsection
