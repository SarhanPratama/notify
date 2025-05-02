<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok - Seroo</title>


<style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        color: #2c3e50;
        background-color: #fff;
        margin: 0;
        padding: 40px;
    }

    .logo {
        text-align: center;
    }

    .header h2 {
        text-align: center;
    }

    .header p {
        font-size: 14px;
        color: #95a5a6;
        margin-top: -5px;
    }

    .info-block {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
        font-size: 13px;
    }

    .info-item h3 {
        font-weight: 600;
        color: #7f8c8d;
        margin-bottom: 5px;
    }

    .info-item p {
        margin: 0;
        font-weight: 600;
        color: #2c3e50;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
        font-size: 13px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px 8px;
        text-align: center;
    }

    th {
        background-color: #34495e;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .summary {
        background-color: #f4f6f7;
        padding: 20px;
        border-left: 5px solid #2c3e50;
        margin-bottom: 30px;
    }

    .summary h3 {
        margin-bottom: 10px;
        font-size: 16px;
        color: #2c3e50;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .summary-item span:first-child {
        font-weight: 600;
    }

    .footer {
        font-size: 13px;
        color: #7f8c8d;
        border-top: 1px solid #ccc;
        padding-top: 20px;
        margin-top: 40px;
        display: flex;
        justify-content: space-between;
    }

    .footer .signature {
        text-align: right;
    }

    .footer .signature b {
        display: inline-block;
        margin-top: 60px;
    }

    @media print {
        body {
            padding: 0;
            margin: 0;
            background-color: white;
        }

        .container {
            box-shadow: none;
            padding: 0;
        }

        .noprint {
            display: none;
        }
    }
</style>

</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ public_path('assets/img/logo/icon2.png') }}" alt="Logo Perusahaan" width="50px">
        </div>
        <div class="header">
            <h2>Laporan Stok Bahan Baku</h2>
            <p>Periode: <span id="periode">{{ date('d F Y') }}</span></p>
        </div>

        {{-- <div class="info-block">
            <div class="info-item">
                <h3>Tanggal Cetak</h3>
                <p>{{ date('d/m/Y H:i') }}</p>
            </div>
            <div class="info-item">
                <h3>Kode Laporan</h3>
                <p>STK-{{ date('Ymd') }}</p>
            </div>
            <div class="info-item">
                <h3>Dicetak Oleh</h3>
                <p>{{ Auth::user()->name ?? 'Administrator' }}</p>
            </div>
        </div> --}}

        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Barang</th>
                    <th width="10%">Satuan</th>
                    <th width="13%">Stok Awal</th>
                    <th width="14%">Total Masuk</th>
                    <th width="14%">Total Keluar</th>
                    <th width="14%">Saldo Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporan_stok as $index => $d)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left;">{{ $d->nama }}</td>
                    <td>{{ $d->nama_satuan }}</td>
                    <td>{{ number_format($d->stok_awal, 0, ',', '.') }}</td>
                    <td>{{ number_format($d->masuk, 0, ',', '.') }}</td>
                    <td>{{ number_format($d->keluar, 0, ',', '.') }}</td>
                    <td>{{ number_format($d->saldoakhir, 0, ',', '.') }}</td>
                </tr>
                @endforeach

                @if(count($laporan_stok) == 0)
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px;">Tidak ada data yang tersedia</td>
                </tr>
                @endif
            </tbody>
        </table>

        {{-- <div class="summary">
            <h3>Ringkasan Laporan</h3>
            <div class="summary-item">
                <span>Total Jenis Barang</span>
                <span>{{ count($laporan_stok) }} item</span>
            </div>
            <div class="summary-item">
                <span>Total Stok Awal</span>
                <span>{{ number_format($laporan_stok->sum('stok_awal'), 0, ',', '.') }} unit</span>
            </div>
            <div class="summary-item">
                <span>Total Barang Masuk</span>
                <span>{{ number_format($laporan_stok->sum('totalmasuk'), 0, ',', '.') }} unit</span>
            </div>
            <div class="summary-item">
                <span>Total Barang Keluar</span>
                <span>{{ number_format($laporan_stok->sum('totalkeluar'), 0, ',', '.') }} unit</span>
            </div>
            <div class="summary-item">
                <span>Total Saldo Akhir</span>
                <span>{{ number_format($laporan_stok->sum('saldoakhir'), 0, ',', '.') }} unit</span>
            </div>
        </div> --}}

        <div class="footer">
            <p>
                <span style="float: left;">PT. Nama Perusahaan</span><br>
                {{ date('d F Y') }}
                <span>
                    <br>
                    Mengetahui,<br><br><br><br>
                    <b>________________</b><br>
                    Manajer Gudang
                </span>
            </p>
        </div>
    </div>
</body>
</html>
