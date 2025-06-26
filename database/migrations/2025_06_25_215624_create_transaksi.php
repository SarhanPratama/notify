<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sumber_dana')->constrained('sumber_dana')->onDelete('cascade');
            $table->nullableMorphs('nobukti');
            $table->date('tanggal');
            $table->enum('tipe', ['debit', 'credit']);
            $table->decimal('jumlah', 15, 2);
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
