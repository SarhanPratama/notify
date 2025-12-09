<?php

namespace App\Exports;

use App\Models\mutasi;
use App\Models\BahanBaku;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KartuStokExport implements FromCollection, WithHeadings
{
    protected $id_bahan_baku;

    public function __construct($id_bahan_baku)
    {
        $this->id_bahan_baku = $id_bahan_baku;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $bahanBaku = BahanBaku::with('ViewStok')->findOrFail($this->id_bahan_baku);
        $riwayat_mutasi = mutasi::with(['transaksi', 'bahanBaku.satuan'])
            ->where('id_bahan_baku', $this->id_bahan_baku)
            ->where('status', 1)
            ->orderBy('created_at')
            ->get();

        $saldo_berjalan = $bahanBaku->stok_awal;
        $data = [];

        // Tambahkan saldo awal
        $data[] = [
            'No' => '-',
            'Tanggal' => '-',
            'No. Bukti' => '-',
            'Keterangan' => 'SALDO AWAL',
            'Masuk' => '-',
            'Keluar' => '-',
            'Saldo' => $saldo_berjalan,
        ];

        foreach ($riwayat_mutasi as $mutasi) {
            $masuk = 0;
            $keluar = 0;
            $keterangan = 'N/A';

            if ($mutasi->jenis_transaksi == 'M') {
                $masuk = $mutasi->quantity;
                $keterangan = 'Pembelian Stok';
            } elseif ($mutasi->jenis_transaksi == 'K') {
                $keluar = $mutasi->quantity;
                $keterangan = 'Distribusi ke Outlet';
            }

            $saldo_berjalan = $saldo_berjalan + $masuk - $keluar;

            $no_bukti = $mutasi->transaksi ? $mutasi->transaksi->first()->nobukti : 'N/A';

            $data[] = [
                'No' => count($data),
                'Tanggal' => $mutasi->transaksi ? $mutasi->transaksi->first()->created_at->format('d M Y H:i') : '-',
                'No. Bukti' => $no_bukti,
                'Keterangan' => $keterangan,
                'Masuk' => $masuk > 0 ? $masuk : '-',
                'Keluar' => $keluar > 0 ? $keluar : '-',
                'Saldo' => $saldo_berjalan,
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'No. Bukti',
            'Keterangan',
            'Masuk',
            'Keluar',
            'Saldo',
        ];
    }
}
