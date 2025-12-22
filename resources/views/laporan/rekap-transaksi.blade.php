@extends('layouts.master')

@section('content')

<div class="container-fluid">
        @include('layouts.breadcrumbs')
        <!-- Filter Card -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-maron">
                        <div class="d-flex align-items-center">
                            <h6 class="font-weight-bold text-white mb-0">Filter Rekap Transaksi</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('laporan.rekap-transaksi') }}" method="GET" class="row align-items-end">
                            <div class="col-md-3">
                                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                                <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal"
                                    value="{{ $tanggal_awal }}">
                            </div>
                            <div class="col-md-3">
                                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir"
                                    value="{{ $tanggal_akhir }}">
                            </div>
                            <div class="col-md-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select class="form-control" id="kategori" name="kategori">
                                    <option value="all" {{ $kategori_filter == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $kategori_filter == $cat->id ? 'selected' : '' }}>{{ $cat->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <!-- Total Pemasukan -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Pemasukan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-down fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pengeluaran -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 p-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total Pengeluaran
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-up fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekap Transaksi Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <h6 class="font-weight-bold text-primary m-0">Rekap Transaksi</h6>
                        <div>
                            <a href="{{ route('laporan.rekap-transaksi.export', [
                                'tanggal_awal' => request('tanggal_awal'),
                                'tanggal_akhir' => request('tanggal_akhir'),
                                'kategori' => request('kategori', 'all')
                            ]) }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-file-excel mr-1"></i> Export Excel
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTableHover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Tanggal</th>
                                    <th width="10%">Hari</th>
                                    <th width="10%">Tipe</th>
                                    <th width="15%">Kategori</th>
                                    <th>Keterangan</th>
                                    <th width="15%" class="text-right">Nominal</th>
                                    <th width="10%">Posisi Kas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksi as $trx)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $trx->tanggal->format('d/m/Y') }}</td>
                                        <td>{{ $trx->tanggal->translatedFormat('l') }}</td>
                                        <td>{{ $trx->kategoriKeuangan->jenis ?? '-' }}</td>
                                        <td>{{ $trx->kategoriKeuangan->nama ?? '-' }}</td>
                                        <td>{{ $trx->deskripsi }}</td>
                                        <td class="text-right">
                                            <span class="{{ $trx->tipe == 'debit' ? 'text-success' : 'text-danger' }}">
                                                Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>{{ $trx->posisi_kas }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTableHover').DataTable({
                "order": [[ 1, "asc" ]]
            });
        });
    </script>
@endpush
