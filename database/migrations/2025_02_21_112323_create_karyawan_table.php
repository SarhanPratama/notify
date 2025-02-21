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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->integer('usia');
            $table->date('tgl_lahir');
            $table->string('telepon');
            $table->text('alamat');
            $table->text('foto');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_roles')->constrained('roles')->onDelete('cascade');
            $table->foreignId('id_cabang')->constrained('cabang')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
