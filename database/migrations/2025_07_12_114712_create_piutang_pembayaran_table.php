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
        Schema::create('piutang_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('nobukti');
            $table->foreignId('id_sumber_dana')->constrained('sumber_dana');
            $table->date('tanggal');
            $table->decimal('jumlah', 15, 0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('piutang_pembayaran');
    }
};
