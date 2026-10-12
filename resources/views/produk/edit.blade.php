<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Produk | SmartRestock</title>
</head>
<body class="bg-slate-50 antialiased text-slate-800">

<main class="max-w-3xl mx-auto py-8 px-6">

    <div class="mb-6">
        <h2 class="text-3xl font-black text-slate-900">
            Edit Produk
        </h2>
        <p class="text-slate-500 mt-1">
            Perbarui data produk yang dipilih.
        </p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

        <form action="{{ route('produk.update', $produk->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">
                    Nama Produk
                </label>

                <input
                    type="text"
                    name="nama_produk"
                    value="{{ old('nama_produk', $produk->nama_produk) }}"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">
                    Kategori
                </label>

                <select
                    name="kategori_id"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3">

                    @foreach($kategoris as $kategori)
                        <option
                            value="{{ $kategori->id }}"
                            {{ $produk->kategori_id == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-bold mb-2">
                        Stok Saat Ini
                    </label>

                    <input
                        type="number"
                        name="stok_saat_ini"
                        value="{{ old('stok_saat_ini', $produk->stok_saat_ini) }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">
                        Lama Pengiriman (Hari)
                    </label>

                    <input
                        type="number"
                        name="lama_pengiriman_hari"
                        value="{{ old('lama_pengiriman_hari', $produk->lama_pengiriman_hari) }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3">
                </div>

            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">

                <div>
                    <label class="block text-sm font-bold mb-2">
                        Harga Modal
                    </label>

                    <input
                        type="number"
                        name="harga_modal"
                        value="{{ old('harga_modal', $produk->harga_modal) }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-2">
                        Harga Jual
                    </label>

                    <input
                        type="number"
                        name="harga_jual"
                        value="{{ old('harga_jual', $produk->harga_jual) }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3">
                </div>

            </div>

            <div class="flex gap-3 mt-8">

                <a href="{{ route('produk.index') }}"
                   class="bg-slate-500 hover:bg-slate-600 text-white px-5 py-3 rounded-xl font-bold">
                    Kembali
                </a>

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-bold">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</main>

</body>
</html>