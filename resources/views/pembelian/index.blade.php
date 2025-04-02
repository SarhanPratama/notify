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
                        <a href="{{ route('pembelian.create') }}" type="button" class="btn btn-outline-light btn-sm btn-lg">
                            Tambah
                        </a>
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
                                    <th class="text-center text-nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembelian as $item)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y, H:i') }}</td>
                                        <td class="align-middle">{{ $item->kode }}</td>
                                        <td class="align-middle">{{ $item->supplier->nama }}</td>
                                        <td class="align-middle text-nowrap">
                                            @if ($item->status == 'Completed')
                                                <span class="badge badge-success">{{ ucwords($item->status) }}</span>
                                            @elseif($item->status == 'Cancelled')
                                                <span class="badge badge-danger">{{ ucwords($item->status) }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ ucwords($item->status) }}</span>
                                            @endif
                                        </td>

                                        <td class="align-middle">Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                                        <td class="d-flex gap-3">
                                            @can('update status pembelian')
                                                <form action="{{ route('pembelian.updateStatus', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    @if ($item->status == 'Completed' || $item->status == 'Cancelled')
                                                        <!-- Status is already 'Completed', no button needed -->
                                                    @elseif ($item->status == 'Pending')
                                                        <!-- Show "Completed" button -->
                                                        <button type="submit" name="status" value="Completed"
                                                            class="btn btn-outline-success btn-sm">
                                                            <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                                        </button>
                                                        <button type="submit" name="status" value="Cancelled"
                                                            class="btn btn-outline-danger btn-sm">
                                                            <i class="fa fa-times-circle" aria-hidden="true"></i>
                                                        </button>
                                                    @elseif ($item->status == 'Cancelled')
                                                        <button type="submit" name="status" value="Pending"
                                                            class="btn btn-outline-warning btn-sm">
                                                            <i class="fa fa-hourglass-half" aria-hidden="true"></i> Pending
                                                        </button>
                                                        <!-- Show "Pending" button -->
                                                    @endif

                                                </form>
                                                @endcan
                                                <button class="btn btn-outline-warning btn-sm" data-toggle="modal"
                                                    data-target="#detailModal{{ $item->id }}">
                                                    <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                                                </button>
                                        </td>
                                    </tr>
                                    @include('pembelian.edit')
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

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add row for products in the edit detail table
            document.getElementById('tambah-detail-edit').addEventListener('click', function() {
                const newRow = document.querySelector('.detail-item').cloneNode(true);
                newRow.querySelectorAll('input').forEach(input => input.value = '');
                newRow.querySelector('select').selectedIndex = 0;
                document.getElementById('detail-pembelian-edit').appendChild(newRow);
                updateTotalEdit();
            });

            // Event listener to remove row
            document.getElementById('detail-pembelian-edit').addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    const row = e.target.closest('tr');
                    if (document.querySelectorAll('#detail-pembelian-edit tr').length > 1) {
                        row.remove();
                        updateTotalEdit();
                    }
                }
            });

            // Event listener for changes in quantity or price to calculate total
            document.getElementById('detail-pembelian-edit').addEventListener('input', function(e) {
                const target = e.target;
                if (target.classList.contains('quantity') || target.classList.contains('harga')) {
                    const row = target.closest('tr');
                    const qty = parseFloat(row.querySelector('.quantity').value) || 0;
                    const harga = parseFloat(row.querySelector('.harga').value) || 0;
                    row.querySelector('.total-harga').value = (qty * harga).toLocaleString('id-ID');
                    updateTotalEdit();
                }
            });

            // Update total calculation
            function updateTotalEdit() {
                let total = 0;
                document.querySelectorAll('#detail-pembelian-edit .total-harga').forEach(input => {
                    total += parseFloat(input.value.replace(/[^0-9]/g, '')) || 0;
                });
                document.getElementById('total-keseluruhan-edit').value = total.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                });
            }
        });
    </script> --}}
@endsection
