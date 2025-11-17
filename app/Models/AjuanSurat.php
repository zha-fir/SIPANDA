<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjuanSurat extends Model
{
    use HasFactory;

    protected $table = 'tabel_ajuan_surat'; // Hubungkan ke tabel
    protected $primaryKey = 'id_ajuan'; // Tentukan primary key

    // Kita HANYA pakai 'created_at' (tanggal_ajuan), tapi tidak 'updated_at'
    const UPDATED_AT = null;
    protected $fillable = [
        'id_warga', 
        'id_jenis_surat', 
        'status', 
        'nomor_surat_lengkap', 
        'catatan_penolakan', 
        'file_hasil'
    ];

    /**
     * Relasi ke Warga: Satu ajuan dimiliki oleh satu warga.
     */
    public function warga()
    {
        return $this->belongsTo(Warga::class, 'id_warga', 'id_warga');
    }

    /**
     * Relasi ke JenisSurat: Satu ajuan merujuk ke satu jenis surat.
     */
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat', 'id_jenis_surat');
    }
}