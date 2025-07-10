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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('nobukti')->unique();
            $table->date('tanggal');
            $table->decimal('total', 15, 2);
            // $table->enum('status', ['lunas', 'belum_lunas', 'piutang',])->default('lunas');
            $table->enum('metode_pembayaran', ['tunai', 'kasbon'])->default('tunai');
            $table->text('catatan')->nullable();
            $table->foreignId('id_cabang')->constrained('cabang')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
