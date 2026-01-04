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
             $table->string('nobukti');
            $table->date('tanggal');
            $table->decimal('jumlah', 15, 0);
            $table->foreignId('id_kategori_keuangan')->constrained('kategori_keuangan')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('posisi_kas', ['Tunai', 'Bank BSI'])->nullable();
            $table->unsignedBigInteger('transaksiable_id')->nullable();
            $table->string('transaksiable_type')->nullable();
            $table->text('deskripsi')->nullable();
            $table->tinyInteger('status')->default(0);
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
