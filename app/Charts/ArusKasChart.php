<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class ArusKasChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build()
    {
        // Ambil tanggal awal & akhir bulan ini
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        // Ambil semua transaksi dalam 1 bulan (1 query saja)
        $transaksi = \App\Models\Transaksi::whereBetween('tanggal', [$startDate, $endDate])
            ->where('status', 1)
            ->get(['tanggal', 'tipe', 'jumlah']);

        // Kelompokkan transaksi per tanggal
        $grouped = $transaksi->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d');
        });

        $labels = [];
        $pemasukanData = [];
        $pengeluaranData = [];

        // Iterasi setiap tanggal dalam bulan
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('d/m');

            // Ambil transaksi di tanggal tersebut (kalau ada)
            $transaksiHarian = $grouped->get($dateString, collect());

            // Hitung total pemasukan & pengeluaran
            $pemasukan = $transaksiHarian->where('tipe', 'debit')->sum('jumlah');
            $pengeluaran = $transaksiHarian->where('tipe', 'kredit')->sum('jumlah');

            $pemasukanData[] = (int) $pemasukan;
            $pengeluaranData[] = (int) $pengeluaran;

            $currentDate->addDay();
        }

        // Return chart
        return $this->chart->barChart()
            ->setTitle('Pemasukan vs Pengeluaran Harian')
            ->setSubtitle('Perbandingan pemasukan dan pengeluaran per hari dalam 1 bulan (' . now()->format('F Y') . ')')
            ->setDataset([
                [
                    'name' => 'Pemasukan',
                    'data' => $pemasukanData
                ],
                [
                    'name' => 'Pengeluaran',
                    'data' => $pengeluaranData
                ]
            ])
            ->setXAxis($labels)
            ->setColors(['#1cc88a', '#e74a3b'])
            ->setHeight(400);
    }
}
