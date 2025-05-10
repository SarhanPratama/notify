@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

        <div class="row">
            <div class="col col-lg-12">
                <!-- Simple Tables -->
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
                        </h6>

                        <a href="{{ route('kas.create') }}" type="button" class="btn btn-outline-light btn-sm btn-lg">
                            Tambah
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="dataTableHover">
                            <thead class="thead-light">
                                <tr class="text-nowrap">
                                    <th class="text-start">No</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Nominal</th>
                                    <th>Sumber Dana</th>
                                    <th>Jenis Transaksi</th>
                                    <th>Keperluan</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kas as $item)
                                    <tr>
                                        <td class="align-middle text-nowrap">{{ $loop->iteration }}</td>
                                        <td class="align-middle text-nowrap">
                                            <span class="badge badge-light p-2 text-dark">
                                                <i class="far fa-calendar-alt text-maron mr-1"></i>
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-nowrap"> {{ $item->keterangan }}</td>
                                        <td class="align-middle text-nowrap">Rp.
                                            {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        <td class="align-middle text-nowrap">{{ $item->sumber_dana }}</td>
                                        <td class="align-middle text-nowrap">{{ $item->jenis_transaksi }}</td>
                                        <td class="align-middle text-nowrap">{{ $item->Keperluan }}</td>

                                        <td class="d-flex justify-content-center text-nowrap gap-2">
                                            <div>
                                                <a href="{{ route('produk.edit', $item->id) }}"
                                                    class="btn btn-sm btn-outline-warning">
                                                    <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                            <div>
                                                <a href="{{ route('produk.show', $item->id) }}"
                                                    class="btn btn-sm btn-outline-success">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-outline-danger" data-toggle="modal"
                                                    data-target="#DestroyModal{{ $item->id }}">
                                                    <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="DestroyModal{{ $item->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="DestroyModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-maron">
                                                    <h5 class="modal-title font-weight-bold text-light"
                                                        id="DestroyModalLabel">Konfirmasi Hapus</h5>
                                                    <button type="button" class="close text-light" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah anda yakin ingin menghapus produk
                                                        <strong>"{{ $item->nama }}"</strong>?</p>
                                                </div>
                                                <form action="{{ route('cabang.destroy', $item->id) }}" method="POST">
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
@endsection
