<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PejabatDesa extends Model
{
    use HasFactory;
    protected $table = 'tabel_pejabat_desa';
    protected $primaryKey = 'id_pejabat_desa';
    public $timestamps = false;
}