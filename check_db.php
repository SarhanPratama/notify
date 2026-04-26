<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transaksi;
use App\Models\KategoriKeuangan;

echo "Total transaksi: " . Transaksi::count() . PHP_EOL;
echo "Transaksi dalam 7 hari: " . Transaksi::whereBetween('tanggal', [now()->subDays(6), now()])->count() . PHP_EOL;

$kategori = KategoriKeuangan::all();
echo "Kategori Keuangan:\n";
foreach($kategori as $k) {
    echo "- {$k->nama}: {$k->jenis}\n";
}

$pemasukan = Transaksi::whereHas('kategoriKeuangan', fn($q) => $q->where('jenis', 'pemasukan'))->where('status', 1)->count();
$pengeluaran = Transaksi::whereHas('kategoriKeuangan', fn($q) => $q->where('jenis', 'pengeluaran'))->where('status', 1)->count();

echo "Transaksi pemasukan (status=1): $pemasukan\n";
echo "Transaksi pengeluaran (status=1): $pengeluaran\n";
