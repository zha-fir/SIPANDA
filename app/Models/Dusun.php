<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dusun extends Model
{
    use HasFactory;

    protected $table = 'tabel_dusun';
    public $timestamps = false;

    /**
     * TAMBAHKAN INI:
     * Beritahu Laravel apa nama Primary Key kita.
     */
    protected $primaryKey = 'id_dusun';
}