@extends('layouts.master')

@section('content')
@include('layouts.breadcrumbs')
<div class="container-fluid">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Saldo Awal Total</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalSaldoAwal,0,',','.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pemasukan</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPemasukan,0,',','.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPengeluaran,0,',','.') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Saldo Akhir</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalSaldoAkhir,0,',','.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Saldo per Dompet</h6>
                    <small class="text-muted">Total Dompet: {{ $saldoPerDompet->count() }}</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTableHover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:6%">No</th>
                                <th>Nama Dompet</th>
                                <th class="text-right">Saldo Awal</th>
                                <th class="text-right">Pemasukan</th>
                                <th class="text-right">Pengeluaran</th>
                                <th class="text-right">Saldo Akhir</th>
                                <th class="text-center" style="width:10%">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = $totalSaldoAkhir > 0 ? $totalSaldoAkhir : 1; @endphp
                            @forelse($saldoPerDompet as $row)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $row->nama }}</strong><br>
                                        {{-- <small class="text-muted">ID: {{ $row->id_sumber_dana }}</small> --}}
                                    </td>
                                    <td class="text-right">Rp {{ number_format($row->saldo_awal,0,',','.') }}</td>
                                    <td class="text-right text-success">Rp {{ number_format($row->total_pemasukan,0,',','.') }}</td>
                                    <td class="text-right text-danger">Rp {{ number_format($row->total_pengeluaran,0,',','.') }}</td>
                                    <td class="text-right font-weight-bold {{ $row->saldo_current < 0 ? 'text-danger' : 'text-primary' }}">Rp {{ number_format($row->saldo_current,0,',','.') }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ number_format(($row->saldo_current / $grandTotal)*100,1) }}%</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i><br>
                                        <span class="text-muted">Belum ada data sumber dana.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($saldoPerDompet->count())
                        <tfoot class="thead-light">
                            <tr>
                                <th colspan="2" class="text-right">TOTAL:</th>
                                <th class="text-right">Rp {{ number_format($totalSaldoAwal,0,',','.') }}</th>
                                <th class="text-right text-success">Rp {{ number_format($totalPemasukan,0,',','.') }}</th>
                                <th class="text-right text-danger">Rp {{ number_format($totalPengeluaran,0,',','.') }}</th>
                                <th class="text-right">Rp {{ number_format($totalSaldoAkhir,0,',','.') }}</th>
                                <th class="text-center">100%</th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
