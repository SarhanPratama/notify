@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-4">
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
                        <table class="table table-striped table-hover text-sm text-nowrap" id="dataTableHover">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kode</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembelian as $item)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y, H:i') }}</td>
                                        <td class="align-middle">{{ $item->kode }}</td>
                                        <td class="align-middle">{{ $item->supplier->nama }}</td>
                                        <td class="align-middle"><span class="badge badge-warning">{{ $item->status }}</span></td>
                                        <td class="align-middle">{{ $item->total }}</td>
                                        <td class="d-flex justify-content-center">
                                            <!-- Tombol untuk membuka modal edit permission -->
                                            <button class="btn btn-outline-warning btn-sm" data-toggle="modal"
                                                data-target="#editRoleModal{{ $item->id }}">
                                                <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Permission -->
                                    {{-- <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1"
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
                                    </div> --}}
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

    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #6777ef">
                    <h1 class="modal-title fs-6 font-weight-bold text-light" id="createModalLabel">Form Tambah</h1>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('pembelian.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body text-sm fw-bold">
                        <div class="mb-3">
                            <label for="id_supplier" class="form-label">Supplier</label>
                            <select class="form-control form-control-sm" id="id_supplier" name="id_supplier" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($suppliers as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Detail Pembelian -->
                        <div id="detail-pembelian">
                            <div class="detail-item mb-3">
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-8 mb-3">
                                        <label for="id_produk" class="form-label">Produk</label>
                                        <select class="form-control form-control-sm" name="produk[]" required>
                                            <option>-- Pilih Produk --</option>
                                            @foreach ($produk as $id => $nama)
                                                <option value="{{ $id }}">{{ $nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-4">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" class="form-control form-control-sm quantity"
                                            name="quantity[]" min="1" required>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-5">
                                        <label class="form-label">Harga</label>
                                        <input type="number" class="form-control form-control-sm harga" name="harga[]"
                                            min="0" step="0.01" required>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-5">
                                        <label class="form-label">Total Harga</label>
                                        <input type="text" class="form-control form-control-sm total-harga" readonly>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-2 d-flex justify-content-end align-items-end">
                                        <div>
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-bahan-baku">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Tombol Tambah Produk -->
                        <button type="button" id="tambah-detail"
                            class="btn btn-sm btn-outline-primary mb-3">Tambah</button>

                        <!-- Total Keseluruhan -->
                        <div class="mb-3">
                            <label class="form-label">Total Keseluruhan</label>
                            <input type="text" class="form-control" id="total-keseluruhan" readonly>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailContainer = document.getElementById('detail-pembelian');
            const tambahDetailButton = document.getElementById('tambah-detail');

            // Fungsi untuk menambahkan detail pembelian
            tambahDetailButton.addEventListener('click', function() {
                const newItem = document.createElement('div');
                newItem.classList.add('detail-item', 'mb-3');
                newItem.innerHTML = `
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-8 mb-3">
                            <label for="id_supplier" class="form-label">Supplier</label>
                                <select class="form-control form-control-sm" id="id_supplier" name="id_supplier" required>
                                    <option>-- Pilih Produk --</option>
                                    @foreach ($produk as $id => $nama)
                                        <option value="{{ $id }}">{{ $nama }}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-4">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control form-control-sm quantity" name="quantity[]" min="1" required>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-5">
                            <label class="form-label">Harga</label>
                            <input type="number" class="form-control form-control-sm harga" name="harga[]" min="0" step="0.01" required>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-5">
                            <label class="form-label">Total Harga</label>
                            <input type="text" class="form-control form-control-sm total-harga" readonly>
                        </div>
                        <div class="col-lg-2 col-md-2 col-2 d-flex justify-content-end align-items-end">
                            <div>
                                <button type="button"
                                    class="btn btn-outline-danger btn-sm remove-bahan-baku">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                detailContainer.appendChild(newItem);
            });

            // Fungsi untuk menghapus detail pembelian
            detailContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove')) {
                    e.target.closest('.detail-item').remove();
                    hitungTotalKeseluruhan(); // Hitung ulang total keseluruhan
                }
            });

            // Fungsi untuk menghitung total harga per item
            detailContainer.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity') || e.target.classList.contains('harga')) {
                    const row = e.target.closest('.row');
                    const quantity = row.querySelector('.quantity').value;
                    const harga = row.querySelector('.harga').value;
                    const totalHarga = row.querySelector('.total-harga');

                    if (quantity && harga) {
                        totalHarga.value = (quantity * harga);
                    } else {
                        totalHarga.value = '';
                    }

                    hitungTotalKeseluruhan(); // Hitung ulang total keseluruhan
                }
            });

            // Fungsi untuk menghitung total keseluruhan
            function hitungTotalKeseluruhan() {
                let totalKeseluruhan = 0;
                document.querySelectorAll('.total-harga').forEach(totalHarga => {
                    if (totalHarga.value) {
                        totalKeseluruhan += parseFloat(totalHarga.value);
                    }
                });
                document.getElementById('total-keseluruhan').value = totalKeseluruhan;
            }
        });
    </script>
@endsection
