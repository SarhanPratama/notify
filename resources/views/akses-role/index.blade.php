@extends('layouts.master')

@section('content')

@include('layouts.breadcrumbs')

<div class="container">
  <div class="row">
    <div class="col-lg-12 mb-4">
      <!-- Simple Tables -->
      <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between color">
            <h6 class="font-weight-bold text-light text-sm">Akses Role</h6>
            {{-- <form action="{{ route('cabang.index') }}" method="GET" class="d-flex align-items-center">
                <select name="per_page" id="per_page" class="form-select form-select-sm " onchange="this.form.submit()">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </form> --}}
            {{-- <div class="dropup-center dropup">
                <a class="" type="" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-search fa-fw text-light"></i>
                </a>
                <ul class="dropdown-menu mb-4 px-2 ms-3" style="width: 360px; background-color: #6777ef">
                    <form action="{{ route('akses-role.index')}}" method="get">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                            <button class="btn btn-outline-light btn-sm" type="submit" id="button-addon2"><i class="fas fa-search fa-fw"></i></button>
                        </div>
                    </form>
                </ul>
            </div> --}}

        </div>
        <div class="table-responsive">
        <table class="table table-striped table-hover text-sm text-nowrap" id="dataTableHover">
            <thead class="thead-light">
            <tr>
                <th class="text-start">No</th>
                <th>Name</th>
                <th>Permission</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($role as $key => $role)
                <tr>
                    <td class="text-start">{{ $key + 1 }}</td>
                    <td>{{ ucwords($role->name) }}</td>
                    <td>
                        @if($role->permissions->count() > 0)
                            @foreach ($role->permissions as $perm)
                                <span class="badge bg-success">{{ ucwords($perm->name) }}</span>
                            @endforeach
                        @else
                            <span class="badge bg-secondary">No Permissions</span>
                        @endif
                    </td>
                    <td class="d-flex justify-content-center">
                        <!-- Tombol untuk membuka modal edit permission -->
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}">
                            <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>

                <!-- Modal Edit Permission -->
                <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true" >
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-warning">
                                <h6 class="modal-title fs-6 font-weight-bold text-light">Tambah permissions untuk role: <strong class="text-decoration-underline">{{ $role->name }}</strong></h6>
                                <i class="bi bi-x-lg btn btn-outline-light btn-sm" data-bs-dismiss="modal" aria-label="Close"></i>
                            </div>
                            <form action="{{ route('akses-role.update', $role->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="fw-bold">Pilih Permission:</label>
                                        <div class="row">
                                            @foreach ($permissions as $id => $name)
                                                <div class="col-md-3">

                                                        <div class="card-body d-flex align-items-center text-sm text-nowrap">
                                                            <div class="form-check form-switch ">
                                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $name }}"
                                                                    id="permissionSwitch{{ $id }}"
                                                                    {{ $role->permissions->contains('name', $name) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="permissionSwitch{{ $id }}">{{ $name }}</label>
                                                            </div>
                                                            {{-- <i class="bi bi-check-circle-fill text-primary" style="font-size: 1.5rem;"></i> --}}
                                                        </div>

                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm btn-outline-warning">Update</button>
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
                {{-- {{ $role->appends(['search' => request('search')])->links('pagination::bootstrap-4') }} --}}
            </div>

    </div>
    </div>
  </div>
</div>
{{-- @include('role.destroy') --}}
{{-- @include('role.create') --}}
{{-- @include('role.update') --}}
@endsection
