<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')->latest()->get();
        return view('produk.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('produk.create', compact('kategoris'));
    }

    public function store(Request $request)
{
    // 1. Tampung hasil validasi ke dalam variabel (misal: $validatedData)
    $validatedData = $request->validate([
        'kategori_id'          => 'required|exists:kategoris,id',
        'nama_produk'          => 'required|string|max:255',
        'stok_saat_ini'        => 'required|integer|min:0',
        'harga_modal'          => 'required|integer|min:0',
        'harga_jual'           => 'required|integer|min:0',
        'lama_pengiriman_hari' => 'required|integer|min:0',
    ]);

    // 2. Gunakan variabel $validatedData tersebut untuk create, BUKAN $request->all()
    Produk::create($validatedData);

    return redirect()->route('produk.index')->with('success', 'Produk Berhasil Ditambahkan!');
}

    public function edit(Produk $produk)
    {
        $kategoris = Kategori::all();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_produk' => 'required|string|max:255',
            'stok_saat_ini' => 'required|integer|min:0',
            'harga_modal' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
            'lama_pengiriman_hari' => 'required|integer|min:0',
        ]);

        $produk->update($request->all());

        return redirect()->route('produk.index')->with('success', 'Produk Berhasil Diperbarui!');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk Berhasil Dihapus!');
    }
}