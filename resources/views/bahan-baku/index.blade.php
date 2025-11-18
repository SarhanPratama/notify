@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp

    <div class="container-fluid">
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
                                    <th>Foto</th>
                                    <th>Bahan Baku </th>
                                    <th>Harga</th>
                                    <th>Stok Awal</th>
                                    <th>Stok Minimum</th>
                                    <th>Kategori</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bahanBaku as $item)
                                    <tr class="text-start text-nowrap ">
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">
                                            @if($item->foto && Storage::disk('public')->exists($item->foto))
                                                <img src="{{ Storage::url($item->foto) }}"
                                                     alt="{{ $item->nama }}"
                                                     class="img-thumbnail rounded-circle"
                                                     style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                                     onclick="showImageModal('{{ Storage::url($item->foto) }}', '{{ $item->nama }}')"
                                                     title="Klik untuk memperbesar">
                                            @else
                                                <div class="d-flex justify-content-center align-items-center bg-light rounded-circle"
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ ucwords($item->nama) }}</td>
                                        <td class="align-middle">Rp {{ number_format($item->harga, 2, ',', '.') }}</td>
                                        <td class="align-middle"><span
                                                class="badge fw-bolder bg-primary">{{ $item->stok_awal }}
                                                {{ $item->satuan->nama }}</span></td>
                                        <td class="align-middle"><span
                                                class="badge fw-bolder bg-secondary">{{ $item->stok_minimum }}
                                                {{ $item->satuan->nama }}</span></td>
                                        <td class="align-middle">
                                            <span class="badge fw-bolder bg-success">
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
                                    @include('bahan-baku.modal-edit')
                                    @include('bahan-baku.modal-konfirmasi-delete')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('bahan-baku.modal-create')

    <!-- Modal untuk menampilkan gambar besar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Foto Bahan Baku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid rounded" style="max-height: 500px;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@push('styles')
<style>
    .img-thumbnail:hover {
        transform: scale(1.05);
        transition: transform 0.2s ease-in-out;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .preview-container {
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 15px;
        transition: border-color 0.3s ease;
    }

    .preview-container:hover {
        border-color: #007bff;
    }
</style>
@endpush

@push('scripts')
<script>
    function showImageModal(imageSrc, itemName) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('modalImage').alt = itemName;
        document.getElementById('imageModalLabel').textContent = 'Foto ' + itemName;

        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }

    // Preview image for create modal
    function previewCreateImage(event) {
        const input = event.target;
        const preview = document.getElementById('createImagePreview');
        const previewContainer = document.getElementById('createPreviewContainer');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            previewContainer.style.display = 'none';
        }
    }

    // Preview image for edit modal
    function previewEditImage(event, itemId) {
        const input = event.target;
        const preview = document.getElementById('editImagePreview' + itemId);
        const previewContainer = document.getElementById('editPreviewContainer' + itemId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

@endsection
