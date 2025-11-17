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
    Schema::create('tabel_pejabat_desa', function (Blueprint $table) {
        $table->id('id_pejabat_desa');
        $table->string('nama_pejabat', 100);
        $table->string('jabatan', 100);
        // $table->timestamps(); // Kita tidak perlu ini
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabel_pejabat_desa');
    }
};
