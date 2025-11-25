<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dusun extends Model
{
    use HasFactory;

    protected $table = 'tabel_dusun';
    protected $primaryKey = 'id_dusun';
    public $timestamps = false;

    // Relasi ke KK (Satu Dusun punya banyak KK) - MUNGKIN SUDAH ADA
    // public function kk()
    // {
    //     return $this->hasMany(KK::class, 'id_dusun', 'id_dusun');
    // }

    /**
     * TAMBAHKAN INI (PENYEBAB EROR):
     * Relasi Dusun ke Warga (Melalui KK)
     * Artinya: Dusun punya banyak Warga, lewat perantara tabel KK.
     */
    public function warga()
    {
        // Argumen: (Model Tujuan, Model Perantara, FK di Perantara, FK di Tujuan, PK Lokal, PK di Perantara)
        return $this->hasManyThrough(
            Warga::class, 
            KK::class, 
            'id_dusun', // Foreign key di tabel_kk
            'id_kk',    // Foreign key di tabel_warga
            'id_dusun', // Local key di tabel_dusun
            'id_kk'     // Local key di tabel_kk
        );
    }
}