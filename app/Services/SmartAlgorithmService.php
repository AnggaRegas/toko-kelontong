<?php

namespace App\Services;

use App\Models\Kategori;
use App\Models\Produk;

class SmartAlgorithmService
{
    private function normalisasiBobot($bobotInput)
    {
        $totalBobotInput = array_sum($bobotInput);

        if ($totalBobotInput == 0) {
            $totalBobotInput = 1;
        }

        return [
            'Sisa Stok'       => ($bobotInput['stok'] ?? 0) / $totalBobotInput,
            'Total Penjualan' => ($bobotInput['terjual'] ?? 0) / $totalBobotInput,
            'Margin'          => ($bobotInput['margin'] ?? 0) / $totalBobotInput,
            'Harga Modal'     => ($bobotInput['modal'] ?? 0) / $totalBobotInput,
            'Lama Pengiriman' => ($bobotInput['lead_time'] ?? 0) / $totalBobotInput,
        ];
    }

    public function calculateRestockPriority(
        $startDate,
        $endDate,
        $kategoriId = null,
        $bobotInput = [],
        $customWeights = []
    ) {
        $hasilAkhir = [];
        $dataBobotKategori = [];

        // Bobot standar
        if (empty($bobotInput)) {
            $bobotInput = [
                'stok' => 30,
                'terjual' => 30,
                'margin' => 20,
                'modal' => 10,
                'lead_time' => 10
            ];
        }

        $bobotBase = $this->normalisasiBobot($bobotInput);

        
        $allProducts = Produk::withSum(
            ['transaksis' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
            }],
            'jumlah_terjual'
        )->get();

        $globalMinMax = $this->getMinMax($allProducts);

        
        $query = Kategori::query();

        if ($kategoriId && $kategoriId != 'all') {
            $query->where('id', $kategoriId);
        }

        $kategoris = $query->get();

        foreach ($kategoris as $kategori) {

            $produks = Produk::where('kategori_id', $kategori->id)
                ->withSum(
                    ['transaksis' => function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
                    }],
                    'jumlah_terjual'
                )
                ->get();

            if ($produks->count() < 1) {
                continue;
            }

            // PAKAI MIN-MAX GLOBAL
            $minMax = $this->getMinMax($produks);

            $produkDinilai = [];
            $kategoriCustomWeights = [];

            foreach ($produks as $produk) {

                $terjual = $produk->transaksis_sum_jumlah_terjual ?? 0;

                $isSimulated = false;
                $bobotAktif = $bobotBase;

                $rawCustom = $customWeights[$produk->id] ?? null;

                if (
                    $rawCustom &&
                    (
                        $rawCustom['stok'] != $bobotInput['stok'] ||
                        $rawCustom['terjual'] != $bobotInput['terjual'] ||
                        $rawCustom['margin'] != $bobotInput['margin'] ||
                        $rawCustom['modal'] != $bobotInput['modal'] ||
                        $rawCustom['lead_time'] != $bobotInput['lead_time']
                    )
                ) {
                    $isSimulated = true;
                    $bobotAktif = $this->normalisasiBobot($rawCustom);

                    $kategoriCustomWeights[$produk->id] = $rawCustom;
                }

                $skorTotal = 0;

                // STOK (Cost)
                $skorTotal +=
                    $this->hitungUtility(
                        $produk->stok_saat_ini,
                        $minMax['stok'],
                        'cost'
                    ) * $bobotAktif['Sisa Stok'];

                // TERJUAL (Benefit)
                $skorTotal +=
                    $this->hitungUtility(
                        $terjual,
                        $minMax['terjual'],
                        'benefit'
                    ) * $bobotAktif['Total Penjualan'];

                // MARGIN (Benefit)
                $skorTotal +=
                    $this->hitungUtility(
                        $produk->margin,
                        $minMax['margin'],
                        'benefit'
                    ) * $bobotAktif['Margin'];

                // MODAL (Cost)
                $skorTotal +=
                    $this->hitungUtility(
                        $produk->harga_modal,
                        $minMax['modal'],
                        'cost'
                    ) * $bobotAktif['Harga Modal'];

                // LEAD TIME (Cost)
                $skorTotal +=
                    $this->hitungUtility(
                        $produk->lama_pengiriman_hari,
                        $minMax['lead_time'],
                        'cost'
                    ) * $bobotAktif['Lama Pengiriman'];

                $produk->skor_smart = $skorTotal;
                $produk->data_terjual = $terjual;
                $produk->is_simulated = $isSimulated;

                $produkDinilai[] = $produk;
            }

            $dataBobotKategori[$kategori->nama_kategori] = [
                'base' => $bobotBase,
                'custom' => $kategoriCustomWeights
            ];

            $hasilAkhir[$kategori->nama_kategori] =
                collect($produkDinilai)
                    ->sortByDesc('skor_smart')
                    ->values()
                    ->all();
        }

        return [
            'error' => empty($hasilAkhir)
                ? 'Tidak ada cukup produk yang tersedia untuk diranking.'
                : null,

            'ranking' => $hasilAkhir,

            'bobot' => $dataBobotKategori
        ];
    }

    private function getMinMax($produks)
    {
        return [
            'stok' => [
                'min' => $produks->min('stok_saat_ini'),
                'max' => $produks->max('stok_saat_ini')
            ],

            'terjual' => [
                'min' => $produks->min('transaksis_sum_jumlah_terjual') ?: 0,
                'max' => $produks->max('transaksis_sum_jumlah_terjual') ?: 0
            ],

            'margin' => [
                'min' => $produks->min('margin'),
                'max' => $produks->max('margin')
            ],

            'modal' => [
                'min' => $produks->min('harga_modal'),
                'max' => $produks->max('harga_modal')
            ],

            'lead_time' => [
                'min' => $produks->min('lama_pengiriman_hari'),
                'max' => $produks->max('lama_pengiriman_hari')
            ],
        ];
    }

    private function hitungUtility($nilai, $batas, $sifat)
    {
        $pembagi = $batas['max'] - $batas['min'];

        if ($pembagi <= 0) {
            return 1;
        }

        if ($sifat === 'benefit') {
            return ($nilai - $batas['min']) / $pembagi;
        }

        return ($batas['max'] - $nilai) / $pembagi;
    }
}