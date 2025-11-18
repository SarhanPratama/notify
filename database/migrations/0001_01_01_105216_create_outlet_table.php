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
        Schema::create('outlet', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->string('penanggung_jawab')->unique();
            $table->text('alamat');
            $table->string('telepon', 20);
            $table->text('lokasi');
            $table->string('foto')->nullable();
            $table->string('barcode_token')->unique()->nullable();
            $table->timestamp('barcode_generated_at')->nullable();
            $table->boolean('barcode_active')->default(false);
            // $table->foreignId('id_user')->constrained('users')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outlet');
    }
};
