<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Tambah Produk | SmartRestock</title>
</head>
<body class="bg-slate-50 py-12 px-6">
    <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-900 p-6 text-white">
            <h2 class="text-xl font-bold">➕ Formulir Tambah Produk Baru</h2>
        </div>
        <form action="{{ route('produk.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Produk</label>
                <select name="kategori_id" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Barang</label>
                <input type="text" name="nama_produk" placeholder="Contoh: Kecap Bango 550ml" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Stok Awal</label>
                    <input type="number" name="stok_saat_ini" placeholder="0" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Lama Kirim Agen (Hari)</label>
                    <input type="number" name="lama_pengiriman_hari" placeholder="1" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Harga Modal (Rp)</label>
                    <input type="number" name="harga_modal" placeholder="1000" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Harga Jual Kasir (Rp)</label>
                    <input type="number" name="harga_jual" placeholder="1500" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex gap-4 pt-4 border-t border-slate-100">
                <a href="{{ route('produk.index') }}" class="w-1/2 bg-slate-100 hover:bg-slate-200 text-center text-slate-700 font-bold py-3 rounded-xl transition-all">Batal</a>
                <button type="submit" class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all shadow-md">Simpan Data</button>
            </div>
        </form>
    </div>
</body>
</html>