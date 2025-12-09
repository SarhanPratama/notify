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
                -- Total Pemasukan (hanya transaksi aktif dan belum dihapus)
                COALESCE(SUM(CASE
                    WHEN tipe = 'debit' AND status = 1 AND deleted_at IS NULL
                    THEN jumlah
                    ELSE 0
                END), 0) AS total_pemasukan,

                -- Total Pengeluaran (hanya transaksi aktif dan belum dihapus)
                COALESCE(SUM(CASE
                    WHEN tipe = 'kredit' AND status = 1 AND deleted_at IS NULL
                    THEN jumlah
                    ELSE 0
                END), 0) AS total_pengeluaran,

                -- Saldo Akhir (Current Saldo)
                (COALESCE(SUM(CASE
                    WHEN tipe = 'debit' AND status = 1 AND deleted_at IS NULL
                    THEN jumlah
                    ELSE 0
                END), 0)
                - COALESCE(SUM(CASE
                    WHEN tipe = 'kredit' AND status = 1 AND deleted_at IS NULL
                    THEN jumlah
                    ELSE 0
                END), 0)) AS saldo_current

            FROM transaksi
            WHERE deleted_at IS NULL AND status = 1;
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
