<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW view_saldo_dana AS
            SELECT
                sumber_dana.id AS id_sumber_dana,
                sumber_dana.nama,
                sumber_dana.saldo_awal,

                -- Total Pemasukan
                COALESCE(SUM(CASE
                    WHEN transaksi.tipe = 'debit'
                    THEN transaksi.jumlah
                    ELSE 0
                END), 0) AS total_pemasukan,

                -- Total Pengeluaran
                COALESCE(SUM(CASE
                    WHEN transaksi.tipe = 'kredit'
                    THEN transaksi.jumlah
                    ELSE 0
                END), 0) AS total_pengeluaran,

                -- Saldo Akhir (Current Saldo)
                (sumber_dana.saldo_awal
                + COALESCE(SUM(CASE
                    WHEN transaksi.tipe = 'debit'
                    THEN transaksi.jumlah
                    ELSE 0
                END), 0)
                - COALESCE(SUM(CASE
                    WHEN transaksi.tipe = 'kredit'
                    THEN transaksi.jumlah
                    ELSE 0
                END), 0)) AS saldo_current

            FROM
                sumber_dana AS sumber_dana
            LEFT JOIN
                transaksi AS transaksi ON transaksi.id_sumber_dana = sumber_dana.id
            GROUP BY
                sumber_dana.id, sumber_dana.nama, sumber_dana.saldo_awal;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_saldo_dana;");
    }
};
