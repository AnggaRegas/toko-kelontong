<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('produks', function (Blueprint $table) {
            // Menyimpan bobot kustom dalam format JSON (array)
            $table->json('bobot_kustom')->nullable()->after('stok_saat_ini');
            // Menyimpan hasil skor terakhir
            $table->decimal('skor_smart_terakhir', 8, 3)->nullable()->after('bobot_kustom');
        });
    }

    public function down()
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn(['bobot_kustom', 'skor_smart_terakhir']);
        });
    }
};