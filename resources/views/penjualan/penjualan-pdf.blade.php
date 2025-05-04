<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Bahan Baku</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        h2 {
            text-align: center;
        }

        .logo {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="logo">

        <img src="{{ public_path('assets/img/logo/icon2.png') }}" alt="Logo Perusahaan" width="100px">
    </div>
    <h2>Laporan Barang Keluar</h2>
    <p>{{ $periode }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>No. Bukti</th>
                <th>Cabang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td> {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y') }}
                    </td>
                    <td>{{ $data->nobukti }}</td>
                    <td>{{ $data->penjualan->cabang->nama }}</td>
                    <td>{{ $data->bahanBaku->nama }}</td>
                    <td>{{ $data->quantity }} {{ $data->satuan ?? '' }}</td>
                    <td>Rp {{ number_format($data->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($data->sub_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7" style="text-align: right;">Total Pengeluaran</th>
                <th>
                    Rp {{ number_format($laporan->sum('sub_total'), 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

</body>

</html>
