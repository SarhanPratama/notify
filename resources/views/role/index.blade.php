@extends('layouts.master')

@section('content')

@include('layouts.breadcrumbs')

<div class="container">
  <div class="row">
    <div class="col-lg-12 mb-4">
      <!-- Simple Tables -->
      <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="font-weight-bold text-primary text-sm">Role</h6>

            <div class="dropup-center dropup">
                <a class="" type="" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-search fa-fw"></i>
                </a>
                <ul class="dropdown-menu mb-4 px-2 ms-3" style="width: 360px;">
                    <form action="{{ route('role.index')}}" method="get">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                            <button class="btn btn-outline-primary btn-sm" type="submit" id="button-addon2"><i class="fas fa-search fa-fw"></i></button>
                        </div>
                    </form>
                </ul>
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm btn-lg" data-bs-toggle="modal" data-bs-target="#roleModal">
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
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($role as $item)

                <tr>
                    <td>{{ ($role->currentPage() - 1) * $role->perPage() + $loop->iteration }}</td>
                    <td>{{ $item->name}}</td>
                    {{-- <td>{{ $item->guard_name}}</td> --}}

                    <td class="d-flex text-nowrap gap-2">
                        <div>
                            <button class="btn btn-sm btn-outline-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#roleUpdateModal"
                            data-id="{{ $item->id }}"
                            data-name="{{ $item->name }}"
                            data-guard="{{ $item->guard_name }}">

                            <i class="fa fa-pencil fs-6 aria-hidden="true"></i>
                        </button>
                        </div>

                        <div>
                                <button class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#roleDestroyModal"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->name}}"
                                >
                                    <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                </button>

                        </div>

                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
        </div>

            <div class="card-footer d-flex justify-content-center">
                {{ $role->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
            </div>

    </div>
    </div>
  </div>
</div>
@include('role.destroy')
@include('role.create')
@include('role.update')
@endsection
