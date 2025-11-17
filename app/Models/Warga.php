<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'tabel_warga';
    protected $primaryKey = 'id_warga';
    public $timestamps = false;

    /**
     * TAMBAHKAN ARRAY INI:
     * Daftar kolom yang boleh diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'nik',
        'nama_lengkap',
        'id_kk',
        'id_user',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_perkawinan',
        'pekerjaan',
        'kewarganegaraan',
    ];

    /**
     * Relasi ke KK: Satu Warga dimiliki oleh satu KK.
     */
    public function kk()
    {
        return $this->belongsTo(KK::class, 'id_kk', 'id_kk');
    }

    /**
     * Relasi ke User: Satu Warga bisa punya satu akun User (untuk login).
     * Relasi ini bersifat opsional (nullable).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Saat data Warga dihapus, hapus juga data User yang terhubung.
     */
    protected static function booted()
    {
        static::deleting(function ($warga) {
            if ($warga->user) {
                $warga->user->delete();
            }
        });
    }
}