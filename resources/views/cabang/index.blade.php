@extends('layouts.master')

@section('content')


    @include('layouts.breadcrumbs')

  <div class="container">
    <div class="row">
        <div class="col col-lg-12">
        <!-- Simple Tables -->

            <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    {{-- <h6 class="font-weight-bold text-primary text-sm">Tabel Cabang</h6> --}}

                    <div class="dropup-center dropup">
                        <a class="" type="" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-search fa-fw"></i>
                        </a>
                        <ul class="dropdown-menu mb-4 px-2" style="width: 300px;">
                            <form action="{{ route('cabang.index')}}" method="get">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                                    <button class="btn btn-outline-primary btn-sm" type="submit" id="button-addon2"><i class="fas fa-search fa-fw"></i></button>
                                </div>
                            </form>
                        </ul>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tambah
                    </button>
                </div>
                <div class="table-responsive">
                <table class="table table-striped text-sm">
                    <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Cabang</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($cabang as $item)

                        <tr>
                            <td>{{ ($cabang->currentPage() - 1) * $cabang->perPage() + $loop->iteration }}</td>
                            <td>
                                {{-- <img src="{{ asset('storage/images/' . $item->foto) }}" alt="Gambar" width="100"> --}}


                                {{ $item->nama}}</td>
                            <td>{{ $item->telepon}}</td>
                            {{-- <td class="">{{ $item->alamat}}</td> --}}
                            <td class="text-truncate" style="max-width: 200px;"  data-bs-toggle="tooltip" title="{{ $item->alamat }}">
                                {{ Str::limit($item->alamat, 30, '...') }}
                            </td>
                            {{-- <td><span class="badge badge-success">Delivered</span></td> --}}
                            <td class="d-flex text-nowrap">
                                <div>
                                    <a href="{{ route('cabang.edit', $item->id)}}"  class="btn btn-sm btn-outline-warning me-1">
                                        <i class="fa fa-pencil fs-6" aria-hidden="true"></i>
                                    </a>
                                </div>

                                <div>
                                    <form action="{{ route('cabang.destroy', $item->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fa fa-trash fs-6" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                </div>

                    <div class="card-footer d-flex justify-content-center">
                        {{ $cabang->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
                    </div>

            </div>
        </div>
    </div>
  </div>
  @include('cabang.create')
  {{-- @include('cabang.update') --}}
@endsection
