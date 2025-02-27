<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->integer('stok');
            $table->integer('harga_modal');
            $table->integer('harga_jual');
            $table->string('status');
            $table->text('deskripsi');
            $table->foreignId('id_merek')->constrained('merek')->onDelete('cascade');
            $table->string('foto');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
