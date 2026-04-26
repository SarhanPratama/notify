<?php

namespace Database\Seeders;

use App\Models\Satuan;
use App\Models\Kategori;
use App\Models\BahanBaku;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada kategori default untuk Bahan Baku & Packaging
        $kategoriBahanBaku = Kategori::firstOrCreate(
            ['nama' => 'Bahan Baku']
        );
        $kategoriPackaging = Kategori::firstOrCreate(
            ['nama' => 'Packaging']
        );

        $daftarBarang = [
            ['nama' => 'Bubuk Teh Hitam (Seroo)', 'satuan' => 'Kg', 'harga_jual' => 50000, 'kategori' => $kategoriBahanBaku->id],
            ['nama' => 'Susu Kental Manis (SKM)', 'satuan' => 'Kaleng (500g)', 'harga_jual' => 13500, 'kategori' => $kategoriBahanBaku->id],
            ['nama' => 'Susu Evaporasi', 'satuan' => 'Kaleng (380g)', 'harga_jual' => 18000, 'kategori' => $kategoriBahanBaku->id],
            ['nama' => 'Milo Bubuk Professional', 'satuan' => 'Kg', 'harga_jual' => 100000, 'kategori' => $kategoriBahanBaku->id],
            ['nama' => 'Green Tea / Matcha Powder', 'satuan' => 'Kg', 'harga_jual' => 85000, 'kategori' => $kategoriBahanBaku->id],
            ['nama' => 'Kopi Robusta Bubuk', 'satuan' => 'Kg', 'harga_jual' => 68000, 'kategori' => $kategoriBahanBaku->id],
            ['nama' => 'Gula Cair (Simple Syrup)', 'satuan' => 'Jerigen (5 Liter)', 'harga_jual' => 55000, 'kategori' => $kategoriBahanBaku->id],
            ['nama' => 'Krimer Bubuk (Non-Dairy)', 'satuan' => 'Kg', 'harga_jual' => 35000, 'kategori' => $kategoriBahanBaku->id],
            ['nama' => 'Gelas Plastik (Cup 22 oz)', 'satuan' => 'Roll (50 pcs)', 'harga_jual' => 23000, 'kategori' => $kategoriPackaging->id],
            ['nama' => 'Plastik Press (Cup Sealer)', 'satuan' => 'Roll (1200 cup)', 'harga_jual' => 95000, 'kategori' => $kategoriPackaging->id],
            ['nama' => 'Sedotan Plastik (Steril)', 'satuan' => 'Pack (500 pcs)', 'harga_jual' => 18000, 'kategori' => $kategoriPackaging->id],
            ['nama' => 'Kantong Plastik Takeaway', 'satuan' => 'Pack (100 pcs)', 'harga_jual' => 14000, 'kategori' => $kategoriPackaging->id],
        ];

        foreach ($daftarBarang as $barang) {
            // Cek atau buat Satuan
            $satuan = Satuan::firstOrCreate(
                ['nama' => $barang['satuan']]
            );

            // Cek atau update Bahan Baku
            BahanBaku::updateOrCreate(
                ['nama' => $barang['nama']],
                [
                    'id_satuan' => $satuan->id,
                    'id_kategori' => $barang['kategori'],
                    'harga' => $barang['harga_jual'],
                    'stok_awal' => 100, // default
                    'stok_minimum' => 10 // default
                ]
            );
        }
    }
}
