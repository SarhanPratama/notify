<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RekapTransaksiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $kategori;

    public function __construct($tanggal_awal, $tanggal_akhir, $kategori = 'all')
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->kategori = $kategori;
    }

    public function collection()
    {
        $query = Transaksi::with('kategoriKeuangan')
            ->whereBetween('tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
            ->where('status', 1)
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc');

        if ($this->kategori && $this->kategori !== 'all') {
            $query->where('id_kategori_keuangan', $this->kategori);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Hari',
            'Tipe',
            'Kategori',
            'Keterangan',
            'Posisi Kas',
            'Nominal',
        ];
    }

    public function map($trx): array
    {
        $tanggal = $trx->tanggal ? Carbon::parse($trx->tanggal)->format('d/m/Y') : '';
        $hari = $trx->tanggal ? Carbon::parse($trx->tanggal)->translatedFormat('l') : '';
        $kategori = $trx->kategoriKeuangan->nama ?? '-';
        $tipe = $trx->tipe ?? ($trx->kategoriKeuangan->jenis ?? '-');

        return [
            '', // No will be filled by Excel consumer (we keep blank here)
            $tanggal,
            $hari,
            $trx->kategoriKeuangan->jenis ?? '-',
            $kategori,
            $trx->deskripsi,
            $trx->posisi_kas,
            $trx->jumlah,
        ];
    }
}
