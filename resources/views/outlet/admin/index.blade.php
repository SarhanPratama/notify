@extends('layouts.master')

@section('content')

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

  <div class="container-fluid">
        @include('layouts.breadcrumbs')
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
                        <th>Kode</th>
                        <th>Outlet</th>
                        <th>Penanggung Jawab</th>
                        <th>Telepon</th>
                        <th>Lokasi</th>
                        <th class="text-center">QR Code</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($outlet as $item)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="text-nowrap align-middle">{{ $item->kode }}</td>
                            <td class="text-nowrap align-middle"> {{ ucwords($item->nama) }}</td>
                            <td class="text-nowrap align-middle">{{ $item->penanggung_jawab }}</td>
                            <td class="text-nowrap align-middle">{{ $item->telepon }}</td>
                            <td class="text-nowrap align-middle"><a class="btn btn-sm btn-outline-info" target="_blank" href="{{ $item->lokasi}}"><i class="fa fa-map-marker fs-5" aria-hidden="true"></i></a></td>
                            <td class="text-center align-middle">
                                @if($item->barcode_token)
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('outlet.show', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Kelola QR Code">
                                            <i class="fa fa-qrcode" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('outlet.show', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Generate QR Code">
                                        <i class="fa fa-plus" aria-hidden="true"></i> QR
                                    </a>
                                @endif
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

                        @include('outlet.admin.edit')

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
                                    <form action="{{ route('outlet.destroy', $item->id) }}" method="POST">
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

    @include('outlet.admin.create')


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
