@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container">
        <div class="row">
            <div class="col col-lg-12">
                <!-- Simple Tables -->

                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                        style="background-color: #6777ef">
                        <h6 class="font-weight-bold text-light text-sm">Resep</h6>

                        <button type="button" class="btn btn-outline-light btn-sm btn-lg" data-bs-toggle="modal"
                            data-bs-target="#createModal">
                            Tambah
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="dataTableHover">
                            <thead class="thead-light text-nowrap">
                                <tr>
                                    <th class="text-start">No</th>
                                    <th>Produk</th>
                                    <th>Bahan Baku</th>
                                    <th>Jumlah</th>
                                    <th>Instruksi</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resep as $item)
                                    <tr class="text-start">
                                        <td class="text-start">{{ $loop->iteration }}</td>
                                        <td> {{ ucwords($item->produk->nama) }}</td>
                                        <td>
                                            @foreach ($item->detailResep as $detail)
                                                <small class="badge bg-success">{{ $detail->bahanBaku->nama }}</small>
                                            @endforeach
                                        </td>
                                        <td>{{ $item->detailResep->count() }}</td>
                                        <td>
                                            <a class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalInstruksi{{ $item->id }}">
                                            View
                                            </a>
                                        </td>

                                        <td class="d-flex justify-content-center text-nowrap gap-2">
                                            <div>
                                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                                    data-bs-target="#bahanBakuUpdateModal{{ $item->id }}">
                                                    <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                                    data-bs-target="#bahanBakuDestroyModal{{ $item->id }}">
                                                    <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalInstruksi{{ $item->id }}" tabindex="-1" aria-labelledby="modalInstruksiLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold" id="modalInstruksiLabel{{ $item->id }}">Instruksi Detail</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{ $item->instruksi }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="bahanBakuUpdateModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="bahanBakuUpdateModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                    <h1 class="modal-title fs-6 text-light font-weight-bold"
                                                        id="bahanBakuUpdateModalLabel">Form Update Resep</h1>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('resep.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body text-sm fw-bold">
                                                        <!-- Field Produk -->
                                                        <div class="mb-3">
                                                            <label for="id_products" class="form-label">Produk</label>
                                                            <select class="form-select form-select-sm" id="id_products"
                                                                name="id_products" required>
                                                                <option selected disabled>Pilih Produk</option>
                                                                @foreach ($produk as $id => $nama)
                                                                    <option value="{{ $id }}"
                                                                        {{ $item->id_products == $id ? 'selected' : '' }}>
                                                                        {{ $nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Container untuk Bahan Baku -->
                                                        <div id="bahan-baku-container-update-{{ $item->id }}">
                                                            @foreach ($item->detailResep as $detail)
                                                                <div class="bahan-baku-item mb-3">
                                                                    <div class="row g-2 align-items-end">
                                                                        <div class="col-md-6 col-12">
                                                                            <label class="form-label">Bahan Baku</label>
                                                                            <select class="form-select form-select-sm"
                                                                                name="id_bahan_baku[]" required>
                                                                                <option selected disabled>Pilih Bahan Baku
                                                                                </option>
                                                                                @foreach ($bahanBaku as $bahan)
                                                                                    <option value="{{ $bahan->id }}"
                                                                                        data-satuan="{{ $bahan->satuan->nama }}"
                                                                                        {{ $detail->id_bahan_baku == $bahan->id ? 'selected' : '' }}>
                                                                                        {{ $bahan->nama }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4 col-8">
                                                                            <label class="form-label">Jumlah</label>
                                                                            <div class="input-group input-group-sm">
                                                                                <input type="number" name="jumlah[]"
                                                                                    class="form-control" min="0"
                                                                                    placeholder="Jumlah"
                                                                                    value="{{ $detail->jumlah }}" required>
                                                                                <span
                                                                                    class="input-group-text satuan-text">{{ $detail->bahanBaku->satuan->nama }}</span>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="col-md-2 col-4 d-flex justify-content-end">
                                                                            <button type="button"
                                                                                class="btn btn-outline-danger btn-sm remove-bahan-baku">
                                                                                <i class="bi bi-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        <!-- Tombol Tambah Bahan Baku -->
                                                        <div class="mb-3">
                                                            <button type="button" class="btn btn-outline-success btn-sm"
                                                                onclick="tambahBahanBakuUpdate('{{ $item->id }}')">
                                                                <i class="bi bi-plus"></i>Bahan Baku
                                                            </button>
                                                        </div>

                                                        <!-- Field Instruksi -->
                                                        <div class="mb-3">
                                                            <label for="instruksi" class="form-label">Instruksi</label>
                                                            <textarea class="form-control form-control-sm" name="instruksi" id="instruksi" rows="3">{{ $item->instruksi }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit"
                                                            class="btn btn-outline-warning btn-sm">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="bahanBakuDestroyModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="bahanBakuDestroyModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h1 class="modal-title fs-5 font-weight-bold text-light"
                                                        id="bahanBakuDestroyModalLabel">Konfirmasi Hapus</h1>
                                                    <i class="bi bi-x-lg btn btn-outline-light btn-sm"
                                                        data-bs-dismiss="modal" aria-label="Close"></i>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah anda yakin ingin menghapus resep
                                                        <strong>{{ $item->produk->nama }}</strong>?</p>
                                                </div>
                                                <form action="{{ route('resep.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                            data-bs-dismiss="modal">Close</button>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header color">
                    <h1 class="modal-title fs-6 text-light font-weight-bold" id="createModalLabel">Form Input Resep</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('resep.store') }}" method="POST">
                    @csrf
                    <div class="modal-body fw-bold text-sm">
                        <!-- Field Produk -->
                        <div class="mb-3">
                            <label for="id_products" class="form-label">Produk</label>
                            <select class="form-select form-select-sm" id="id_products" name="id_products" required>
                                <option selected disabled>Pilih Produk</option>
                                @foreach ($produk as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Container untuk Bahan Baku -->
                        <div id="bahan-baku-container"></div>

                        <!-- Tombol Tambah Bahan Baku -->
                        <div class="mb-3">
                            <button type="button" id="tambah-bahan-baku" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-plus"></i>Bahan Baku
                            </button>
                        </div>

                        <!-- Field Instruksi -->
                        <div class="mb-3">
                            <label for="instruksi" class="form-label">Instruksi</label>
                            <textarea class="form-control form-control-sm" name="instruksi" id="instruksi" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('bahan-baku-container');
            const addButton = document.getElementById('tambah-bahan-baku');

            // Fungsi untuk menambahkan field bahan baku
            addButton.addEventListener('click', function() {
                const newItem = document.createElement('div');
                newItem.classList.add('bahan-baku-item', 'mb-3');
                newItem.innerHTML = `
            <div class="row g-2 align-items-end">
                <div class="col-md-6 col-12">
                    <label class="form-label">Bahan Baku</label>
                    <select class="form-select form-select-sm" name="id_bahan_baku[]" required>
                        <option selected disabled>Pilih Bahan Baku</option>
                        @foreach ($bahanBaku as $item)
                            <option value="{{ $item->id }}" data-satuan="{{ $item->satuan->nama }}">
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-8">
                    <label class="form-label">Jumlah</label>
                    <div class="input-group input-group-sm">
                        <input type="number" name="jumlah[]" class="form-control" min="0" placeholder="Jumlah" required>
                        <span class="input-group-text satuan-text"></span>
                    </div>
                </div>
                <div class="col-md-2 col-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-bahan-baku">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            `;
                container.appendChild(newItem);

                // Event listener untuk menampilkan satuan saat bahan baku dipilih
                const selectElement = newItem.querySelector('select[name="id_bahan_baku[]"]');
                const satuanText = newItem.querySelector('.satuan-text');

                selectElement.addEventListener('change', function() {
                    const selectedOption = selectElement.selectedOptions[0];
                    const satuan = selectedOption.getAttribute('data-satuan');
                    satuanText.textContent = satuan ? satuan : '';
                });
            });

            // Fungsi untuk menghapus field bahan baku
            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-bahan-baku')) {
                    const item = e.target.closest('.bahan-baku-item');
                    item.remove();
                }
            });
        });

        function tambahBahanBakuUpdate(resepId) {
            const container = document.getElementById(`bahan-baku-container-update-${resepId}`);
            const newItem = document.createElement('div');
            newItem.classList.add('bahan-baku-item', 'mb-3');
            newItem.innerHTML = `
        <div class="row g-2 align-items-end">
            <div class="col-md-6 col-12">
                <label class="form-label">Bahan Baku</label>
                <select class="form-select form-select-sm" name="id_bahan_baku[]" required>
                    <option selected disabled>Pilih Bahan Baku</option>
                    @foreach ($bahanBaku as $item)
                        <option value="{{ $item->id }}" data-satuan="{{ $item->satuan->nama }}">
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-8">
                <label class="form-label">Jumlah</label>
                <div class="input-group input-group-sm">
                    <input type="number" name="jumlah[]" class="form-control" min="0" placeholder="Jumlah" required>
                    <span class="input-group-text satuan-text"></span>
                </div>
            </div>
            <div class="col-md-2 col-4 d-flex justify-content-end">
                <button type="button" class="btn btn-outline-danger btn-sm remove-bahan-baku">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        `;
            container.appendChild(newItem);

            // Event listener untuk menampilkan satuan saat bahan baku dipilih
            const selectElement = newItem.querySelector('select[name="id_bahan_baku[]"]');
            const satuanText = newItem.querySelector('.satuan-text');

            selectElement.addEventListener('change', function() {
                const selectedOption = selectElement.selectedOptions[0];
                const satuan = selectedOption.getAttribute('data-satuan');
                satuanText.textContent = satuan ? satuan : '';
            });
        }

        // Fungsi untuk menghapus field bahan baku
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-bahan-baku')) {
                        const item = e.target.closest('.bahan-baku-item');
                        item.remove();
                    }
                });
            });
        });
    </script>
@endsection
