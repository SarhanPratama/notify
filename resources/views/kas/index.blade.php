@extends('layouts.master')

@section('content')
    @include('layouts.breadcrumbs')

    <div class="container-fluid">
        <div class="row">
            <div class="col col-lg-12">
                <!-- Simple Tables -->
                  <a href="{{ route('transaksi.create') }}" type="button" class="btn btn-outline-primary mb-3 fw-bold">
                        Tambah
                    </a>
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-maron">
                        <h6 class="font-weight-bold text-light text-sm">{{ $breadcrumbs[count($breadcrumbs) - 1]['label'] }}
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped text-sm" id="dataTableHover">
                            <thead class="thead-light">
                                <tr class="text-nowrap">
                                    <th class="text-start">No</th>
                                    <th>Tanggal</th>
                                    <th>Sumber Dana</th>
                                    <th>Type</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kas as $item)
                                    <tr>
                                        <td class="align-middle text-nowrap">{{ $loop->iteration }}</td>
                                        <td class="align-middle text-nowrap">
                                            <span class="badge badge-light p-2 text-dark">
                                                <i class="far fa-calendar-alt text-maron mr-1"></i>
                                                {{ $item->tanggal->format('d M Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-nowrap"> {{ $item->SumberDana->nama }}</td>
                                        <td class="align-middle text-nowrap"> <span
                                                class="badge fw-bolder {{ $item->tipe === 'credit' ? 'bg-danger' : 'bg-success' }}">
                                                {{ ucwords($item->tipe) }}
                                            </span></td>
                                        <td class="align-middle text-nowrap">Rp.
                                            {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                        <td class="align-middle text-nowrap">{{ $item->deskripsi }}</td>

                                        <td class="d-flex justify-content-center text-nowrap gap-2">
                                            {{-- <div>
                                                <a href="{{ route('produk.edit', $item->id) }}"
                                                    class="btn btn-sm btn-outline-warning">
                                                    <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                                                </a>
                                            </div> --}}
                                            <div>
                                                <a href="{{ route('transaksi.show', $item->id) }}"
                                                    class="btn btn-sm btn-outline-success">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                            {{-- <div>
                                                <button class="btn btn-sm btn-outline-danger" data-toggle="modal"
                                                    data-target="#DestroyModal{{ $item->id }}">
                                                    <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                                </button>
                                            </div> --}}
                                        </td>
                                    </tr>
                                    {{-- <div class="modal fade" id="DestroyModal{{ $item->id }}" tabindex="-1"
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
                                    </div> --}}
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
@endsection
