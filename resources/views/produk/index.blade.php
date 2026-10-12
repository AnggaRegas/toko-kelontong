<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Kelola Produk | SmartRestock</title>
</head>
<div class="flex h-screen w-full overflow-hidden">
    
    <aside class="relative w-64 shrink-0 bg-slate-800 border-r border-slate-700 shadow-xl flex flex-col h-full">
        <div class="p-6 border-b border-slate-700 shrink-0 text-center">
            <h1 class="text-2xl font-bold text-blue-400">Toko 2 Putra</h1>
        </div>

        <div class="p-4 space-y-2 overflow-y-auto flex-1">
            <a href="{{ route('dashboard.home') }}" class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('dashboard.home') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white' }}">📈 Dashboard</a>
            <a href="{{ route('dashboard.index') }}" class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('dashboard.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white' }}">📊 Analisis SMART</a>
            <a href="{{ route('produk.index') }}" class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('produk.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white' }}">📦 Kelola Produk</a>
            <a href="{{ route('kasir.index') }}" class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('kasir.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white' }}">🛒 Sistem Kasir</a>
        </div>

        <div class="mt-auto p-4 border-t border-slate-700 shrink-0">
            <div class="text-center text-sm font-bold text-slate-400 mb-3">👤 {{ auth()->user()->name ?? 'Admin' }}</div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-slate-700 hover:bg-red-600 text-slate-300 hover:text-white font-bold py-2.5 rounded-lg transition-all">Keluar</button>
            </form>
        </div>
    </aside>
<body class="bg-slate-50 antialiased text-slate-800">
    <main class="flex-1 h-full overflow-y-auto bg-slate-50 p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-black text-slate-900">Manajemen Produk</h2>
                <p class="text-slate-500 text-sm mt-1">Tambah, edit, atau hapus data komoditas tokomu secara manual.</p>
            </div>
            <a href="{{ route('produk.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl transition-all shadow-md">
                ➕ Tambah Produk Baru
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-800 border-b border-slate-200 text-xs font-bold text-slate-200 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nama Produk</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-center">Stok</th>
                        <th class="px-6 py-4">Harga Modal</th>
                        <th class="px-6 py-4">Harga Jual</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($produks as $prod)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $prod->nama_produk }}</td>
                        <td class="px-6 py-4"><span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-1 rounded-full uppercase font-bold">{{ $prod->kategori->nama_kategori }}</span></td>
                        <td class="px-6 py-4 text-center font-bold {{ $prod->stok_saat_ini <= 10 ? 'text-red-500' : 'text-slate-600' }}">{{ $prod->stok_saat_ini }} pcs</td>
                        <td class="px-6 py-4 text-slate-500">Rp{{ number_format($prod->harga_modal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-blue-600">Rp{{ number_format($prod->harga_jual, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 flex justify-center gap-3">
                            <a href="{{ route('produk.edit', $prod->id) }}" class="text-amber-500 hover:text-amber-700 text-sm font-bold">Edit</a>
                            <form action="{{ route('produk.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-bold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>