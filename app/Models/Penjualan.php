<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $guarded = [];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}