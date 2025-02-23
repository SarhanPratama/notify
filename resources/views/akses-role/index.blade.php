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

            <a href="{{ route('akses-role.create')}}" class="btn btn-outline-primary btn-sm btn-lg">
                Tambah
            </a>

        </div>
        <div class="table-responsive">
        <table class="table table-striped text-sm">
            <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Permission</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($role as $key => $role)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        @if($role->permissions->count() > 0)
                            @foreach ($role->permissions as $perm)
                                <span class="badge bg-success">{{ $perm->name }}</span>
                            @endforeach
                        @else
                            <span class="badge bg-secondary">No Permissions</span>
                        @endif
                    </td>
                    <td>
                        <!-- Tombol untuk membuka modal edit permission -->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $role->id }}">
                            Edit Permissions
                        </button>
                    </td>
                </tr>

                <!-- Modal Edit Permission -->
                <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true" >
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white " >
                                <h5 class="modal-title">Edit Permissions for Role: {{ $role->name }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('akses-role.update', $role->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="fw-bold">Pilih Permission:</label>
                                        <div class="row">
                                            @foreach ($permissions as $permission)
                                                <div class="col-md-3">

                                                        <div class="card-body d-flex align-items-center text-sm text-nowrap">
                                                            <div class="form-check form-switch ">
                                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                                    id="permissionSwitch{{ $permission->id }}"
                                                                    {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="permissionSwitch{{ $permission->id }}">{{ $permission->name }}</label>
                                                            </div>
                                                            {{-- <i class="bi bi-check-circle-fill text-primary" style="font-size: 1.5rem;"></i> --}}
                                                        </div>

                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
