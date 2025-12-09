<?php

namespace App\Exports;

use App\Models\ViewStok;
use App\Models\VSaldoAkhir;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StokExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ViewStok::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Stok Awal',
            'Total Masuk',
            'Total Keluar',
            'Saldo Akhir',
            'Satuan',
        ];
    }
}
