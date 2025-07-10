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
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('harga', 10, 2);
            $table->integer('stok_awal');
            $table->integer('stok_minimum');
            $table->string('foto')->nullable();
            $table->foreignId('id_kategori')->constrained('kategori')->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('id_satuan')->constrained('satuan')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};
