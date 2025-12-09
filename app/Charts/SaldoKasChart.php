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
        // Ambil data saldo kas global
        $saldoData = ViewSaldo::first();

        $labels = ['Saldo Kas'];
        $data = [$saldoData->saldo_current ?? 0];

        return $this->chart->pieChart()
            ->setTitle('Saldo Kas')
            ->setSubtitle('Total saldo kas')
            ->addData($data)
            ->setLabels($labels)
            ->setHeight(413)
            ->setColors(['#4e73df']);
    }
}
