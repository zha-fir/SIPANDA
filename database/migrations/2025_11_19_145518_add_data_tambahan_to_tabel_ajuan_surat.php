<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tabel_ajuan_surat', function (Blueprint $table) {
            // Kita tambahkan kolom 'data_tambahan' bertipe TEXT
            // Kita taruh setelah kolom 'keperluan' agar rapi
            // 'nullable()' artinya boleh kosong (karena tidak semua surat butuh data ini)
            $table->text('data_tambahan')->nullable()->after('keperluan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabel_ajuan_surat', function (Blueprint $table) {
            // Perintah untuk menghapus kolom jika kita membatalkan migrasi
            $table->dropColumn('data_tambahan');
        });
    }
};