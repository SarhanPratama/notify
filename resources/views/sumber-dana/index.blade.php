@extends('layouts.master')

@section('content')

    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}</h6>
                        <button type="button" class="btn btn-outline-light btn-sm" data-toggle="modal" data-target="#createSumberDanaModal">Tambah</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="dataTableHover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Saldo Awal</th>
                                    <th>Dibuat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sumberDana as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucwords($item->nama) }}</td>
                                        <td>Rp {{ number_format($item->saldo_awal ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $item->created_at ? $item->created_at->format('d-m-Y') : '-' }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#editSumberDanaModal{{ $item->id }}"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#deleteSumberDanaModal{{ $item->id }}"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editSumberDanaModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning text-white">
                                                    <h6 class="modal-title">Edit Sumber Dana</h6>
                                                    <button type="button" class="close text-light" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <form action="{{ route('sumber-dana.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                                                            <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Saldo Awal <span class="text-danger">*</span></label>
                                                            <input type="number" name="saldo_awal" class="form-control" value="{{ $item->saldo_awal ?? 0 }}" min="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-outline-warning">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteSumberDanaModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-maron text-white">
                                                    <h6 class="modal-title">Konfirmasi Hapus</h6>
                                                    <button type="button" class="close text-light" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus sumber dana "<strong>{{ $item->nama }}</strong>"?</p>
                                                </div>
                                                <form action="{{ route('sumber-dana.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-outline-danger">Hapus</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createSumberDanaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-maron text-white">
                    <h6 class="modal-title">Tambah Sumber Dana</h6>
                    <button type="button" class="close text-light" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('sumber-dana.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Saldo Saat Ini <span class="text-danger">*</span></label>
                            <input type="number" name="saldo_awal" class="form-control" value="0" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-outline-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
