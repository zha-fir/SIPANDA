<?php

namespace App\Imports;

use App\Models\Warga;
use App\Models\User;
use App\Models\KK;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // <-- Ini penting agar bisa panggil nama kolom
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class WargaImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            // Validasi dasar: Lewati baris jika NIK atau NO_KK kosong
            if (empty($row['nik']) || empty($row['no_kk'])) {
                continue;
            }

            // 1. Cari Kartu Keluarga (KK)
            $kk = KK::where('no_kk', $row['no_kk'])->first();

            // Jika KK tidak ditemukan, HENTIKAN import dan beri tahu errornya
            if (!$kk) {
                throw ValidationException::withMessages([
                   'file_excel' => 'Import Gagal di baris untuk NIK ' . $row['nik'] . 
                                   '. NO_KK "' . $row['no_kk'] . 
                                   '" tidak ditemukan di database. Pastikan NO_KK sudah terdaftar.'
                ]);
            }

            // 2. Buat atau Update Akun Login (User)
            $user = User::updateOrCreate(
                ['username' => $row['nik']],
                [
                    'nama_lengkap' => $row['nama_lengkap'],
                    'password' => Hash::make('123456'),
                    'role' => 'warga'
                ]
            );

            // --- INI ADALAH PERBAIKANNYA ---
            $tanggalLahir = null;
            if (!empty($row['tanggal_lahir'])) {
                try {
                    // Cek jika ini angka (misal: 34773)
                    if (is_numeric($row['tanggal_lahir'])) {
                        // Konversi angka Excel ke objek DateTime
                        $dateTimeObject = Date::excelToDateTimeObject($row['tanggal_lahir']);
                        $tanggalLahir = $dateTimeObject->format('Y-m-d');
                    } else {
                        // Jika sudah format teks (misal: 1995-03-15), parse saja
                        $tanggalLahir = \Carbon\Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    // Jika formatnya aneh, biarkan null
                    $tanggalLahir = null;
                }
            }
            // --- AKHIR PERBAIKAN ---

            // 3. Buat atau Update Data Warga
            Warga::updateOrCreate(
                ['nik' => $row['nik']], 
                [
                    'nama_lengkap' => $row['nama_lengkap'],
                    'id_kk' => $kk->id_kk,
                    'id_user' => $user->id_user,
                    'tempat_lahir' => $row['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $tanggalLahir, // <-- Gunakan variabel yang sudah diterjemahkan
                    'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
                    'agama' => $row['agama'] ?? null,
                    'status_perkawinan' => $row['status_perkawinan'] ?? null,
                    'pekerjaan' => $row['pekerjaan'] ?? null,
                    'kewarganegaraan' => $row['kewarganegaraan'] ?? 'WNI',
                ]
            );
        }
    }
}