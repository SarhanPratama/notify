<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('cash_flow', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('keterangan');
            $table->decimal('nominal', 15, 2);
            $table->enum('jenis_transaksi', ['debit', 'kredit']);
            $table->enum('sumber_dana', ['kas seroo', 'kas rekening', 'dana peminjaman', 'piutang']);
            $table->text('keperluan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_flow');
    }
};
