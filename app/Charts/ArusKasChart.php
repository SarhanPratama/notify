<?php

namespace App\Charts;

use App\Models\Transaksi;
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
        // Ambil 7 hari terakhir (hari ini + 6 hari ke belakang)
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        // Ambil semua transaksi dalam 7 hari terakhir (1 query saja)
        $transaksi = Transaksi::with('kategoriKeuangan')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('status', 1)
            ->get();

        // Kelompokkan transaksi per tanggal
        $grouped = $transaksi->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d');
        });

        $labels = [];
        $pemasukanData = [];
        $pengeluaranData = [];

        // Iterasi setiap tanggal dalam 7 hari terakhir
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('d/m');

            // Ambil transaksi di tanggal tersebut (kalau ada)
            $transaksiHarian = $grouped->get($dateString, collect());

            // Hitung total pemasukan & pengeluaran
            $pemasukan = $transaksiHarian->filter(function ($item) {
                return $item->kategoriKeuangan && $item->kategoriKeuangan->jenis === 'pemasukan';
            })->sum('jumlah');

            $pengeluaran = $transaksiHarian->filter(function ($item) {
                return $item->kategoriKeuangan && $item->kategoriKeuangan->jenis === 'pengeluaran';
            })->sum('jumlah');

            $pemasukanData[] = (int) $pemasukan;
            $pengeluaranData[] = (int) $pengeluaran;

            $currentDate->addDay();
        }

        // Return chart
        return $this->chart->barChart()
            ->setTitle('Pemasukan vs Pengeluaran Harian')
            ->setSubtitle('Perbandingan pemasukan dan pengeluaran per hari dalam 7 hari terakhir')
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
