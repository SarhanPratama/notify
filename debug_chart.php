<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transaksi;
use Carbon\Carbon;

$startDate = Carbon::now()->subDays(6)->startOfDay();
$endDate = Carbon::now()->endOfDay();

echo "Start: $startDate, End: $endDate\n";

$transaksi = Transaksi::with('kategoriKeuangan')
    ->whereBetween('tanggal', [$startDate, $endDate])
    ->where('status', 1)
    ->get();

echo "Total transaksi: " . $transaksi->count() . "\n";

$grouped = $transaksi->groupBy(function ($item) {
    return Carbon::parse($item->tanggal)->format('Y-m-d');
});

$labels = [];
$pemasukanData = [];
$pengeluaranData = [];

$currentDate = $startDate->copy();
while ($currentDate->lte($endDate)) {
    $dateString = $currentDate->format('Y-m-d');
    $labels[] = $currentDate->format('d/m');

    $transaksiHarian = $grouped->get($dateString, collect());

    $pemasukan = $transaksiHarian->filter(function ($item) {
        return $item->kategoriKeuangan && $item->kategoriKeuangan->jenis === 'pemasukan';
    })->sum('jumlah');

    $pengeluaran = $transaksiHarian->filter(function ($item) {
        return $item->kategoriKeuangan && $item->kategoriKeuangan->jenis === 'pengeluaran';
    })->sum('jumlah');

    $pemasukanData[] = (int) $pemasukan;
    $pengeluaranData[] = (int) $pengeluaran;

    echo "Date: $dateString, Pemasukan: $pemasukan, Pengeluaran: $pengeluaran\n";

    $currentDate->addDay();
}

echo "\nLabels: " . implode(', ', $labels) . "\n";
echo "Pemasukan: " . implode(', ', $pemasukanData) . "\n";
echo "Pengeluaran: " . implode(', ', $pengeluaranData) . "\n";
