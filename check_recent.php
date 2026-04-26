<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transaksi;
use Carbon\Carbon;

$start = Carbon::now()->subDays(6)->startOfDay();
$end = Carbon::now()->endOfDay();

echo "Transaksi dari $start sampai $end:\n";

$transaksi = Transaksi::with('kategoriKeuangan')
    ->whereBetween('tanggal', [$start, $end])
    ->where('status', 1)
    ->get();

foreach($transaksi as $t) {
    echo "- {$t->tanggal}: {$t->kategoriKeuangan->nama} ({$t->kategoriKeuangan->jenis}) - Rp " . number_format($t->jumlah) . "\n";
}

$pemasukan = $transaksi->filter(fn($t) => $t->kategoriKeuangan->jenis === 'pemasukan')->sum('jumlah');
$pengeluaran = $transaksi->filter(fn($t) => $t->kategoriKeuangan->jenis === 'pengeluaran')->sum('jumlah');

echo "\nTotal pemasukan: Rp " . number_format($pemasukan) . "\n";
echo "Total pengeluaran: Rp " . number_format($pengeluaran) . "\n";
