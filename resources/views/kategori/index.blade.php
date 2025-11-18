@extends('layouts.master')

@section('content')

    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}</h6>
                        <button type="button" class="btn btn-outline-light btn-sm" data-toggle="modal" data-target="#createKategoriModal">Tambah</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="dataTableHover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Dibuat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategori as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucwords($item->nama) }}</td>
                                        <td>{{ $item->created_at ? $item->created_at->format('d-m-Y') : '-' }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#editKategoriModal{{ $item->id }}"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#deleteKategoriModal{{ $item->id }}"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editKategoriModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning text-white">
                                                    <h6 class="modal-title">Edit Kategori</h6>
                                                    <button type="button" class="close text-light" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <form action="{{ route('kategori.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                                            <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" required>
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
                                    <div class="modal fade" id="deleteKategoriModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-maron text-white">
                                                    <h6 class="modal-title">Konfirmasi Hapus</h6>
                                                    <button type="button" class="close text-light" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus kategori "<strong>{{ $item->nama }}</strong>"?</p>
                                                </div>
                                                <form action="{{ route('kategori.destroy', $item->id) }}" method="POST">
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
    <div class="modal fade" id="createKategoriModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-maron text-white">
                    <h6 class="modal-title">Tambah Kategori</h6>
                    <button type="button" class="close text-light" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required>
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
