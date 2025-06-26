<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok - Seroo</title>

<style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        color: #333; /* Slightly darker default text for better contrast */
        background-color: #f4f6f9; /* Light gray background for the page */
        margin: 0;
        padding: 20px; /* Add some padding for when viewed in browser */
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .container {
        max-width: 800px; /* A4-like width, or adjust as needed */
        margin: 20px auto; /* Centering and top/bottom margin */
        padding: 30px 40px; /* Inner padding for the report content */
        background-color: #fff;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07); /* Softer, more modern shadow */
        border-radius: 8px; /* Rounded corners for the "page" */
    }

    .logo {
        text-align: center;
        margin-bottom: 20px;
    }
    .logo img {
        max-width: 60px; /* Slightly larger logo if appropriate */
        height: auto;
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
    }
    .header h2 {
        font-size: 24px; /* More prominent title */
        color: #2c3e50;
        margin-bottom: 5px;
    }
    .header p {
        font-size: 14px;
        color: #7f8c8d; /* Lighter color for subtitle */
        margin-top: 0;
    }

    .info-block { /* Styling for the commented out info block if used */
        display: flex;
        justify-content: space-between;
        margin-bottom: 25px;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 6px;
        font-size: 13px;
    }
    .info-item h3 {
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
        font-size: 12px;
        text-transform: uppercase;
    }
    .info-item p {
        margin: 0;
        font-weight: 500;
        color: #2c3e50;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        font-size: 13px;
    }

    th, td {
        border: 1px solid #e0e0e0; /* Lighter border color for cells */
        padding: 10px 12px; /* Slightly more padding */
        text-align: center;
        vertical-align: middle; /* Ensure vertical centering */
    }

    th {
        background-color: #34495e; /* Dark blue-gray for header */
        color: #fff;
        font-weight: 600; /* Standard bold for headers */
        text-transform: uppercase; /* Uppercase for table headers */
        font-size: 12px;
    }
    /* Specific alignments for table columns */
    th.text-left, td.text-left {
        text-align: left;
    }
    th.text-right, td.text-right {
        text-align: right;
    }
    td.text-right {
        font-family: 'Consolas', 'Menlo', monospace; /* Monospace font for numbers */
    }


    tr:nth-child(even) td { /* Apply to td for better specificity */
        background-color: #f8f9fa; /* Lighter even row color */
    }

    tr:hover td { /* Apply to td */
        background-color: #f1f6f9; /* Subtle hover effect */
    }

    .summary { /* Styling for the commented out summary block if used */
        background-color: #e9ecef; /* Lighter summary background */
        padding: 20px;
        border-left: 4px solid #34495e; /* Accent border */
        margin-bottom: 30px;
        border-radius: 6px;
    }
    .summary h3 {
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 16px;
        color: #2c3e50;
        font-weight: 600;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-bottom: 8px;
        padding-bottom: 8px;
        border-bottom: 1px dashed #ced4da;
    }
    .summary-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .summary-item span:first-child {
        font-weight: 500;
        color: #555;
    }
    .summary-item span:last-child {
        font-weight: 600;
        color: #2c3e50;
    }


    .footer {
        font-size: 12px; /* Slightly smaller footer text */
        color: #7f8c8d;
        border-top: 1px solid #e0e0e0; /* Lighter border */
        padding-top: 20px;
        margin-top: 30px; /* Reduced margin if summary is not present */
        display: flex;
        justify-content: space-between;
        align-items: flex-end; /* Align items to the bottom */
    }
    .footer .company-info p {
        margin: 0;
        line-height: 1.6;
    }
    .footer .signature {
        text-align: center; /* Center signature block */
    }
    .footer .signature p {
        margin-bottom: 50px; /* Space for signature */
    }
    .footer .signature b {
        display: block; /* Make it block for better spacing */
        margin-top: 0; /* Reset margin-top if p handles spacing */
        border-top: 1px solid #7f8c8d; /* Line for signature */
        padding-top: 5px;
        min-width: 180px; /* Minimum width for signature line */
    }

    @media print {
        body {
            padding: 0;
            margin: 0;
            background-color: #fff;
            font-size: 12pt; /* Adjust base font size for print */
        }
        .container {
            box-shadow: none;
            border-radius: 0;
            padding: 20mm 15mm; /* Standard A4 margins */
            margin: 0;
            max-width: 100%;
        }
        th, td {
            padding: 8px 10px; /* Adjust padding for print */
            font-size: 10pt;
        }
        th {
             font-size: 9pt;
        }
        .header h2 {
            font-size: 20pt;
        }
        .header p {
            font-size: 11pt;
        }
        .footer {
            font-size: 10pt;
            color: #333; /* Darker footer text for print */
        }
        .footer .signature b {
             border-top: 1px solid #333;
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
            {{-- For PDF generation, public_path is fine. For web, asset() is better. --}}
            {{-- Assuming this is for PDF, so public_path is kept. --}}
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
                    <th width="30%" class="text-left">Nama Barang</th> {{-- Added text-left class --}}
                    <th width="10%">Satuan</th>
                    <th width="13%" class="text-right">Stok Awal</th> {{-- Added text-right class --}}
                    <th width="14%" class="text-right">Total Masuk</th> {{-- Added text-right class --}}
                    <th width="14%" class="text-right">Total Keluar</th> {{-- Added text-right class --}}
                    <th width="14%" class="text-right">Saldo Akhir</th> {{-- Added text-right class --}}
                </tr>
            </thead>
            <tbody>
                @forelse($laporan_stok as $index => $d) {{-- Changed to forelse for empty case --}}
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $d->nama }}</td> {{-- Added text-left class --}}
                    <td>{{ $d->nama_satuan }}</td>
                    <td class="text-right">{{ number_format($d->stok_awal, 0, ',', '.') }}</td> {{-- Added text-right class --}}
                    <td class="text-right">{{ number_format($d->masuk, 0, ',', '.') }}</td> {{-- Added text-right class --}}
                    <td class="text-right">{{ number_format($d->keluar, 0, ',', '.') }}</td> {{-- Added text-right class --}}
                    <td class="text-right">{{ number_format($d->saldoakhir, 0, ',', '.') }}</td> {{-- Added text-right class --}}
                </tr>
                @empty {{-- Handles case where $laporan_stok is empty --}}
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px;">Tidak ada data yang tersedia untuk periode ini.</td>
                </tr>
                @endforelse
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
                <span>{{ number_format($laporan_stok->sum('masuk'), 0, ',', '.') }} unit</span>
            </div>
            <div class="summary-item">
                <span>Total Barang Keluar</span>
                <span>{{ number_format($laporan_stok->sum('keluar'), 0, ',', '.') }} unit</span>
            </div>
            <div class="summary-item">
                <span>Total Saldo Akhir</span>
                <span>{{ number_format($laporan_stok->sum('saldoakhir'), 0, ',', '.') }} unit</span>
            </div>
        </div> --}}

        <div class="footer">
            <div class="company-info">
                <p><strong>Seroo</strong><br>
                Dicetak pada: {{ date('d F Y, H:i') }}</p>
            </div>
            <div class="signature">
                <p>Mengetahui,</p>
                <b>Manajer Gudang</b>
            </div>
        </div>
    </div>
</body>
</html>
