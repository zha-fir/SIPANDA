<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // <-- Tetap pakai ini
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Beritahu Laravel untuk menggunakan tabel 'tabel_users'
     */
    protected $table = 'tabel_users';

    /**
     * Beritahu Laravel apa Primary Key-nya
     */
    protected $primaryKey = 'id_user';

    /**
     * Kita tidak pakai timestamps (created_at/updated_at)
     */
    public $timestamps = false;

    /**
     * Kolom yang boleh diisi (meskipun kita tidak buat registrasi)
     */
    protected $fillable = [
        'username',
        'nama_lengkap',
        'password',
        'role',
    ];

    /**
     * Kolom yang disembunyikan
     */
    protected $hidden = [
        'password',
    ];

    /**
     * TAMBAHKAN INI:
     * Mendefinisikan relasi "satu-ke-satu" (inverse) ke Warga.
     * Satu akun User dimiliki oleh satu Warga.
     */
    public function warga()
    {
        return $this->hasOne(Warga::class, 'id_user', 'id_user');
    }
}