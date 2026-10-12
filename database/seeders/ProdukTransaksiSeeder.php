<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Transaksi;
use Carbon\Carbon;

class ProdukTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $dataProduk = [
            // --- KATEGORI 1: SEMBAKO (ID: 1) ---
            ['kategori_id' => 1, 'nama_produk' => 'Beras Premium 5kg', 'stok_saat_ini' => 5, 'harga_modal' => 63000, 'harga_jual' => 65000, 'lama_pengiriman_hari' => 3],
            ['kategori_id' => 1, 'nama_produk' => 'Minyak Goreng Bimoli 2L', 'stok_saat_ini' => 25, 'harga_modal' => 32000, 'harga_jual' => 35000, 'lama_pengiriman_hari' => 1],
            ['kategori_id' => 1, 'nama_produk' => 'Telur Ayam 1kg', 'stok_saat_ini' => 10, 'harga_modal' => 26000, 'harga_jual' => 29000, 'lama_pengiriman_hari' => 1],
            ['kategori_id' => 1, 'nama_produk' => 'Gula Pasir Gulaku 1kg', 'stok_saat_ini' => 30, 'harga_modal' => 15000, 'harga_jual' => 17000, 'lama_pengiriman_hari' => 2],
            ['kategori_id' => 1, 'nama_produk' => 'Indomie Goreng (Dus)', 'stok_saat_ini' => 8, 'harga_modal' => 110000, 'harga_jual' => 115000, 'lama_pengiriman_hari' => 1],
            ['kategori_id' => 1, 'nama_produk' => 'Indomie Ayam Bawang (Dus)', 'stok_saat_ini' => 15, 'harga_modal' => 105000, 'harga_jual' => 110000, 'lama_pengiriman_hari' => 1],
            ['kategori_id' => 1, 'nama_produk' => 'Tepung Segitiga Biru 1kg', 'stok_saat_ini' => 20, 'harga_modal' => 11000, 'harga_jual' => 13000, 'lama_pengiriman_hari' => 2],
            ['kategori_id' => 1, 'nama_produk' => 'Susu Kental Manis Frisian Flag', 'stok_saat_ini' => 40, 'harga_modal' => 14000, 'harga_jual' => 16500, 'lama_pengiriman_hari' => 1],
            ['kategori_id' => 1, 'nama_produk' => 'Garam Dapur Halus', 'stok_saat_ini' => 50, 'harga_modal' => 2000, 'harga_jual' => 3500, 'lama_pengiriman_hari' => 2],
            ['kategori_id' => 1, 'nama_produk' => 'Kopi Kapal Api Mix', 'stok_saat_ini' => 12, 'harga_modal' => 12000, 'harga_jual' => 15000, 'lama_pengiriman_hari' => 1],

            // --- KATEGORI 2: ROKOK (ID: 2) ---
            ['kategori_id' => 2, 'nama_produk' => 'Surya 12', 'stok_saat_ini' => 8, 'harga_modal' => 22000, 'harga_jual' => 24000, 'lama_pengiriman_hari' => 0],
            ['kategori_id' => 2, 'nama_produk' => 'Sampoerna Mild', 'stok_saat_ini' => 50, 'harga_modal' => 28000, 'harga_jual' => 31000, 'lama_pengiriman_hari' => 0],
            ['kategori_id' => 2, 'nama_produk' => 'Gudang Garam Filter', 'stok_saat_ini' => 15, 'harga_modal' => 22500, 'harga_jual' => 24000, 'lama_pengiriman_hari' => 0],
            ['kategori_id' => 2, 'nama_produk' => 'Djarum Super 12', 'stok_saat_ini' => 5, 'harga_modal' => 21000, 'harga_jual' => 23000, 'lama_pengiriman_hari' => 0],
            ['kategori_id' => 2, 'nama_produk' => 'Marlboro Merah', 'stok_saat_ini' => 20, 'harga_modal' => 35000, 'harga_jual' => 38000, 'lama_pengiriman_hari' => 1],
            ['kategori_id' => 2, 'nama_produk' => 'LA Lights', 'stok_saat_ini' => 35, 'harga_modal' => 25000, 'harga_jual' => 27500, 'lama_pengiriman_hari' => 0],
            ['kategori_id' => 2, 'nama_produk' => 'Dji Sam Soe 234', 'stok_saat_ini' => 10, 'harga_modal' => 19000, 'harga_jual' => 21000, 'lama_pengiriman_hari' => 1],

            // --- KATEGORI 3: MINUMAN (ID: 3) ---
            ['kategori_id' => 3, 'nama_produk' => 'Teh Pucuk Harum 350ml', 'stok_saat_ini' => 12, 'harga_modal' => 2500, 'harga_jual' => 3500, 'lama_pengiriman_hari' => 1],
            ['kategori_id' => 3, 'nama_produk' => 'Aqua Botol 600ml', 'stok_saat_ini' => 48, 'harga_modal' => 2000, 'harga_jual' => 3000, 'lama_pengiriman_hari' => 1],
            ['kategori_id' => 3, 'nama_produk' => 'Le Minerale 600ml', 'stok_saat_ini' => 60, 'harga_modal' => 2100, 'harga_jual' => 3500, 'lama_pengiriman_hari' => 1],
            ['kategori_id' => 3, 'nama_produk' => 'Kopi Kenangan Botol', 'stok_saat_ini' => 4, 'harga_modal' => 7000, 'harga_jual' => 9500, 'lama_pengiriman_hari' => 2],
            ['kategori_id' => 3, 'nama_produk' => 'Floridina Orange', 'stok_saat_ini' => 18, 'harga_modal' => 2500, 'harga_jual' => 3500, 'lama_pengiriman_hari' => 1],
            ['kategori_id' => 3, 'nama_produk' => 'Pocari Sweat 500ml', 'stok_saat_ini' => 25, 'harga_modal' => 6000, 'harga_jual' => 7500, 'lama_pengiriman_hari' => 2],
            ['kategori_id' => 3, 'nama_produk' => 'Sprite Botol 390ml', 'stok_saat_ini' => 10, 'harga_modal' => 4500, 'harga_jual' => 5500, 'lama_pengiriman_hari' => 1],
            ['kategori_id' => 3, 'nama_produk' => 'Susu Bear Brand', 'stok_saat_ini' => 6, 'harga_modal' => 9500, 'harga_jual' => 11000, 'lama_pengiriman_hari' => 2],
        ];

        $insertedProduks = [];
        foreach ($dataProduk as $item) {
            $insertedProduks[] = Produk::create($item);
        }

        // Generate 1000 Transaksi untuk 6 Bulan Terakhir
        $jumlahTransaksiSistem = 1000; 
        
        $jumlahStruk = 300; 

for ($i = 0; $i < $jumlahStruk; $i++) {
    $randomDate = Carbon::now()->subDays(rand(0, 180));
    
    // Bikin Struk Dulu
    $penjualan = \App\Models\Penjualan::create([
        'no_struk' => 'INV-DUMMY-' . rand(1000, 9999) . $i,
        'total_harga' => 0, // Akan diupdate di bawah
        'tanggal_transaksi' => $randomDate->format('Y-m-d')
    ]);

    $totalHargaStruk = 0;
    $jumlahMacamBarang = rand(1, 4); // 1 Pelanggan beli 1-4 macam barang berbeda

    // Masukkan barang ke dalam struk
    for ($j = 0; $j < $jumlahMacamBarang; $j++) {
        $randomProduk = $insertedProduks[array_rand($insertedProduks)];
        $jumlahBeli = rand(1, 3);
        $subtotal = $randomProduk->harga_jual * $jumlahBeli;

        \App\Models\Transaksi::create([
            'penjualan_id' => $penjualan->id,
            'produk_id' => $randomProduk->id,
            'jumlah_terjual' => $jumlahBeli,
            'subtotal_harga' => $subtotal,
            'tanggal_transaksi' => $randomDate->format('Y-m-d')
        ]);

        $totalHargaStruk += $subtotal;
    }

    // Update total harga di struk
    $penjualan->update(['total_harga' => $totalHargaStruk]);
}
    
    }
}