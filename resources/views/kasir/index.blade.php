<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Sistem POS Kasir | SmartRestock</title>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 overflow-hidden h-screen">
    
<div class="flex h-screen w-full overflow-hidden">
    
    <aside class="relative w-64 shrink-0 bg-slate-800 border-r border-slate-700 shadow-xl flex flex-col h-full z-50">
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

    <main class="flex-1 h-full overflow-y-auto bg-slate-50 p-6 relative">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <div class="lg:col-span-2 space-y-6">
                @if(session('success'))
                    <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl font-bold border border-emerald-200">✅ {{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="bg-red-50 text-red-700 p-4 rounded-xl font-bold border border-red-200">⚠️ {{ $errors->first() }}</div>
                @endif

                <div class="bg-slate-800 p-4 rounded-2xl shadow-sm border border-slate-700 flex gap-4 items-center">
                    <span class="text-2xl">🔍</span>
                    <input type="text" id="searchInput" placeholder="Cari nama barang..." 
                        class="w-full bg-transparent text-lg outline-none font-medium text-white placeholder-slate-400">
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="productGrid">
                    @foreach($produks as $p)
                    <div class="produk-card bg-white p-5 rounded-2xl shadow-sm border border-slate-200 cursor-pointer hover:border-blue-500 hover:shadow-md transition-all flex flex-col justify-between h-full" 
                         data-name="{{ strtolower($p->nama_produk) }}"
                         onclick='addToCart(
                                {{ $p->id }},
                                @json($p->nama_produk),
                                {{ $p->harga_jual }},
                                {{ $p->stok_saat_ini }}
                                )'>
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">ID: {{ $p->id }}</span>
                            <h4 class="font-bold text-slate-800 leading-tight mt-1">{{ $p->nama_produk }}</h4>
                        </div>
                        <div class="mt-4 flex justify-between items-end">
                            <p class="font-black text-blue-600">Rp{{ number_format($p->harga_jual, 0, ',', '.') }}</p>
                            <p class="text-xs font-bold {{ $p->stok_saat_ini <= 5 ? 'text-red-500' : 'text-slate-500' }}">Sisa: {{ $p->stok_saat_ini }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-8">
                    <div class="bg-slate-100 px-6 py-4 border-b border-slate-200"><h3 class="text-lg font-bold text-slate-800">📋 Penjualan Hari Ini</h3></div>
                    <div class="max-h-64 overflow-y-auto">
                        <table class="w-full text-left text-sm">
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-600">
                                @foreach($transaksisHariIni as $trx)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-3 font-mono text-slate-400">{{ $trx->created_at->format('H:i') }}</td>
                                    <td class="px-6 py-3 text-slate-800">{{ $trx->produk->nama_produk }}</td>
                                    <td class="px-6 py-3 text-center font-bold">{{ $trx->jumlah_terjual }}x</td>
                                    <td class="px-6 py-3 text-emerald-600 font-bold text-right">Rp{{ number_format($trx->produk->harga_jual * $trx->jumlah_terjual, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 sticky top-0">
                <div class="bg-white rounded-3xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="bg-slate-800 px-6 py-4">
                        <h3 class="text-xl font-black text-white flex items-center gap-2">
                            🛒 Keranjang Belanja
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div id="cartItems" class="space-y-4 mb-4 max-h-[50vh] overflow-y-auto pr-2">
                            </div>

                        <p class="text-center text-slate-400 text-sm py-4" id="emptyCartMsg">
                            Keranjang masih kosong.<br>
                            Klik barang di sebelah kiri.
                        </p>

                        <div class="border-t border-slate-100 pt-4">
                            <div class="flex justify-between items-center mb-6">
                                <span class="font-bold text-slate-500">Total Harga</span>
                                <span class="text-2xl font-black text-slate-900" id="grandTotalDisplay">Rp 0</span>
                            </div>

                            <form action="{{ route('kasir.store') }}" method="POST" id="checkoutForm">
                                @csrf
                                <div id="hiddenInputsContainer"></div>
                                <button type="submit" id="btnSubmit" disabled class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 text-white font-black py-4 rounded-xl transition-all">
                                    💳 PROSES PEMBAYARAN
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>

</div>

<script>
    let cart = [];

    // TAMBAH PRODUK KE KERANJANG
    function addToCart(id, nama, harga, maxStok)
    {
        const existingItem = cart.find(item => item.id == id);

        if(existingItem)
        {
            if(existingItem.qty < maxStok)
            {
                existingItem.qty++;
            }
            else
            {
                alert('Stok tidak mencukupi!');
            }
        }
        else
        {
            cart.push({
                id: id,
                nama: nama,
                harga: harga,
                qty: 1,
                maxStok: maxStok
            });
        }

        renderCart();
    }

    // UBAH JUMLAH
    function changeQty(id, change)
    {
        const item = cart.find(item => item.id == id);

        if(!item) return;

        item.qty += change;

        if(item.qty <= 0)
        {
            cart = cart.filter(i => i.id != id);
        }

        if(item.qty > item.maxStok)
        {
            item.qty = item.maxStok;
            alert('Stok maksimal tercapai!');
        }

        renderCart();
    }

    // RENDER KERANJANG
    function renderCart()
    {
        const cartContainer = document.getElementById('cartItems');
        const hiddenInputs = document.getElementById('hiddenInputsContainer');
        const emptyMsg = document.getElementById('emptyCartMsg');
        const grandTotalDisplay = document.getElementById('grandTotalDisplay');
        const btnSubmit = document.getElementById('btnSubmit');

        cartContainer.innerHTML = '';
        hiddenInputs.innerHTML = '';

        let grandTotal = 0;

        if(cart.length === 0)
        {
            emptyMsg.style.display = 'block';
            btnSubmit.disabled = true;
            grandTotalDisplay.innerText = 'Rp 0';
            return;
        }

        emptyMsg.style.display = 'none';
        btnSubmit.disabled = false;

        cart.forEach((item, index) => {

            const subtotal = item.qty * item.harga;
            grandTotal += subtotal;

            const itemHtml = `
                <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-200">
                    <div class="flex-1 pr-2">
                        <div class="font-bold text-sm leading-tight text-slate-800">
                            ${item.nama}
                        </div>
                        <div class="text-blue-600 text-xs font-semibold mt-1">
                            Rp${item.harga.toLocaleString('id-ID')}
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-lg p-1">
                            <button type="button" onclick="changeQty(${item.id}, -1)" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-red-500 hover:text-white rounded text-slate-600 font-bold transition-colors">-</button>
                            <span class="font-bold w-4 text-center text-sm">${item.qty}</span>
                            <button type="button" onclick="changeQty(${item.id}, 1)" class="w-6 h-6 flex items-center justify-center bg-slate-100 hover:bg-emerald-500 hover:text-white rounded text-slate-600 font-bold transition-colors">+</button>
                        </div>
                        <div class="font-bold text-right text-sm text-slate-800">
                            Rp${subtotal.toLocaleString('id-ID')}
                        </div>
                    </div>
                </div>
            `;

            cartContainer.innerHTML += itemHtml;

            hiddenInputs.innerHTML += `
                <input type="hidden" name="keranjang[${index}][id]" value="${item.id}">
                <input type="hidden" name="keranjang[${index}][qty]" value="${item.qty}">
            `;
        });

        grandTotalDisplay.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    // PENCARIAN PRODUK
    document.getElementById('searchInput').addEventListener('input', function(e)
    {
        const keyword = e.target.value.toLowerCase();

        document.querySelectorAll('.produk-card').forEach(card =>
        {
            const nama = card.dataset.name;

            if(nama.includes(keyword))
            {
                card.style.display = 'flex';
            }
            else
            {
                card.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>