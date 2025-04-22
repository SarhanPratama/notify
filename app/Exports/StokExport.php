<?php

namespace App\Exports;

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
        return VSaldoAkhir::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Satuan',
            'Stok Awal',
            'Total Masuk',
            'Total Keluar',
            'Saldo Akhir'
        ];
    }
}
