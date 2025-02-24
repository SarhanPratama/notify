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
                    <form action="{{ route('cabang.index')}}" method="get">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                            <button class="btn btn-outline-primary btn-sm" type="submit" id="button-addon2"><i class="fas fa-search fa-fw"></i></button>
                        </div>
                    </form>
                </ul>
            </div>

            <a href="{{ route('users.create')}}" class="btn btn-outline-primary btn-sm btn-lg">
                Tambah
            </a>
        </div>
    <div class="table-responsive">
        <table class="table table-striped text-sm text-nowrap">
            <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>nama</th>
                <th>email</th>
                <th>Usia</th>
                {{-- <th>Alamat</th> --}}
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)

                <tr>
                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                    <td>{{ $item->name}}</td>
                    <td>{{ $item->email}}</td>
                    <td>{{ $item->Karyawan->usia ?? '-' }}</td>

                    {{-- <td class="text-truncate" style="max-width: 200px;"  data-bs-toggle="tooltip" title="{{ $item->alamat }}">
                        {{ Str::limit($item->alamat, 30, '...') }}
                    </td> --}}

                    <td class="d-flex text-nowrap gap-2">
                        <div>
                            <a href="{{ route('users.edit', $item->id) }}"  class="btn btn-sm btn-outline-warning">
                                <i class="fa fa-pencil fs-6 aria-hidden="true"></i>
                            </a>
                        </div>

                        <div>
                            <a href="{{ route('users.show', $item->id) }}"  class="btn btn-sm btn-outline-warning">
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
                            <div class="modal-header">
                                <h1 class="modal-title fs-5 font-weight-bold"
                                    id="usersDestroyModalLabel">Konfirmasi Hapus</h1>
                                <i class="bi bi-x-lg btn btn-outline-danger btn-sm"
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
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit"
                                        class="btn btn-outline-danger btn-sm">Hapus</button>
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
                {{-- {{ $cabang->appends(['search' => request('search')])->links('pagination::bootstrap-4') }} --}}
            </div>

    </div>
    </div>
  </div>
</div>
  {{-- @include('users.create') --}}
@endsection
