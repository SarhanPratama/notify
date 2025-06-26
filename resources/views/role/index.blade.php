@extends('layouts.master')

@section('content')

    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Simple Tables -->
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}</h6>

                        {{-- <form action="{{ route('role.index') }}" method="GET" class="d-flex align-items-center">
                            <label for="per_page" class="me-2 text-sm">Tampilkan:</label>
                            <select name="per_page" id="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                            </select>
                        </form> --}}

                        {{-- <div class="dropup-center dropup">
                            <a class="" type="" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-search fa-fw text-light"></i>
                            </a>
                            <ul class="dropdown-menu mb-4 px-2 ms-3" style="width: 360px;background-color: #6777ef">
                                <form action="{{ route('role.index') }}" method="get">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control form-control-sm"
                                            value="{{ request('search') }}" placeholder="Search" aria-label="Search"
                                            aria-describedby="button-addon2">
                                        <button class="btn btn-outline-light btn-sm" type="submit" id="button-addon2"><i
                                                class="fas fa-search fa-fw"></i></button>
                                    </div>
                                </form>
                            </ul>
                        </div> --}}

                        <button type="button" class="btn btn-outline-light btn-sm btn-lg" data-toggle="modal"
                            data-target="#roleModal">
                            Tambah
                        </button>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-sm" id="dataTableHover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    {{-- <th>Guard Name</th> --}}
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($role as $id => $name)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ ucwords($name) }}</td>
                                        {{-- <td>{{ $guard_name}}</td> --}}

                                        <td class="d-flex text-nowrap justify-content-center  gap-2">
                                            <div>
                                                <button class="btn btn-sm btn-outline-warning" data-toggle="modal"
                                                    data-target="#roleUpdateModal{{ $id }}">

                                                    <i class="fa fa-pencil fs-6 aria-hidden="true"></i>
                                                </button>
                                            </div>

                                            <div>
                                                <button class="btn btn-sm btn-outline-danger" data-toggle="modal"
                                                    data-target="#roleDestroyModal{{ $id }}">
                                                    <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="roleUpdateModal{{ $id }}" tabindex="-1"
                                        aria-labelledby="roleUpdateModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h1 class="modal-title fs-6 text-light font-weight-bold" id="roleUpdateModalLabel">
                                                        Form Edit</h1>
                                                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                          </button>
                                                </div>
                                                <form action="{{ route('role.update', $id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" id="modal_id" name="id">
                                                    <div class="modal-body text-sm">
                                                        <div class="mb-3">
                                                            <label for="cabang">Role <span class="text-danger">*</span></label>
                                                            <input type="text" name="name"
                                                                class="form-control form-control-sm"
                                                                value="{{ $name }}" placeholder="Masukkan nama role"
                                                                required>
                                                            @error('name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit"
                                                            class="btn btn-outline-warning btn-sm">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="roleDestroyModal{{ $id }}" tabindex="-1"
                                        aria-labelledby="roleDestroyModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-maron">
                                                    <h1 class="modal-title fs-6 text-light font-weight-bold"
                                                        id="roleDestroyModalLabel">Konfirmasi Hapus</h1>
                                                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                          </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah anda yakin ingin menghapus role <strong>"{{ $name }}
                                                        "</strong>?</p>
                                                </div>
                                                <form action="{{ route('role.destroy', $id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                            data-dismiss="modal">Close</button>
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

                    <div class="card-footer">
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('role.create')
@endsection
