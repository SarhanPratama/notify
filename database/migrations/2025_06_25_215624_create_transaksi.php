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
            $table->nullableMorphs('referenceable');
            $table->date('tanggal');
            $table->enum('tipe', ['debit', 'kredit']);
            $table->decimal('jumlah', 15, 2);
            $table->text('deskripsi');
            $table->tinyInteger('status')->default(1);
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
