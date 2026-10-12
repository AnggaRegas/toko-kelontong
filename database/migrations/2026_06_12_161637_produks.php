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
        Schema::create('produks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('kategori_id')->constrained()->cascadeOnDelete();
    $table->string('nama_produk');
    $table->integer('stok_saat_ini');
    $table->integer('harga_modal');
    $table->integer('harga_jual');
    $table->integer('lama_pengiriman_hari');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
