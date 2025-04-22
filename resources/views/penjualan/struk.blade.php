<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan</title>
    <style>
        @page {
            size: 80mm 297mm;
            margin: 0;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            margin: auto;
            padding: 0;
            width: 80mm;
            background-color: white;
        }

        .receipt-container {
                width: 100%;
                max-width: 76mm;
                padding: 2mm;
                margin: 0 auto;
            }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .logo {
            max-width: 60mm;
            max-height: 20mm;
            margin: 0 auto 5px;
            text-align: center;
        }

        .logo img {
            max-width: 20%;
            max-height: 20%;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }

        .company-details {
            font-size: 10px;
            margin: 2px 0;
        }

        .info {
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .info-label {
            font-weight: bold;
            flex: 1;
        }

        .info-value {
            flex: 1;
            text-align: right;
        }

        .items {
            margin-bottom: 10px;
        }

        .item {
            margin-bottom: 8px;
        }

        .item-name {
            font-weight: bold;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .totals {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 10px 0;
            margin: 10px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .total-label {
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
        }

        .barcode {
            text-align: center;
            margin: 15px 0;
        }

        .barcode img {
            max-width: 60mm;
        }

        .divider {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }

        .dotted-line {
            border-bottom: 1px dotted #000;
            margin: 3px 0;
        }

        .signature {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            width: 45%;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #000;
        }

        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .receipt-container {
                width: 100%;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('assets/img/logo/icon2.png')}}" alt="Logo Perusahaan">
            </div>
            <div class="company-name">SEROO</div>
            <div class="company-details">{{ $penjualan->cabang->nama ?? 'Cabang Utama' }}</div>
            <div class="company-details">{{ $penjualan->cabang->alamat ?? 'Jl. Contoh Alamat No. 123' }}</div>
            <div class="company-details">Telp: {{ $penjualan->cabang->telepon ?? '(021) 1234-5678' }}</div>
        </div>

        <div class="info">
            <div class="info-row">
                <span class="info-label">No. Penjualan</span>
                <span class="info-value">{{ $penjualan->nobukti ?? 'PJL-001' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value">{{ $penjualan->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Pelanggan</span>
                <span class="info-value">{{ $penjualan->cabang->nama ?? 'Umum' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kasir</span>
                <span class="info-value">{{ Auth::user()->name ?? 'Admin' }}</span>
            </div>
        </div>

        <div class="items">
            @if($penjualan->mutasi && count($penjualan->mutasi) > 0)
                @foreach($penjualan->mutasi as $item)
                <div class="item">
                    <div class="item-details">
                    <div class="item-name">{{ ucwords($item->bahanBaku->nama) ?? 'Bahan Tidak Tersedia' }}</div>
                        <span>{{ $item->quantity }} {{ $item->bahanBaku->satuan->nama ?? 'Pcs' }}</span>
                        <span>Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="item-details">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format(($item->sub_total) ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="dotted-line"></div>
                @endforeach
            @else
                <div class="item">
                    <div class="item-name">Tidak ada item</div>
                </div>
            @endif
        </div>

        <div class="totals">
            <div class="total-row">
                <span class="total-label">Total Item</span>
                <span>{{ $penjualan->mutasi ? count($penjualan->mutasi) : '0' }} Item</span>
            </div>
            <div class="total-row">
                <span class="total-label">Subtotal</span>
                <span>Rp {{ number_format($penjualan->subtotal ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="total-label">Diskon</span>
                <span>Rp {{ number_format($penjualan->diskon ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="total-label">Pajak</span>
                <span>Rp {{ number_format($penjualan->pajak ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="total-row" style="font-weight: bold; font-size: 14px;">
                <span class="total-label">TOTAL</span>
                <span>Rp {{ number_format($penjualan->total ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="total-label">Bayar</span>
                <span>Rp {{ number_format($penjualan->bayar ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span class="total-label">Kembali</span>
                <span>Rp {{ number_format($penjualan->kembali ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="barcode">
            <img src="/api/placeholder/60/20" alt="Barcode">
            <div>{{ $penjualan->kode ?? 'PJL-001' }}</div>
        </div>

        <div class="footer">
            <p>Terima kasih atas kunjungan Anda</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
            <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
        </div>

        <div class="no-print" style="margin-top: 20px; text-align: center;">
            <button onclick="window.print()" style="padding: 10px 20px; background: #2c3e50; color: white; border: none; border-radius: 5px; cursor: pointer;">Cetak Struk</button>
            <button onclick="downloadPDF()" style="padding: 10px 20px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">Unduh PDF</button>
        </div>
    </div>

    <script>
        function downloadPDF() {
            // Pada implementasi nyata, ini bisa dihubungkan ke fungsi backend
            // untuk menghasilkan PDF yang dapat diunduh
            window.print();

            // Contoh implementasi dengan endpoint download:

        }
    </script>
</body>
</html>
