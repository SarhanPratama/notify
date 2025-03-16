@extends('layouts.master')

@section('content')

    @include('layouts.breadcrumbs')

    <style>
        .preview-img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border: 3px solid #ddd;
            border-radius: 10px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s;
        }

        .preview-img:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .preview-container {
            background-color: #f8f9fa;
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 20px;
            transition: border-color 0.3s ease-in-out;
        }

        .preview-container:hover {
            border-color: #8e1616;
        }

        .form-control:focus {
            border-color: #8e1616;
            box-shadow: 0 0 5px rgba(142, 22, 22, 0.5);
        }

    </style>

  <div class="container">
    <div class="row">
        <div class="col col-lg-12">
        <!-- Simple Tables -->
            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}</h6>

                    <button type="button" class="btn btn-outline-light btn-sm btn-lg" data-toggle="modal" data-target="#exampleModal">
                        Tambah
                    </button>
                </div>
                <div class="table-responsive">
                <table class="table table-striped text-sm" id="dataTableHover">
                    <thead class="thead-light">
                    <tr>
                        <th class="text-start">No</th>
                        <th>Cabang</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($cabang as $item)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="text-nowrap align-middle"> {{ ucwords($item->nama) }}</td>
                            <td class="text-nowrap align-middle">{{ $item->telepon }}</td>
                            <td class="text-nowrap align-middle"  data-toggle="tooltip" title="{{ $item->alamat }}">
                                {{ Str::limit($item->alamat, 30, '...') }}
                            </td>
                            <td class="d-flex justify-content-center text-nowrap gap-2">
                                <div>
                                    <button class="btn btn-sm btn-outline-warning"
                                    data-toggle="modal"
                                    data-target="#editModal{{ $item->id }}">
                                    <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                                </button>

                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-danger"
                                        data-toggle="modal"
                                        data-target="#cabangDestroyModal{{ $item->id }}">
                                        <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                    </button>
                                </div>

                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <!-- Modal Header -->
                                    <div class="modal-header bg-warning text-white">
                                        <h6 class="modal-title font-weight-bold" id="editModalLabel">Form Edit</h6>
                                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <!-- Modal Body -->
                                    <form action="{{ route('cabang.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row">
                                                <!-- Form Input (Kiri) -->
                                                <div class="col-lg-8">
                                                    <div class="row g-3 text-sm">
                                                        <!-- Nama Cabang -->
                                                        <div class="col-md-6">
                                                            <label for="nama" class="form-label fw-bold">Nama Cabang <span class="text-danger">*</span></label>
                                                            <input type="text" name="nama" class="form-control form-control-sm" value="{{ $item->nama }}" placeholder="Masukkan nama cabang" required>
                                                            @error('nama')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <!-- Telepon -->
                                                        <div class="col-md-6">
                                                            <label for="telepon" class="form-label fw-bold">Telepon <span class="text-danger">*</span></label>
                                                            <input type="text" name="telepon" class="form-control form-control-sm" value="{{ $item->telepon }}" placeholder="Masukkan telepon" required>
                                                        </div>

                                                        <!-- Alamat -->
                                                        <div class="col-12">
                                                            <label for="alamat" class="form-label fw-bold">Alamat <span class="text-danger">*</span></label>
                                                            <textarea class="form-control form-control-sm" name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat" required>{{ $item->alamat }}</textarea>
                                                        </div>

                                                        <!-- Upload Foto -->
                                                        <div class="mb-3">
                                                            <label for="foto" class="form-label fw-bold">Upload Foto</label>
                                                            <div class="input-group input-group-sm">
                                                                <input type="file" name="foto" class="form-control" id="fotoInput{{ $item->id }}" onchange="previewImage(event, '{{ $item->id }}')">
                                                                <label class="input-group-text">Pilih File</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Foto (Kanan) -->
                                                <div class="col-lg-4">
                                                    <div class="sticky-top" style="top: 20px;">
                                                        <!-- Preview Foto -->
                                                        <div class="text-center">
                                                            <label class="form-label fw-bold">Preview Foto</label>
                                                            <div class="preview-container">
                                                                @if ($item->foto)
                                                                    <img id="fotoPreview{{ $item->id }}" src="{{ asset('storage/' . $item->foto) }}" class="img-thumbnail preview-img m-auto">
                                                                @else
                                                                    <img id="fotoPreview{{ $item->id }}" src="" class="img-thumbnail preview-img m-auto">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Footer -->
                                        <div class="modal-footer d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-warning btn-sm">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="cabangDestroyModal{{ $item->id }}" tabindex="-1" aria-labelledby="cabangDestroyModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-maron">
                                        <h6 class="modal-title font-weight-bold text-light" id="cabangDestroyModalLabel">Konfirmasi Hapus</h6>
                                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                    <div class="modal-body">
                                        <p>Apakah anda yakin ingin menghapus cabang <strong>"{{ $item->nama }}"</strong>?</p>
                                    </div>
                                    <form action="{{ route('cabang.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-maron text-white">
                <h6 class="modal-title font-weight-bold" id="exampleModalLabel">Form Tambah</h6>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('cabang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Form Input (Kiri) -->
                        <div class="col-lg-8">
                            <div class="row g-3 text-sm">
                                <!-- Nama Cabang -->
                                <div class="col-md-6">
                                    <label for="cabang" class="form-label fw-bold">Nama Cabang <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control form-control-sm" placeholder="Masukkan nama cabang" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Telepon -->
                                <div class="col-md-6">
                                    <label for="telepon" class="form-label fw-bold">Telepon <span class="text-danger">*</span></label>
                                    <input type="text" name="telepon" class="form-control form-control-sm" placeholder="Masukkan telepon" required>
                                </div>

                                <!-- Alamat -->
                                <div class="col-12">
                                    <label for="alamat" class="form-label fw-bold">Alamat <span class="text-danger">*</span></label>
                                    <textarea class="form-control form-control-sm" name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat" required></textarea>
                                </div>

                                <!-- Upload Foto -->
                                <div class="mb-3">
                                    <label for="foto" class="form-label fw-bold">Upload Foto</label>
                                    <div class="input-group input-group-sm">
                                        <input type="file" name="foto" class="form-control" id="fotoInput" onchange="previewImage(event)">
                                        <label class="input-group-text">Pilih File</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Foto (Kanan) -->
                        <div class="col-lg-4">
                            <div class="sticky-top" style="top: 20px;">
                                <!-- Preview Foto -->
                                <div class="text-center">
                                    <label class="form-label fw-bold">Preview Foto</label>
                                    <div class="preview-container">
                                        <img id="fotoPreview" src="" class="img-thumbnail preview-img m-auto">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Close</button>
                    <button type="reset" class="btn btn-outline-warning btn-sm me-2">Reset</button>
                    <button type="submit" class="btn btn-outline-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function previewImage(event, id = '') {
        const input = event.target;
        const preview = document.getElementById('fotoPreview' + id);

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
