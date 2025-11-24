<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KK extends Model
{
    use HasFactory;

    protected $table = 'tabel_kk'; // Hubungkan ke 'tabel_kk'
    protected $primaryKey = 'id_kk'; // Tentukan primary key
    public $timestamps = false; // Kita tidak pakai timestamps

    protected $fillable = [
        'no_kk',
        'nama_kepala_keluarga', // Meskipun nullable, tetap harus didaftarkan
        'alamat_kk',
        'rt',
        'rw',
        'id_dusun',
    ];
    /**
     * Mendefinisikan relasi "belongsTo" ke Model Dusun.
     * Satu KK hanya dimiliki oleh satu Dusun.
     */
    public function dusun()
    {
        // Parameter: (Model Tujuan, foreign_key_di_tabel_ini, primary_key_di_tabel_tujuan)
        return $this->belongsTo(Dusun::class, 'id_dusun', 'id_dusun');
    }

    public function warga()
    {
        return $this->hasMany(Warga::class, 'id_kk', 'id_kk');
    }

    public function kepalaKeluarga()
    {
        return $this->hasOne(Warga::class, 'id_kk', 'id_kk')
                    ->where('status_dalam_keluarga', 'KEPALA KELUARGA');
    }
}