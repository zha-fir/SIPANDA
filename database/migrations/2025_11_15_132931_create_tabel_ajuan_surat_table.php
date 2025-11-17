<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('tabel_ajuan_surat', function (Blueprint $table) {
        $table->id('id_ajuan');
        $table->unsignedBigInteger('id_warga');
        $table->unsignedBigInteger('id_jenis_surat')->nullable();

        // --- Kolom Baru ---
        $table->text('keperluan')->nullable(); // Diisi oleh Warga
        $table->string('nomor_surat', 100)->nullable(); // Diisi oleh Admin saat Konfirmasi
        $table->unsignedBigInteger('id_pejabat_desa')->nullable(); // Diisi oleh Admin

        // --- Kolom Status yang Diperbarui ---
        // Kita hanya butuh 3 status ini
        $table->enum('status', ['BARU', 'SELESAI', 'DITOLAK'])->default('BARU');

        $table->text('catatan_penolakan')->nullable();
        $table->timestamp('tanggal_ajuan')->useCurrent();

        // --- Kolom yang Dihapus ---
        // KITA HAPUS: 'nomor_surat_lengkap' (diganti 'nomor_surat')
        // KITA HAPUS: 'file_hasil' (karena file digenerate on-the-fly)

        // Foreign keys
        $table->foreign('id_warga')->references('id_warga')->on('tabel_warga')->onDelete('cascade');
        $table->foreign('id_jenis_surat')->references('id_jenis_surat')->on('tabel_jenis_surat')->onDelete('set null');

        // Relasi baru ke tabel penandatangan
        $table->foreign('id_pejabat_desa')->references('id_pejabat_desa')->on('tabel_pejabat_desa')->onDelete('set null');
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabel_ajuan_surat');
    }
};