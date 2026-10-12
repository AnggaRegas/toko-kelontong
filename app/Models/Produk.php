<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $guarded = ['id'];
// Tambahkan ini di dalam class Produk
    protected $casts = [
        'bobot_kustom' => 'array',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    // Accessor untuk menghitung margin secara dinamis
    public function getMarginAttribute()
    {
        return $this->harga_jual - $this->harga_modal;
    }
}