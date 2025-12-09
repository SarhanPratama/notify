<?php

namespace App\Exports;

use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BukuBesarExport implements FromCollection, WithHeadings
{
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $tipe_transaksi;
    protected $search;

    public function __construct($tanggal_awal, $tanggal_akhir, $tipe_transaksi, $search)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->tipe_transaksi = $tipe_transaksi;
        $this->search = $search;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Query transaksi dengan filter
        $query = Transaksi::
            whereBetween('tanggal', [$this->tanggal_awal, $this->tanggal_akhir])
            ->where('status', 1);

        if ($this->tipe_transaksi !== 'all') {
            $query->where('tipe', $this->tipe_transaksi);
        }

        if (!empty($this->search)) {
            $query->where('deskripsi', 'like', '%' . $this->search . '%');
        }

        $transaksi = $query->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung saldo awal
        $saldoSebelumnya = Transaksi::where('tanggal', '<', $this->tanggal_awal)
            ->where('status', 1)
            // ->when($this->sumber_dana !== 'all', function($q) {
            //     return $q->where('id_sumber_dana', $this->sumber_dana);
            // })
            ->sum(DB::raw('CASE WHEN tipe = "debit" THEN jumlah ELSE -jumlah END'));

        $data = [];

        // Tambahkan saldo awal
        $data[] = [
            'Tanggal' => '-',
            'Deskripsi' => '-',
            'Debit' => '-',
            'Kredit' => '-',
            'Saldo' => $saldoSebelumnya,
        ];

        $running_balance = $saldoSebelumnya;

        foreach ($transaksi as $trx) {
            $debit = $trx->tipe === 'debit' ? $trx->jumlah : 0;
            $kredit = $trx->tipe === 'kredit' ? $trx->jumlah : 0;
            $running_balance += $debit - $kredit;

            $data[] = [
                'Tanggal' => $trx->tanggal->format('d/m/Y'),
                'Deskripsi' => $trx->deskripsi,
                // 'Sumber Dana' => $trx->sumberDana ? $trx->sumberDana->nama : '-',
                'Debit' => $debit > 0 ? $debit : '-',
                'Kredit' => $kredit > 0 ? $kredit : '-',
                'Saldo' => $running_balance,
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Deskripsi',
            // 'Sumber Dana',
            'Debit',
            'Kredit',
            'Saldo',
        ];
    }
}
