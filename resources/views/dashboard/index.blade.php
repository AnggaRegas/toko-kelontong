<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Dashboard Terpadu | Toko 2 Putra</title>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 overflow-hidden h-screen">

<div class="flex h-screen w-full overflow-hidden">
    
    <aside class="relative w-64 shrink-0 bg-slate-800 border-r border-slate-700 shadow-xl flex flex-col h-full z-10">
        <div class="p-6 border-b border-slate-700 shrink-0 text-center">
            <h1 class="text-2xl font-bold text-blue-400">Toko 2 Putra</h1>
        </div>
        <div class="p-4 space-y-2 overflow-y-auto flex-1">
            <a href="{{ route('dashboard.home') }}" class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('dashboard.home') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white' }}">📈 Dashboard</a>
            <a href="{{ route('dashboard.index') }}" class="block px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('dashboard.index') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-700 hover:text-white' }}">📊 Analisis SMART</a>
            <a href="{{ route('produk.index') }}" class="block px-4 py-3 rounded-xl font-semibold text-slate-400 hover:bg-slate-700 hover:text-white">📦 Kelola Produk</a>
            <a href="{{ route('kasir.index') }}" class="block px-4 py-3 rounded-xl font-semibold text-slate-400 hover:bg-slate-700 hover:text-white">🛒 Sistem Kasir</a>
        </div>
        <div class="mt-auto p-4 border-t border-slate-700 shrink-0">
            <div class="text-center text-sm font-bold text-slate-400 mb-3">👤 {{ auth()->user()->name ?? 'Admin' }}</div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-slate-700 hover:bg-red-600 text-slate-300 hover:text-white font-bold py-2.5 rounded-lg transition-all">Keluar</button>
            </form>
        </div>
    </aside>

<main class="flex-1 h-full overflow-y-auto bg-slate-50 p-6">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800">
                {{ $mode == 'produk' ? '🎯 Mode Custom Per-Produk' : '📊 Rekomendasi Restok (Per Kategori)' }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ $mode == 'produk' ? 'Ubah angka persentase pada produk mana saja secara instan.' : 'Hitung ranking secara global berdasarkan filter kategori.' }}
            </p>
        </div>
        
        <div>
            @if($mode == 'produk')
                <a href="{{ route('dashboard.index', ['mode' => 'kategori']) }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-2.5 px-5 rounded-lg transition-all shadow-sm flex items-center gap-2">
                    📦 Kembali ke Mode Kategori
                </a>
            @else
                <a href="{{ route('dashboard.index', ['mode' => 'produk']) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-lg transition-all shadow-md flex items-center gap-2">
                    🎯 Kustomisasi Bobot Produk
                </a>
            @endif
        </div>
    </div>

    @if(isset($errorMessage))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 font-bold shadow-sm">
            {{ $errorMessage }}
        </div>
    @endif

    @if($mode == 'produk')
        <form action="{{ route('dashboard.index') }}" method="GET" class="mb-8">
            <input type="hidden" name="mode" value="produk">
            
            <div class="bg-white rounded-2xl shadow-sm border-t-4 border-emerald-500 overflow-hidden flex flex-col">
                <div class="bg-emerald-50 p-4 border-b border-emerald-100 flex justify-between items-center flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-emerald-900">📅 Periode Data Terjual:</span>
                        <input type="date" name="start_date" value="{{ request('start_date') ?? now()->subMonths(3)->format('Y-m-d') }}" required class="bg-white border border-emerald-200 rounded px-2 py-1 text-xs outline-none focus:ring-1 focus:ring-emerald-500 font-bold">
                        <span class="text-xs text-emerald-700">s/d</span>
                        <input type="date" name="end_date" value="{{ request('end_date') ?? now()->format('Y-m-d') }}" required class="bg-white border border-emerald-200 rounded px-2 py-1 text-xs outline-none focus:ring-1 focus:ring-emerald-500 font-bold">
                    </div>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded-lg transition-all shadow-sm text-sm">
                        🚀 Kalkulasi Perangkingan
                    </button>
                </div>
                
                <div class="max-h-[50vh] overflow-y-auto bg-slate-50 relative">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-white border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th class="px-4 py-3 text-xs font-extrabold text-slate-800">Nama Produk</th>
                                <th class="px-2 py-3 text-[10px] font-bold text-slate-500 uppercase text-center w-20">Stok (%)</th>
                                <th class="px-2 py-3 text-[10px] font-bold text-slate-500 uppercase text-center w-20">Terjual (%)</th>
                                <th class="px-2 py-3 text-[10px] font-bold text-slate-500 uppercase text-center w-20">Margin (%)</th>
                                <th class="px-2 py-3 text-[10px] font-bold text-slate-500 uppercase text-center w-20">Modal (%)</th>
                                <th class="px-2 py-3 text-[10px] font-bold text-slate-500 uppercase text-center w-20">Lead Time (%)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($semuaProdukList as $prod)
                                @php 
                                    // LOGIKA PRIORITAS: 
                                    // 1. Ambil dari form jika baru submit
                                    // 2. Ambil dari database jika sebelumnya pernah disave
                                    // 3. Pakai baseline default jika belum pernah diapa-apain
                                    $cw = request('custom_weights')[$prod->id] 
                                          ?? $prod->bobot_kustom 
                                          ?? ['stok'=>30, 'terjual'=>30, 'margin'=>20, 'modal'=>10, 'lead_time'=>10];
                                @endphp
                                <tr class="hover:bg-amber-50/50 transition-colors {{ $prod->bobot_kustom ? 'bg-amber-50/30' : '' }}">
                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-slate-700 text-xs">{{ $prod->nama_produk }}</p>
                                            @if($prod->bobot_kustom)
                                                <span class="text-[8px] bg-amber-500 text-white px-1.5 py-0.5 rounded uppercase font-bold">Tersimpan</span>
                                            @endif
                                        </div>
                                        <p class="text-[9px] text-slate-400 font-semibold">{{ $prod->kategori->nama_kategori ?? 'Umum' }}</p>
                                    </td>
                                    <td class="px-2 py-2"><input type="number" name="custom_weights[{{$prod->id}}][stok]" value="{{$cw['stok']}}" class="w-full text-center bg-white border border-slate-300 rounded px-1 py-1.5 text-xs outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-bold {{ $prod->bobot_kustom ? 'text-amber-700' : 'text-slate-800' }}"></td>
                                    <td class="px-2 py-2"><input type="number" name="custom_weights[{{$prod->id}}][terjual]" value="{{$cw['terjual']}}" class="w-full text-center bg-white border border-slate-300 rounded px-1 py-1.5 text-xs outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-bold {{ $prod->bobot_kustom ? 'text-amber-700' : 'text-slate-800' }}"></td>
                                    <td class="px-2 py-2"><input type="number" name="custom_weights[{{$prod->id}}][margin]" value="{{$cw['margin']}}" class="w-full text-center bg-white border border-slate-300 rounded px-1 py-1.5 text-xs outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-bold {{ $prod->bobot_kustom ? 'text-amber-700' : 'text-slate-800' }}"></td>
                                    <td class="px-2 py-2"><input type="number" name="custom_weights[{{$prod->id}}][modal]" value="{{$cw['modal']}}" class="w-full text-center bg-white border border-slate-300 rounded px-1 py-1.5 text-xs outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-bold {{ $prod->bobot_kustom ? 'text-amber-700' : 'text-slate-800' }}"></td>
                                    <td class="px-2 py-2"><input type="number" name="custom_weights[{{$prod->id}}][lead_time]" value="{{$cw['lead_time']}}" class="w-full text-center bg-white border border-slate-300 rounded px-1 py-1.5 text-xs outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-bold {{ $prod->bobot_kustom ? 'text-amber-700' : 'text-slate-800' }}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

    @else
        <div class="bg-white rounded-2xl shadow-sm border-t-4 border-blue-600 overflow-hidden mb-6 flex flex-col">
            <div class="bg-slate-900 text-white p-4 border-b border-slate-200">
                <h2 class="text-sm font-bold">⚙️ Parameter Analisis SMART</h2>
            </div>
            <div class="p-5">
                <form action="{{ route('dashboard.index') }}" method="GET" class="space-y-6">
                    <input type="hidden" name="mode" value="kategori">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Mulai Tanggal</label>
                            <input type="date" name="start_date" value="{{ request('start_date') ?? now()->subMonths(3)->format('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ request('end_date') ?? now()->format('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Kategori Barang</label>
                            <select name="kategori_id" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="all">-- Semua Kategori --</option>
                                @foreach($kategoris ?? [] as $kat)
                                    <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="bg-slate-100 p-4 rounded-xl border border-slate-200">
                        <p class="text-xs font-bold text-slate-800 uppercase mb-3 border-b border-slate-200 pb-2">⚖️ Bobot Kriteria Standar (%)</p>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            @php $b = request('bobot') ?? ['stok'=>30, 'terjual'=>30, 'margin'=>20, 'modal'=>10, 'lead_time'=>10]; @endphp
                            <div><label class="block text-[10px] uppercase font-bold text-slate-500 mb-1">Sisa Stok</label><input type="number" name="bobot[stok]" value="{{ $b['stok'] }}" required class="w-full text-center bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-sm outline-none font-bold"></div>
                            <div><label class="block text-[10px] uppercase font-bold text-slate-500 mb-1">Total Terjual</label><input type="number" name="bobot[terjual]" value="{{ $b['terjual'] }}" required class="w-full text-center bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-sm outline-none font-bold"></div>
                            <div><label class="block text-[10px] uppercase font-bold text-slate-500 mb-1">Margin Untung</label><input type="number" name="bobot[margin]" value="{{ $b['margin'] }}" required class="w-full text-center bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-sm outline-none font-bold"></div>
                            <div><label class="block text-[10px] uppercase font-bold text-slate-500 mb-1">Harga Modal</label><input type="number" name="bobot[modal]" value="{{ $b['modal'] }}" required class="w-full text-center bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-sm outline-none font-bold"></div>
                            <div><label class="block text-[10px] uppercase font-bold text-slate-500 mb-1">Lama Kirim</label><input type="number" name="bobot[lead_time]" value="{{ $b['lead_time'] }}" required class="w-full text-center bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-sm outline-none font-bold"></div>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all shadow-md text-sm">
                        🚀 Proses Analisis SMART
                    </button>
                </form>
            </div>
        </div>
    @endif


    @if(isset($hasilRangking) && count($hasilRangking) > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mt-6">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-200 shrink-0">
            <h2 class="text-xl font-extrabold {{ $mode == 'produk' ? 'text-emerald-700' : 'text-slate-900' }}">
                🏆 {{ $mode == 'produk' ? 'Hasil Simulasi Kompetisi Produk' : 'Hasil Analisis SMART' }}
            </h2>
            <a href="{{ route('dashboard.index', ['mode' => $mode]) }}" class="bg-slate-50 border border-slate-300 hover:bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg font-bold text-xs transition-all">Tutup Hasil</a>
        </div>

        <div class="p-6 bg-slate-50/50 space-y-6">
            @foreach ($hasilRangking ?? [] as $namaKategori => $produks)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                
                <div class="bg-slate-100 px-4 py-3 border-b border-slate-200 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-4 {{ $mode == 'produk' ? 'bg-emerald-600' : 'bg-blue-600' }} rounded-full"></span>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800">{{ $namaKategori }}</h3>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border-b border-slate-200 space-y-3">
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Bobot Standar Kategori (%):</span>
                        @foreach(($bobotKategori[$namaKategori]['base'] ?? []) as $namaKriteria => $nilaiBobot)
                            @if(!is_array($nilaiBobot))
                            <span class="bg-white border border-slate-200 text-slate-700 px-2.5 py-1 rounded-md text-[10px] font-black shadow-sm">
                                {{ $namaKriteria }}: {{ round($nilaiBobot * 100, 1) }}%
                            </span>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-white border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase">Rank</th>
                                <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase">Nama Produk</th>
                                <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center bg-blue-50/50" title="Cost (Makin kecil makin bagus)">Stok</th>
                                <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center bg-emerald-50/50" title="Benefit (Makin besar makin bagus)">Terjual</th>
                                <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center bg-emerald-50/50" title="Benefit (Makin besar makin bagus)">Margin</th>
                                <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center bg-red-50/50" title="Cost (Makin kecil makin bagus)">Modal</th>
                                <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center bg-blue-50/50" title="Benefit (Makin cepat/besar angkanya makin bagus)">Lama Kirim</th>
                                <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Tipe Penilaian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($produks ?? [] as $index => $produk)
                            <tr class="{{ $produk->is_simulated ? 'bg-amber-50 hover:bg-amber-100/80 font-medium border-y border-amber-300' : ($index == 0 ? 'bg-orange-50/40' : '') }} hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 font-black {{ $produk->is_simulated ? 'text-amber-700' : 'text-slate-400' }}">#{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-slate-700">
                                    <div>
                                        <span>{{ $produk->nama_produk }}</span>
                                        @if($produk->is_simulated && isset($bobotKategori[$namaKategori]['custom'][$produk->id]))
                                            @php $cb = $bobotKategori[$namaKategori]['custom'][$produk->id]; @endphp
                                            <p class="text-[9px] text-amber-600 font-bold mt-1">
                                                (Stok:{{$cb['stok']}}% | Jual:{{$cb['terjual']}}% | Margin:{{$cb['margin']}}% | Modal:{{$cb['modal']}}% | Kirim:{{$cb['lead_time']}}%)
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                
                                {{-- 5 DATA KRITERIA MENTAH UNTUK KEBUTUHAN EXCEL --}}
                                <td class="px-4 py-3 text-center font-semibold text-slate-600 bg-blue-50/10">{{ $produk->stok_saat_ini }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-emerald-600 bg-emerald-50/10">{{ $produk->data_terjual ?? 0 }}</td>
                                <td class="px-4 py-3 text-center text-slate-600 bg-emerald-50/10">{{ $produk->margin }}</td>
                                <td class="px-4 py-3 text-center text-slate-600 bg-red-50/10">{{ $produk->harga_modal }}</td>
                                <td class="px-4 py-3 text-center text-slate-600 bg-blue-50/10">{{ $produk->lama_pengiriman_hari }}</td>
                                
                                <td class="px-4 py-3 text-center">
                                    @if($produk->is_simulated)
                                        <span class="bg-amber-600 text-white text-[9px] px-2 py-1 rounded font-bold uppercase shadow-sm">
                                            Bobot Kustom 🛠️
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">Bobot Standar Baseline</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
    @endif

</main>

</div>

</body>
</html>