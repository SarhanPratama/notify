<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id();
            $table->morphs('mutasiable');
            // $table->string('nobukti');
            $table->foreignId('id_bahan_baku')->constrained('bahan_baku')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('quantity');
            $table->decimal('harga', 15, 2);
            $table->decimal('sub_total', 15, 2);
            $table->enum('jenis_transaksi', ['M', 'K']);
            $table->boolean('status');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi');
    }
};

