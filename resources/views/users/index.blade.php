@extends('layouts.master')

@section('content')

<div class="container-fluid">
    @include('layouts.breadcrumbs')
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
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ ucwords($item->name) }}</td>
                                        <td class="align-middle">{{ $item->email }}</td>
                                        <td class="align-middle">{{ Ucwords($item->role->name ?? '-') }}</td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('users.edit', $item->id) }}"
                                                    class="btn btn-outline-warning rounded-left" title="edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                           <button class="btn btn-sm btn-outline-danger rounded-right" data-toggle="modal"
                                                    data-target="#usersDestroyModal{{ $item->id }}">
                                                    <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    @include('users.destroy')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
