<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    // Mengizinkan semua kolom diisi secara massal (mass assignment)
    protected $guarded = [];

    // Relasi balik (Many-to-Many) ke Kategori
    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'kategori_kriteria')
                    ->withPivot('bobot');
    }
}