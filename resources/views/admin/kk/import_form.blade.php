@extends('layouts.admin')

@section('title', 'Import Data KK')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Import KK dari Excel</h6>
        </div>
        <div class="card-body">

            <div class="alert alert-info">
                <strong>Petunjuk Format Excel:</strong><br>
                1. <strong>NO_KK</strong>: Wajib diisi (Format Teks).<br>
                2. <strong>NAMA_DUSUN</strong>: Wajib diisi, ejaan harus sama persis dengan Data Dusun di sistem.<br>
                3. <strong>ALAMAT</strong>: Alamat lengkap jalan/gang.<br>
                4. <strong>RT</strong> & <strong>RW</strong>: Angka/Nomor.<br>
                <br>
                <i class="fas fa-info-circle"></i> <em>Catatan: Anda <strong>TIDAK PERLU</strong> membuat kolom "Kepala
                    Keluarga" di Excel. Sistem akan mendeteksi Kepala Keluarga secara otomatis setelah Anda mengimpor Data
                    Penduduk.</em>
            </div>

            <strong>Format Kolom Excel (Header) yang Dibutuhkan:</strong>
            <ul class="mb-3">
                <li>no_kk</li>
                <li>nama_dusun</li>
                <li>alamat</li>
                <li>rt</li>
                <li>rw</li>
            </ul>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.kk.import.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Pilih File Excel (.xlsx, .xls)</label>
                    <input type="file" class="form-control-file" name="file_excel" required accept=".xlsx, .xls">
                </div>

                <a href="{{ route('kk.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload dan Import</button>
            </form>
        </div>
    </div>
@endsection