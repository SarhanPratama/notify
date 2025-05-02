@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container">
        <div class="row">
            <div class="col col-lg-12">
                <!-- Simple Tables -->

                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
                        </h6>

                        <button type="button" class="btn btn-outline-light btn-sm btn-lg" data-toggle="modal"
                            data-target="#createModal">
                            Tambah
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="dataTableHover">
                            <thead class="thead-light">
                                <tr class="text-nowrap">
                                    <th class="text-start">No</th>
                                    <th>Bahan Baku </th>
                                    <th>Harga</th>
                                    <th>Stok Awal</th>
                                    {{-- <th>Stok Masuk</th>
                                    <th>Stok Keluar</th> --}}
                                    <th>Stok Akhir</th>
                                    <th>Stok Minimum</th>
                                    <th>Kategori</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bahan_baku as $item)
                                    <tr class="text-start text-nowrap ">
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ ucwords($item->nama) }}</td>
                                        <td class="align-middle">Rp {{ number_format($item->harga, 2, ',', '.') }}</td>
                                        <td class="align-middle"><span
                                                class="badge fw-bolder bg-primary">{{ $item->stok_awal }}
                                                {{ $item->satuan->nama }}</span></td>
                                        {{-- <td class="align-middle"><span class="badge fw-bolder bg-success">{{ $item->totalmasuk }}
                                            {{ $item->satuan->nama }}</span></td> --}}
                                        {{-- <td class="align-middle"><span class="badge fw-bolder bg-danger">{{ $item->totalkeluar }}
                                            {{ $item->satuan->nama }}</span></td> --}}
                                        <td class="align-middle"><span
                                                class="badge fw-bolder bg-warning">{{ $item->saldoakhir }}
                                                {{ $item->satuan->nama }}</span></td>
                                        <td class="align-middle"><span
                                                class="badge fw-bolder bg-secondary">{{ $item->stok_minimum }}
                                                {{ $item->satuan->nama }}</span></td>
                                        <td class="align-middle"><span class="badge fw-bolder bg-success">
                                                {{ ucwords($item->kategori->nama) }}</span></td>
                                        <td class="d-flex justify-content-center text-nowrap gap-2">
                                            <div>
                                                <button class="btn btn-sm btn-outline-warning" data-toggle="modal"
                                                    data-target="#editModal{{ $item->id }}">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-outline-danger" data-toggle="modal"
                                                    data-target="#DestroyModal{{ $item->id }}">
                                                    <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title text-light font-weight-bold" id="editModalLabel">
                                                        Form Edit</h5>
                                                    <button type="button" class="close text-light" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('bahan-baku.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body text-sm fw-bold">
                                                        <div class="row">
                                                            <div class="mb-3">
                                                                <label>Bahan Baku <span class="text-danger">*</span></label>
                                                                <input type="text" name="nama"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $item->nama }}"
                                                                    placeholder="Masukkan nama bahan baku">
                                                                @error('nama')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label>Harga <span class="text-danger">*</span></label>
                                                                <input type="number" name="harga"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ (int) $item->harga }}"
                                                                    placeholder="Masukkan harga">
                                                            </div>
                                                            <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-3">
                                                                <label>Stok Awal<span class="text-danger">*</span></label>
                                                                <input type="number" name="stok_awal"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $item->stok_awal }}"
                                                                    placeholder="Masukkan stok awal">
                                                            </div>
                                                            <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-3">
                                                                <label>Stok Akhir<span class="text-danger">*</span></label>
                                                                <input type="number" name="stok_akhir"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $item->stok_akhir }}"
                                                                    placeholder="Masukkan stok akhir">
                                                            </div>
                                                            <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-3">
                                                                <label>Stok Minimum <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" name="stok_minimum"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $item->stok_minimum }}"
                                                                    placeholder="Masukkan stok minimum">
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                {{-- <div class="d-flex justify-content-between">
                                                                    <label for="select2Single"
                                                                        class="form-label fw-bold">Satuan
                                                                        <span class="text-danger">*</span></label>
                                                                    <a class="text-danger text-decoration-underline"
                                                                        href="javascript:void(0);" data-toggle="modal"
                                                                        data-target="#satuanModal">Tambah Satuan</a>
                                                                </div> --}}
                                                                <label>Satuan <span class="text-danger">*</span></label>
                                                                <select class="form-select form-select-sm" id="id_satuan"
                                                                    name="id_satuan" required>
                                                                    <option selected disabled>Pilih Satuan</option>
                                                                    @foreach ($satuan as $id => $nama)
                                                                        <option value="{{ $id }}"
                                                                            {{ $item->id_satuan == $id ? 'selected' : '' }}>
                                                                            {{ $nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-6 mb-3">
                                                                <label class="form-label">Kategori <span
                                                                        class="text-danger">*</span></label>
                                                                <select class="form-select form-select-sm"
                                                                    id="id_kategori" name="id_kategori" required>
                                                                    <option selected disabled>Pilih Kategori</option>
                                                                    @foreach ($kategori as $id => $nama)
                                                                        <option value="{{ $id }}"
                                                                            {{ $item->id_kategori == $id ? 'selected' : '' }}>
                                                                            {{ $nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-12 mb-3">
                                                                <label>Foto <span class="text-danger">*</span></label>
                                                                <input type="file" name="foto" class="form-control form-control-sm">
                                                            </div>

                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit"
                                                            class="btn btn-outline-warning btn-sm">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="DestroyModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="DestroyModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title font-weight-bold text-light"
                                                        id="DestroyModalLabel">Konfirmasi Hapus</h5>
                                                    <button type="button" class="close text-light" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah anda yakin ingin menghapus bahan baku
                                                        <strong>"{{ $item->nama }}"</strong>?
                                                    </p>
                                                </div>
                                                <form action="{{ route('bahan-baku.destroy', $item->id) }}"
                                                    method="POST">
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


    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header bg-maron">
                    <h5 class="modal-title text-light font-weight-bold" id="createModalLabel">Form Tambah</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('bahan-baku.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-sm fw-bold">
                        <div class="row">
                            <div class="mb-3">
                                <label>Bahan Baku <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control form-control-sm"
                                    placeholder="Masukkan nama bahan baku">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label>Harga <span class="text-danger">*</span></label>
                                <input type="number" name="harga" class="form-control form-control-sm"
                                    placeholder="Masukkan harga">
                            </div>
                            <div class="col-4 mb-3">
                                <label>Stok Awal<span class="text-danger">*</span></label>
                                <input type="number" name="stok_awal" class="form-control form-control-sm"
                                    placeholder="Masukkan stok">
                            </div>
                            {{-- <div class="col-4 mb-3">
                                <label>Stok akhir<span class="text-danger">*</span></label>
                                <input type="number" name="stok_akhir" class="form-control form-control-sm"
                                    placeholder="Masukkan stok">
                            </div> --}}
                            <div class="col-4 mb-3">
                                <label>Stok Minimum <span class="text-danger">*</span></label>
                                <input type="number" name="stok_minimum" class="form-control form-control-sm"
                                    placeholder="Masukkan stok minimum">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-12 mb-3">
                                <label class="form-label">Satuan <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="id_satuan" name="id_satuan" required>
                                    <option selected disabled>Pilih Satuan</option>
                                    @foreach ($satuan as $id => $nama)
                                        <option value="{{ $id }}">{{ $nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-6 col-sm-12 col-12 mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="id_kategori" name="id_kategori" required>
                                    <option selected disabled>Pilih Kategori</option>
                                    @foreach ($kategori as $id => $nama)
                                        <option value="{{ $id }}">{{ $nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Foto <span class="text-danger">*</span></label>
                                <input type="file" name="foto" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary btn-sm">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('bahan-baku.createSatuan')
@endsection
