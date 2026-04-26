<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Piutang - {{ $outlet_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #D21626;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
            color: #D21626;
        }
        .header p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        .summary-box {
            margin-bottom: 20px;
            width: 100%;
        }
        .summary-table {
            width: auto;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 5px 15px 5px 0;
            font-weight: bold;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th, table.data td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table.data th {
            background-color: #f2f2f2;
            color: #333;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
        }
        .signature {
            width: 300px;
            float: right;
            text-align: center;
        }
        .signature p {
            margin-bottom: 70px;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            width: 80%;
            margin: 0 auto;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN PIUTANG OUTLET</h1>
        <p>Outlet: <strong>{{ $outlet_name }}</strong> | Status: <strong>{{ $status_filter == 'all' ? 'Semua Status' : ($status_filter == 'lunas' ? 'Lunas' : 'Belum Lunas') }}</strong></p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td>Total Keseluruhan Bon (Piutang)</td>
                <td>: Rp {{ number_format($totalPiutang, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Sudah Terbayar</td>
                <td>: Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="color: #D21626;">Sisa Tagihan</td>
                <td style="color: #D21626;">: Rp {{ number_format($totalSisa, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">No. Bukti</th>
                <th width="20%">Tanggal Bon</th>
                <th width="20%">Outlet</th>
                <th class="text-right" width="15%">Nilai Piutang</th>
                <th class="text-right" width="10%">Terbayar</th>
                <th class="text-right" width="15%">Sisa Piutang</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($piutang as $item)
                @php
                    $terbayar = $item->jumlah_piutang - $item->sisa_piutang;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->nobukti }}</td>
                    <td>{{ $item->penjualan->created_at->translatedFormat('d M Y') }}</td>
                    <td>{{ $item->penjualan->outlet->nama }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah_piutang, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($terbayar, 0, ',', '.') }}</td>
                    <td class="text-right font-weight-bold" style="color: {{ $item->sisa_piutang > 0 ? '#D21626' : '#000' }};">
                        Rp {{ number_format($item->sisa_piutang, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data tagihan piutang pada kriteria ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL KESELURUHAN:</td>
                <td class="text-right">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</td>
                <td class="text-right" style="color: #D21626;">Rp {{ number_format($totalSisa, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Admin Keuangan,</p>
            <div class="signature-line"></div>
            <p style="margin-top: 10px; font-weight: bold;">( ........................................ )</p>
        </div>

        <div class="signature" style="float: left;">
            <p>Penerima / Penanggung Jawab Outlet,</p>
            <div class="signature-line"></div>
            <p style="margin-top: 10px; font-weight: bold;">( ........................................ )</p>
        </div>
    </div>

</body>
</html>
