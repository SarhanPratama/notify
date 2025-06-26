@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- Simple Tables -->
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
                        </h6>
                        <a href="{{ route('users.create') }}" class="btn btn-outline-light btn-sm btn-lg">
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
                                    <th>Cabang</th>
                                    <th>Telepon</th>
                                    {{-- <th>Alamat</th> --}}
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ ucwords($item->name) }}</td>
                                        <td class="align-middle">{{ Ucwords($item->roles->first()->name ?? '-') }}</td>
                                        <td class="align-middle">{{ ucwords($item->cabang->nama ?? '-') }}</td>
                                        <td class="align-middle">
                                            <a href="https://wa.me/{{ $item->telepon ?? 'nomor_default' }}" target="_blank">
                                                {{ $item->telepon ?? '-' }}
                                            </a>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('users.edit', $item->id) }}"
                                                    class="btn btn-outline-warning rounded-left" title="edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a>

                                                <a href="{{ route('users.show', $item->id) }}"
                                                    class="btn btn-sm btn-outline-success">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>

                                           <button class="btn btn-sm btn-outline-danger rounded-right" data-toggle="modal"
                                                    data-target="#usersDestroyModal{{ $item->id }}">
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
                                                    <h6 class="modal-title text-light font-weight-bold"
                                                        id="usersDestroyModalLabel">Konfirmasi Hapus</h6>
                                                    <button type="button" class="close text-light" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('users.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>Apakah anda yakin ingin menghapus users
                                                            <strong>"{{ $item->name }}"</strong>?
                                                        </p>

                                                        <div class="my-3">
                                                            <label for="password" class="form-label">Masukkan password
                                                                user <span class="text-danger">*</span></label>
                                                            <input type="password" class="form-control form-control-sm"
                                                                id="password" name="password" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit"
                                                                class="btn btn-outline-danger btn-sm">Delete</button>
                                                        </div>
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
