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
            // $table->enum('status', ['pending', 'disetujui', 'success'])->default('pending');
            $table->decimal('total', 15, 2);
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
