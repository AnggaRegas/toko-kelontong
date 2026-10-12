<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Produk::count();

        $totalKategori = Kategori::count();

        $stokKritis = Produk::where('stok_saat_ini', '<=', 10)->count();

        $omsetHariIni = Transaksi::whereDate(
            'tanggal_transaksi',
            Carbon::today()
        )->sum('subtotal_harga');

        return view('dashboard.home', compact(
            'totalProduk',
            'totalKategori',
            'stokKritis',
            'omsetHariIni'
        ));
    }
}