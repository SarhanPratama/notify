@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row">
            <div class="col col-lg-12">
                <!-- Simple Tables -->

                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
                        </h6>

                        <button type="button" class="btn btn-outline-light btn-sm btn-lg" data-toggle="modal"
                            data-target="#exampleModal">
                            Tambah
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="dataTableHover">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-start">No</th>
                                    <th>Supplier</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($supplier as $item)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle"> {{ ucwords($item->nama) }}</td>
                                        <td class="align-middle">{{ $item->telepon }}</td>
                                        <td class="align-middle" class="" data-bs-toggle="tooltip"
                                            title="{{ $item->alamat }}">
                                            {{ Str::limit($item->alamat, 30, '...') }}
                                        </td>

                                        <td class="text-center align-middle">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button class="btn btn-sm btn-outline-warning rounded-left"
                                                    data-toggle="modal"
                                                    data-target="#supplierUpdateModal{{ $item->id }}">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </button>

                                                <button class="btn btn-sm btn-outline-danger rounded-right" data-toggle="modal"
                                                    data-target="#supplierDestroyModal{{ $item->id }}">
                                                    <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>

                                    <div class="modal fade" id="supplierUpdateModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="supplierUpdateModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h6 class="modal-title font-weight-bold text-light"
                                                        id="supplierUpdateModalLabel">Form Edit</h6>
                                                    <button type="button" class="close text-light" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('supplier.update', $item->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body text-sm fw-bold">
                                                        <div class="mb-3">
                                                            <label>Supplier <span class="text-danger">*</span> </label>
                                                            <input type="text" name="nama"
                                                                class="form-control form-control-sm"
                                                                value="{{ $item->nama }}"
                                                                placeholder="Masukkan nama supplier" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Telepon <span class="text-danger">*</span></label>
                                                            <input type="text" name="telepon"
                                                                class="form-control form-control-sm"
                                                                value="{{ $item->telepon }}"
                                                                placeholder="Masukkan nomor telepon" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Alamat <span class="text-danger">*</span></label>
                                                            <textarea class="form-control form-control-sm" name="alamat" rows="3">{{ $item->alamat }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit"
                                                            class="btn btn-outline-primary btn-sm">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="supplierDestroyModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="supplierDestroyModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h6 class="modal-title font-weight-bold text-light"
                                                        id="supplierDestroyModalLabel">Konfirmasi Hapus</h6>
                                                    <button type="button" class="close text-light" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah anda yakin ingin menghapus supplier
                                                        <strong>"{{ $item->nama }}"</strong>?</p>
                                                </div>
                                                <form action="{{ route('supplier.destroy', $item->id) }}" method="POST">
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
                    <div class="card-footer d-flex justify-content-center">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-maron">
                    <h6 class="modal-title text-light font-weight-bold" id="exampleModalLabel">Form Tambah</h6>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-sm fw-bold">
                        <div class="mb-3">
                            <label>Supplier <span class="text-danger">*</span> </label>
                            <input type="text" name="nama" class="form-control form-control-sm"
                                placeholder="Masukkan nama supplier" required>
                        </div>
                        <div class="mb-3">
                            <label>Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="telepon" class="form-control form-control-sm"
                                placeholder="Masukkan nomor telepon" required>
                        </div>
                        <div class="mb-3">
                            <label>Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-sm" name="alamat" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close</button>
                        <button type="reset" class="btn btn-outline-warning btn-sm">Reset</button>
                        <button type="submit" class="btn btn-outline-primary btn-sm">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
