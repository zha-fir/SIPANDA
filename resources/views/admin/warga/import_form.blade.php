@extends('layouts.admin')

@section('title', 'Import Data Warga')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Import Warga dari Excel</h6>
    </div>
    <div class="card-body">

        <p>Upload file Excel (.xlsx atau .xls) untuk mengimpor data warga secara massal.</p>
        <p class="text-danger font-weight-bold">
            PENTING: Pastikan data **Nomor KK** yang Anda masukkan di Excel sudah terdaftar di sistem (di menu Manajemen KK) sebelum Anda mengimpor data warga.
        </p>

        <hr>

        {{-- Tampilkan template/petunjuk kolom --}}
        <strong>Contoh Format Kolom di Excel:</strong>
        <ol class="mt-2">
            <li>**NIK** (Wajib, 16 digit)</li>
            <li>**NAMA_LENGKAP** (Wajib)</li>
            <li>**NO_KK** (Wajib, harus ada di Manajemen KK)</li>
            <li>**TEMPAT_LAHIR**</li>
            <li>**TANGGAL_LAHIR** (Format: YYYY-MM-DD, contoh: 1990-10-25)</li>
            <li>**JENIS_KELAMIN** (Isi: LAKI-LAKI atau PEREMPUAN)</li>
            <li>**AGAMA**</li>
            <li>**STATUS_PERKAWINAN**</li>
            <li>**PEKERJAAN**</li>
            <li>**KEWARGANEGARAAN** (Default: WNI jika dikosongi)</li>
        </ol>
        <p>Sistem akan **otomatis membuatkan akun login** untuk setiap warga dengan **Username = NIK** dan **Password Default = 123456**.</p>

        <hr>

        {{-- Menampilkan error validasi --}}
        @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
        {{-- Menampilkan pesan sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.warga.import.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file_excel">Pilih File Excel (.xlsx, .xls)</label>
                <input type="file" class="form-control-file" id="file_excel" name="file_excel" required accept=".xlsx, .xls">
            </div>

            <a href="{{ route('warga.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload dan Import
            </button>
        </form>
    </div>
</div>
@endsection