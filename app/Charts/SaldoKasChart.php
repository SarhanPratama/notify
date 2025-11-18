<?php

namespace App\Charts;

use App\Models\ViewSaldo;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class SaldoKasChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        // Ambil data dari database
        $saldoData = ViewSaldo::select('nama', 'saldo_current')->get();

        $labels = [];
        $data = [];

        if ($saldoData->count() > 0) {
            foreach ($saldoData as $item) {
                $labels[] = $item->nama;
                $data[] = abs($item->saldo_current);
            }
        } else {
            $labels = ['Tidak ada data'];
            $data = [1];
        }

        return $this->chart->pieChart()
            ->setTitle('Komposisi Saldo Akhir')
            ->setSubtitle('Distribusi saldo kas per dompet')
            ->addData($data)
            ->setLabels($labels)
            ->setHeight(413)
            ->setColors([
                '#4e73df',
                '#1cc88a',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b',
                '#858796',
                '#6f42c1',
                '#20c997',
                '#fd7e14',
                '#e83e8c'
            ]);
    }
}
