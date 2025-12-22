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
            $table->foreignId('id_kategori_keuangan')->nullable()->constrained('kategori_keuangan')->onDelete('set null');
            $table->string('nobukti');
            $table->date('tanggal');
            // $table->enum('tipe', ['debit', 'kredit']);
            $table->decimal('jumlah', 15, 0);
            $table->text('deskripsi');
            $table->tinyInteger('status')->default(0);
             $table->enum('posisi_kas', ['Tunai', 'Bank BSI'])->nullable();
            $table->softDeletes();
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
