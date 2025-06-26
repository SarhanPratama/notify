@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- Simple Tables -->
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-sm" id="dataTableHover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Permission</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($role as $key => $role)
                                    <tr>
                                        <td class="align-middle">{{ $key + 1 }}</td>
                                        <td class="align-middle">{{ ucwords($role->name) }}</td>
                                        <td class="align-middle">
                                            @if ($role->permissions->count() > 0)
                                                @foreach ($role->permissions as $perm)
                                                    <span class="badge bg-success">{{ ucwords($perm->name) }}</span>
                                                @endforeach
                                            @else
                                                <span class="badge bg-secondary">No Permissions</span>
                                            @endif
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <!-- Tombol untuk membuka modal edit permission -->
                                            <button class="btn btn-outline-warning btn-sm" data-toggle="modal"
                                                data-target="#editRoleModal{{ $role->id }}">
                                                <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Permission -->
                                    <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1"
                                        aria-labelledby="editRoleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h6 class="modal-title fs-6 font-weight-bold text-light">Tambah
                                                        permissions untuk role: <strong
                                                            class="text-decoration-underline">{{ $role->name }}</strong>
                                                    </h6>
                                                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                </div>
                                                <form action="{{ route('akses-role.update', $role->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label class="fw-bold">Pilih Permission:</label>
                                                            <select class="select2-multiple form-control" name="permissions[]"
                                                                multiple="multiple" id="permissionsSelect{{ $role->id }}">
                                                                @foreach ($permissions as $id => $name)
                                                                    <option value="{{ $name }}" {{ $role->permissions->contains('name', $name) ? 'selected' : '' }}>
                                                                        {{ $name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-warning">Update</button>
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
@endsection
