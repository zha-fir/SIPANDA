<?php

namespace App\Imports;

use App\Models\KK;
use App\Models\Dusun;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\ValidationException;

class KKImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            // 1. Validasi dasar: Lewati jika No KK kosong
            if (empty($row['no_kk'])) {
                continue;
            }

            // 2. Cari ID Dusun berdasarkan Nama Dusun di Excel
            // Admin harus menulis nama dusun PERSIS sama dengan di database
            $dusun = null;
            if (!empty($row['nama_dusun'])) {
                $dusun = Dusun::where('nama_dusun', 'LIKE', '%' . $row['nama_dusun'] . '%')->first();
                
                // Jika Dusun tidak ditemukan, lempar error agar Admin tahu
                if (!$dusun) {
                    throw ValidationException::withMessages([
                        'file_excel' => 'Error pada No KK ' . $row['no_kk'] . '. Dusun "' . $row['nama_dusun'] . '" tidak ditemukan di sistem. Pastikan ejaannya benar.'
                    ]);
                }
            }

            // 3. Buat atau Update Data KK
            KK::updateOrCreate(
                ['no_kk' => $row['no_kk']], // Kunci pencarian
                [
                    // Kita HAPUS baris 'nama_kepala_keluarga' => ...
                    // Karena data ini sekarang diambil otomatis dari tabel warga
                    
                    'alamat_kk' => $row['alamat'] ?? '-', // Sesuai form 'Alamat Lengkap'
                    'rt' => $row['rt'] ?? '000',          // Sesuai form 'RT'
                    'rw' => $row['rw'] ?? '000',          // Sesuai form 'RW'
                    'id_dusun' => $dusun ? $dusun->id_dusun : null, // Sesuai form 'Pilih Dusun'
                ]
            );
        }
    }
}