<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $guarded = [];

    // Relasi ke kriteria beserta pivot bobotnya
    public function kriterias()
    {
        return $this->belongsToMany(Kriteria::class, 'kategori_kriteria')
                    ->withPivot('bobot');
    }

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}