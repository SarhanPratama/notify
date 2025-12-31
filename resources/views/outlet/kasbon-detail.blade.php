@extends('layouts.outlet')

@section('content')
<div class="container-fluid mt-3">
    <div class="mb-4 d-flex align-items-center">
        <div class="ms-1">
            <a href="{{ route('outlet.kasbon', ['token' => $token]) }}" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <div class="flex-grow-1 text-center">
            <h4 class="fw-bold text-maron mb-1 fs-4">Detail Kasbon</h4>
            <p class="text-muted mb-0"><strong>{{ $piutang->nobukti }}</strong></p>
        </div>
    </div>

    <div class="row g-3">
        <!-- Left Column: Main Info -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="row mb-3 text-center">
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block">Tanggal Pesanan</small>
                            <span
                                class="fw-semibold">{{ $piutang->penjualan->tanggal->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block">Jatuh Tempo</small>
                            <span
                                class="fw-semibold {{ $isJatuhTempo ? 'text-danger' : '' }}">{{ $piutang->jatuh_tempo->translatedFormat('l, d F Y') }}</span>
                            @if ($isJatuhTempo)
                                <span class="badge bg-danger ms-1">Terlambat</span>
                            @endif
                        </div>
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block">Status</small>
                            @if ($piutang->status == 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-danger">Belum Lunas</span>
                            @endif
                        </div>
                    </div>
                    <hr class="mt-0">
                    <h6 class="fs-5 fw-bold my-3 text-center text-maron">Daftar Belanja</h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-nowrap">Nama Barang</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($piutang->penjualan->mutasi as $item)
                                    <tr>
                                        <td>{{ $item->bahanBaku->nama }}</td>
                                        <td class="text-center">{{ $item->quantity }}
                                            {{ $item->bahanBaku->satuan->nama ?? 'pcs' }}</td>
                                        <td class="text-end text-nowrap">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td class="text-end text-nowrap">Rp {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th class="text-end text-nowrap">Rp {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fs-5 fw-bold mb-3 text-center text-maron">Riwayat Pembayaran</h6>
                    @if ($piutang->pembayaran->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($piutang->pembayaran as $pembayaran)
                                        <tr class="text-nowrap">
                                            <td>{{ $pembayaran->tanggal->translatedFormat('l, d/m/Y') }}</td>
                                            <td class="text-end text-success fw-semibold">Rp
                                                {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr class="text-nowrap">
                                        <th>Total Dibayar</th>
                                        <th class="text-end text-success fw-bold">Rp
                                            {{ number_format($totalBayar, 0, ',', '.') }}</th>
                                    </tr>
                                    @if ($piutang->status != 'lunas')
                                        <tr>
                                            <th>Sisa Tagihan</th>
                                            <th class="text-end text-danger fw-bold">Rp
                                                {{ number_format($sisaPiutang, 0, ',', '.') }}</th>
                                        </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0 text-info text-center"><i class="fas fa-info-circle me-2"></i>Belum ada pembayaran
                            untuk tagihan ini.</div>
                    @endif
                </div>
            </div>

        </div>

        {{-- <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="fs-5 fw-bold mb-3 text-center text-maron">Ringkasan Tagihan</h6>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1"><span>Total Tagihan:</span><strong>Rp
                                {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</strong></div>
                        <div class="d-flex justify-content-between mb-1"><span>Total Dibayar:</span><strong
                                class="text-success">Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>Sisa:</span><strong
                                class="{{ $sisaPiutang > 0 ? 'text-danger' : 'text-success' }}">Rp
                                {{ number_format($sisaPiutang, 0, ',', '.') }}</strong></div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('outlet.kasbon.download', ['token' => $token, 'id' => $piutang->id]) }}"
                           class="btn btn-outline-secondary">
                            <i class="fas fa-download me-2"></i>Download Invoice
                        </a>
                        <a href="{{ route('outlet.kasbon', ['token' => $token]) }}" class="btn btn-outline-primary">Kembali</a>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>
    <script>
        function hubungiAdmin() {
            Swal.fire({
                icon: 'info',
                title: 'Hubungi Admin',
                text: 'Fitur chat dengan admin sedang dalam pengembangan',
                confirmButtonColor: '#9c1515'
            });
        }
    </script>
@endsection
