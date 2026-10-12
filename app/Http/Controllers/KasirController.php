<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk Database Transaction

class KasirController extends Controller
{
    public function index()
    {
        $produks = Produk::where('stok_saat_ini', '>', 0)->orderBy('nama_produk')->get();
        $transaksisHariIni = Transaksi::with('produk')
            ->whereDate('tanggal_transaksi', Carbon::today())
            ->latest()
            ->get();

        return view('kasir.index', compact('produks', 'transaksisHariIni'));
    }

    public function store(Request $request)
{
    $request->validate([
        'keranjang' => 'required|array',
        'keranjang.*.id' => 'required|exists:produks,id',
        'keranjang.*.qty' => 'required|integer|min:1',
    ]);

    try {
        DB::transaction(function () use ($request) {
            $hariIni = Carbon::today()->format('Y-m-d');
            $totalHargaStruk = 0;
            $detailTransaksiArray = [];

            // 1. Looping pertama: Validasi stok dan hitung total harga struk
            foreach ($request->keranjang as $item) {
                $produk = Produk::find($item['id']);

                if ($produk->stok_saat_ini < $item['qty']) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi! Sisa: {$produk->stok_saat_ini}");
                }

                $subtotal = $produk->harga_jual * $item['qty'];
                $totalHargaStruk += $subtotal;

                $detailTransaksiArray[] = [
                    'produk' => $produk,
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal
                ];
            }

            // 2. Buat Header Transaksi (STRUK / NOTA)
            $penjualan = \App\Models\Penjualan::create([
                'no_struk' => 'INV-' . time(), // Contoh: INV-1718293812
                'total_harga' => $totalHargaStruk,
                'tanggal_transaksi' => $hariIni,
            ]);

            // 3. Looping kedua: Simpan detail barang & potong stok
            foreach ($detailTransaksiArray as $detail) {
                // Simpan ke detail transaksi yang terikat dengan ID Penjualan
                \App\Models\Transaksi::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $detail['produk']->id,
                    'jumlah_terjual' => $detail['qty'],
                    'subtotal_harga' => $detail['subtotal'],
                    'tanggal_transaksi' => $hariIni
                ]);

                // Potong stok master
                $detail['produk']->decrement('stok_saat_ini', $detail['qty']);
            }
        });

        return redirect()->route('kasir.index')->with('success', 'Pembayaran Berhasil! Struk tercetak.');
        
    } catch (\Exception $e) {
        return back()->withErrors(['kasir_error' => $e->getMessage()]);
    }
}
}