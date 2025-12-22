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
            $table->decimal('total', 15, 0);
            $table->enum('status_gudang', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status_keuangan', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('status_pembayaran', ['lunas', 'piutang'])->default('piutang');
            $table->text('catatan')->nullable();
            $table->foreignId('id_outlet')->constrained('outlet')->onDelete('restrict')->onUpdate('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
