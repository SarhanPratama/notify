<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StokExport implements FromCollection, WithHeadings
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan = null, $tahun = null)
    {
        $this->bulan = $bulan ?? now()->month;
        $this->tahun = $tahun ?? now()->year;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('bahan_baku')
            ->leftJoin('mutasi', function($join) {
                $join->on('bahan_baku.id', '=', 'mutasi.id_bahan_baku')
                     ->whereYear('mutasi.created_at', $this->tahun)
                     ->whereMonth('mutasi.created_at', $this->bulan);
            })
            ->leftJoin('satuan', 'bahan_baku.id_satuan', '=', 'satuan.id')
            ->select(
                'bahan_baku.nama',
                'bahan_baku.stok_awal',
                DB::raw('COALESCE(SUM(CASE WHEN mutasi.jenis_transaksi = "M" THEN mutasi.quantity ELSE 0 END), 0) as total_masuk'),
                DB::raw('COALESCE(SUM(CASE WHEN mutasi.jenis_transaksi = "K" THEN mutasi.quantity ELSE 0 END), 0) as total_keluar'),
                DB::raw('bahan_baku.stok_awal + COALESCE(SUM(CASE WHEN mutasi.jenis_transaksi = "M" THEN mutasi.quantity ELSE 0 END), 0) - COALESCE(SUM(CASE WHEN mutasi.jenis_transaksi = "K" THEN mutasi.quantity ELSE 0 END), 0) as stok_akhir'),
                'satuan.nama as nama_satuan'
            )
            ->groupBy('bahan_baku.id', 'bahan_baku.nama', 'bahan_baku.stok_awal', 'satuan.nama')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama Barang',
            'Stok Awal',
            'Total Masuk',
            'Total Keluar',
            'Saldo Akhir',
            'Satuan',
        ];
    }
}
