<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $piutang->nobukti }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .container {
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #9c1515;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #9c1515;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            color: #666;
        }

        .invoice-info {
            margin-bottom: 20px;
        }

        .invoice-info table {
            width: 100%;
        }

        .invoice-info td {
            padding: 5px 0;
        }

        .invoice-info .label {
            font-weight: bold;
            width: 150px;
        }

        .section-title {
            background-color: #9c1515;
            color: white;
            padding: 8px 10px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            font-size: 14px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table thead {
            background-color: #f5f5f5;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            font-weight: bold;
            color: #333;
        }

        .items-table td.center {
            text-align: center;
        }

        .items-table td.right {
            text-align: right;
        }

        .items-table tfoot td {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .summary-box {
            float: right;
            width: 300px;
            margin-top: 10px;
        }

        .summary-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-box td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .summary-box .label {
            font-weight: bold;
            background-color: #f5f5f5;
        }

        .summary-box .value {
            text-align: right;
        }

        .summary-box .total {
            background-color: #9c1515;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 12px;
        }

        .status-lunas {
            background-color: #28a745;
            color: white;
        }

        .status-belum-lunas {
            background-color: #dc3545;
            color: white;
        }

        .payment-history {
            margin-top: 20px;
        }

        .payment-history table {
            width: 100%;
            border-collapse: collapse;
        }

        .payment-history th,
        .payment-history td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .payment-history thead {
            background-color: #f5f5f5;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-success {
            color: #28a745;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>INVOICE KASBON</h1>
            <p>Toko Desa Senggigi</p>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <table>
                <tr>
                    <td class="label">No. Invoice:</td>
                    <td><strong>{{ $piutang->nobukti }}</strong></td>
                    <td class="label" style="width: 150px;">Tanggal Pesanan:</td>
                    <td>{{ $piutang->penjualan->tanggal->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Outlet:</td>
                    <td><strong>{{ $outlet->nama }}</strong></td>
                    <td class="label">Jatuh Tempo:</td>
                    <td>
                        {{ $piutang->jatuh_tempo->format('d F Y') }}
                        @if($piutang->jatuh_tempo < now() && $piutang->status != 'lunas')
                            <span class="text-danger">(Terlambat)</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Penanggung Jawab:</td>
                    <td>{{ $outlet->penanggung_jawab }}</td>
                    <td class="label">Status:</td>
                    <td>
                        @if($piutang->status == 'lunas')
                            <span class="status-badge status-lunas">LUNAS</span>
                        @else
                            <span class="status-badge status-belum-lunas">BELUM LUNAS</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- Items -->
        <div class="section-title">DAFTAR BELANJA</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Barang</th>
                    <th class="center" style="width: 80px;">Qty</th>
                    <th class="right" style="width: 120px;">Harga</th>
                    <th class="right" style="width: 120px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($piutang->penjualan->mutasi as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $item->bahanBaku->nama }}</td>
                    <td class="center">{{ $item->quantity }} {{ $item->bahanBaku->satuan->nama ?? 'pcs' }}</td>
                    <td class="right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="right">TOTAL</td>
                    <td class="right"><strong>Rp {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- Summary -->
        <div class="clearfix">
            <div class="summary-box">
                <table>
                    <tr>
                        <td class="label">Total Tagihan</td>
                        <td class="value">Rp {{ number_format($piutang->jumlah_piutang, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Total Dibayar</td>
                        <td class="value text-success">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total">
                        <td>Sisa Tagihan</td>
                        <td class="value">Rp {{ number_format($sisaPiutang, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Payment History -->
        @if($piutang->pembayaran->count() > 0)
        <div class="payment-history">
            <div class="section-title">RIWAYAT PEMBAYARAN</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Tanggal</th>
                        <th class="right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($piutang->pembayaran as $index => $pembayaran)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $pembayaran->tanggal->format('d F Y') }}</td>
                        <td class="right text-success">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dicetak secara otomatis pada {{ now()->format('d F Y H:i:s') }}</p>
            <p>Terima kasih atas kepercayaan Anda</p>
        </div>
    </div>
</body>

</html>
