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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('nobukti')->unique();
            $table->date('tanggal');
            $table->decimal('total', 15, 0);
            $table->enum('status', ['pending', 'approved', 'cancelled', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->foreignId('id_supplier')->nullable()->constrained('supplier')->onDelete('restrict')->onUpdate('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
