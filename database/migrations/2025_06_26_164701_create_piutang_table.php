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
        Schema::create('piutang', function (Blueprint $table) {
            $table->id();
            $table->string('nobukti')->unique();
            $table->decimal('jumlah_piutang', 15, 2);
            $table->date('jatuh_tempo');
            $table->enum('status', ['belum_lunas', 'lunas'])->default('belum_lunas');
            $table->timestamps();
            $table->foreign('nobukti')->references('nobukti')->on('penjualan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piutang');
    }
};
