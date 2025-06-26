<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW view_stok AS
            SELECT
                bahan_baku.id as id_bahan_baku,
                bahan_baku.nama,
                bahan_baku.stok_awal,

                -- Total Masuk
                COALESCE(SUM(CASE WHEN mutasi.jenis_transaksi = 'M' THEN mutasi.quantity ELSE 0 END), 0) AS total_masuk,

                -- Total Keluar
                COALESCE(SUM(CASE WHEN mutasi.jenis_transaksi = 'K' THEN mutasi.quantity ELSE 0 END), 0) AS total_keluar,

                -- Saldo Akhir
                bahan_baku.stok_awal +
                COALESCE(SUM(CASE WHEN mutasi.jenis_transaksi = 'M' THEN mutasi.quantity ELSE 0 END), 0) -
                COALESCE(SUM(CASE WHEN mutasi.jenis_transaksi = 'K' THEN mutasi.quantity ELSE 0 END), 0) AS saldo_akhir,

                satuan.nama AS nama_satuan

            FROM
                bahan_baku
            LEFT JOIN mutasi ON mutasi.id_bahan_baku = bahan_baku.id
            LEFT JOIN satuan ON bahan_baku.id_satuan = satuan.id

            GROUP BY
                bahan_baku.id, bahan_baku.nama, bahan_baku.stok_awal, satuan.nama;

        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('view_stok');
    }
};
