<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Kriteria;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Pemilik Toko',
            'email' => 'admin@toko.com',
            'password' => bcrypt('password123'),
        ]);

        // 2. Buat Kriteria Dasar
        $kriterias = [
            ['nama_kriteria' => 'Sisa Stok', 'sifat' => 'cost'],
            ['nama_kriteria' => 'Total Penjualan', 'sifat' => 'benefit'],
            ['nama_kriteria' => 'Margin', 'sifat' => 'benefit'],
            ['nama_kriteria' => 'Harga Modal', 'sifat' => 'cost'],
            ['nama_kriteria' => 'Lama Pengiriman', 'sifat' => 'benefit'],
        ];

        foreach ($kriterias as $k) {
            Kriteria::create($k);
        }

        // 3. Buat Kategori & Bobot Dinamis
        $dataKategori = [
            'Sembako' => [1 => 40, 2 => 20, 3 => 10, 4 => 10, 5 => 20],
            'Rokok'   => [1 => 15, 2 => 35, 3 => 10, 4 => 35, 5 => 5],
            'Minuman' => [1 => 15, 2 => 35, 3 => 35, 4 => 10, 5 => 5],
        ];

        foreach ($dataKategori as $nama => $bobots) {
            $kat = Kategori::create(['nama_kategori' => $nama]);
            foreach ($bobots as $kriteriaId => $nilaiBobot) {
                DB::table('kategori_kriteria')->insert([
                    'kategori_id' => $kat->id,
                    'kriteria_id' => $kriteriaId,
                    'bobot' => $nilaiBobot,
                ]);
            }
        }
        $this->call([
            ProdukTransaksiSeeder::class
        ]);
    }
}