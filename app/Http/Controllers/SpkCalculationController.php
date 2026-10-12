<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmartAlgorithmService;
use App\Models\Kategori;
use App\Models\Produk;

class SpkCalculationController extends Controller
{
    protected $smartService;

    public function __construct(SmartAlgorithmService $smartService)
    {
        $this->smartService = $smartService;
    }

    public function index(Request $request)
    {
        $kategoris = Kategori::all();
        $semuaProdukList = Produk::with('kategori')->orderBy('kategori_id')->orderBy('nama_produk')->get();
        
        $hasilRangking = null;
        $bobotKategori = null;
        $errorMessage = null;

        $mode = $request->query('mode', 'kategori');
        $bobotInput = $request->bobot ?? ['stok' => 30, 'terjual' => 30, 'margin' => 20, 'modal' => 10, 'lead_time' => 10];

        if ($request->filled('start_date') && $request->filled('end_date')) {
            
            if ($mode == 'produk') {
                $customWeights = $request->input('custom_weights', []);

                // 💾 1. SIMPAN BOBOT KUSTOM KE DATABASE
                foreach ($customWeights as $id => $weights) {
                    $p = $semuaProdukList->where('id', $id)->first();
                    if ($p) {
                        // Jika angka di tabel persis sama dengan baseline standar, kosongkan database agar bersih.
                        // Jika ada satu saja angka yang beda, simpan sebagai bobot_kustom.
                        if (
                            $weights['stok'] == $bobotInput['stok'] &&
                            $weights['terjual'] == $bobotInput['terjual'] &&
                            $weights['margin'] == $bobotInput['margin'] &&
                            $weights['modal'] == $bobotInput['modal'] &&
                            $weights['lead_time'] == $bobotInput['lead_time']
                        ) {
                            $p->bobot_kustom = null;
                        } else {
                            $p->bobot_kustom = $weights;
                        }
                        $p->save();
                    }
                }

                $hasilSmart = $this->smartService->calculateRestockPriority(
                    $request->start_date, 
                    $request->end_date,
                    'all',
                    $bobotInput,
                    $customWeights
                );
            } else {
                $hasilSmart = $this->smartService->calculateRestockPriority(
                    $request->start_date, 
                    $request->end_date,
                    $request->kategori_id,
                    $bobotInput,
                    []
                );
            }

            if (isset($hasilSmart['error']) && $hasilSmart['error'] != null) {
                $errorMessage = $hasilSmart['error'];
            } else {
                $hasilRangking = $hasilSmart['ranking'] ?? [];
                $bobotKategori = $hasilSmart['bobot'] ?? [];

                // 💾 2. SIMPAN HASIL SKOR SMART KE DATABASE
                foreach ($hasilRangking as $kategori => $produks) {
                    foreach ($produks as $prod) {
                        $p = Produk::find($prod->id);
                        if ($p) {
                            $p->skor_smart_terakhir = $prod->skor_smart;
                            $p->save();
                        }
                    }
                }
            }
        }

        // Ambil ulang data terbaru setelah di-save agar langsung tampil di form
        $semuaProdukList = Produk::with('kategori')->orderBy('kategori_id')->orderBy('nama_produk')->get();
        $semuaProduk = empty($hasilRangking) ? Kategori::with('produks')->get() : null;

        return view('dashboard.index', compact(
            'kategoris', 'semuaProdukList', 'hasilRangking', 'bobotKategori', 'semuaProduk', 'mode', 'errorMessage'
        ));
    }
}