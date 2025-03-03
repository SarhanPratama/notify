@extends('layouts.master')
@section('content')

@include('layouts.breadcrumbs')


<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <!-- Form Basic -->
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-warning">
            <a href="{{ route('cabang.index')}}"  class="btn btn-sm btn-outline-light">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </a>
        </div>
        <div class="card-body">
          <form action="{{ route('cabang.update', $cabang->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row text-sm">
                <div class="col col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="cabang">Cabang</label>
                        <input type="text" class="form-control form-control-sm" id="cabang" name="nama" value="{{ $cabang->nama}}"
                            placeholder="Masukkan nama cabang">
                    </div>
                    <div class="form-group">
                        <label for="telepon">Telepon</label>
                        <input type="text" class="form-control form-control-sm" id="telepon" name="telepon" value="{{ $cabang->telepon}}"
                            placeholder="Masukkan nomor telepon">
                    </div>
                </div>

                <div class="col col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control form-control-sm" name="alamat"  placeholder="Masukkan alamat cabang"  rows="1">{{ $cabang->alamat}}</textarea>
                      </div>
                    <div class="form-group">
                        <label for="">Foto </label>
                        <div class="input-group mb-4">
                            <input type="file" name="foto" class="form-control form-control-sm" id="inputGroupFile02">
                            <label class="input-group-text text-sm" for="inputGroupFile02">Upload</label>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-outline-warning float-end btn-sm">Update</button>
          </form>
        </div>
      </div>
  </div>
</div>
</div>
@endsection

{{-- <div class="modal fade" id="updateCabang" tabindex="-1" aria-labelledby="updateCabangLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 font-weight-bold" id="exampleModalLabel">Form update cabang</h1>
          <i class="bi bi-x-lg btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close"></i>

        </div>
        <form action="{{ route('cabang.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body text-sm">
                <div class="mb-3">
                    <label for="cabang">Cabang</label>
                    <input type="text" name="nama" value="{{ $item->nama }}" class="form-control form-control-sm"  placeholder="Masukkan nama cabang">
                </div>
                <div class="mb-3">
                    <label for="telepon">Telepon</label>
                    <input type="text" name="telepon" value="{{ $item->telepon}}" class="form-control form-control-sm"  placeholder="Masukkan nama telepon">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Alamat</label>
                    <textarea class="form-control form-control-sm" name="alamat" id="exampleFormControlTextarea1" rows="3">{{ $item->alamat}}</textarea>
                </div>
                <div class="mb-3">
                    <label for="formFileSm" class="form-label">Foto</label>
                    <input class="form-control form-control-sm" name="foto" id="formFileSm" type="file">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-outline-primary">Save</button>
            </div>
        </form>
      </div>
    </div>
  </div> --}}
