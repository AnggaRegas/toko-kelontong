<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard Admin | Toko 2 Putra</title>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 overflow-hidden h-screen">

<div class="flex h-screen w-full overflow-hidden">
    
    <aside class="relative w-64 shrink-0 bg-slate-800 border-r border-slate-700 shadow-xl flex flex-col h-full">
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

    <main class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50 p-6">
        
        <div class="mb-8">
            <h1 class="text-4xl font-black text-slate-800">Dashboard Admin</h1>
            <p class="text-slate-400 mt-2">Selamat datang, <span class="text-blue-400 font-bold">Toko 2 Putra</span></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Produk</p>
                <p class="text-4xl font-black text-white mt-2">{{ $totalProduk }}</p>
            </div>
            <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Kategori</p>
                <p class="text-4xl font-black text-white mt-2">{{ $totalKategori }}</p>
            </div>
            <div class="bg-slate-800 p-6 rounded-2xl border border-red-500/30">
                <p class="text-red-400 text-xs font-bold uppercase tracking-wider">Stok Kritis</p>
                <p class="text-4xl font-black text-red-500 mt-2">{{ $stokKritis }}</p>
            </div>
            <div class="bg-slate-800 p-6 rounded-2xl border border-emerald-500/30">
                <p class="text-emerald-400 text-xs font-bold uppercase tracking-wider">Omset Hari Ini</p>
                <p class="text-2xl font-black text-white mt-2">Rp {{ number_format($omsetHariIni, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-lg">
            <h2 class="text-lg font-bold text-white mb-6">📊 Grafik Omset Mingguan</h2>
            <div class="relative h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

    </main>
</div>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Omset (Rp)',
                data: [150000, 230000, 180000, 450000, 320000, 500000, 400000],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { grid: { color: '#334155' }, ticks: { color: '#94a3b8' } },
                x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
            },
            plugins: { legend: { labels: { color: '#ffffff' } } }
        }
    });
</script>
</body>
</html>