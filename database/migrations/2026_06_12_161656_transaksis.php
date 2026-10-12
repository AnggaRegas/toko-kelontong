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
    Schema::create('transaksis', function (Blueprint $table) {
        $table->id();
        // Mengikat ke tabel struk/penjualan
        $table->foreignId('penjualan_id')->constrained()->cascadeOnDelete();
        $table->foreignId('produk_id')->constrained()->cascadeOnDelete();
        $table->integer('jumlah_terjual');
        $table->integer('subtotal_harga'); // Tambahan: harga barang x jumlah
        $table->date('tanggal_transaksi'); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
