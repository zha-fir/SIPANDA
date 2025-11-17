@extends('layouts.citizen')

@section('title', 'Buat Ajuan Surat Baru')

@section('content')

<div class="card shadow-sm border-0">
    <div class="card-body p-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('warga.ajuan.store') }}" method="POST">
            @csrf

            <p>
                Data Anda akan diambil secara otomatis berdasarkan akun Anda:
            </p>
            <ul>
                <li><strong>NIK:</strong> {{ $warga->nik }}</li>
                <li><strong>Nama:</strong> {{ $warga->nama_lengkap }}</li>
            </ul>
            <p>Silakan pilih jenis surat yang ingin Anda ajukan di bawah ini.</p>

            <hr>

            <div class="mb-3">
                <label for="id_jenis_surat" class="form-label">
                    <strong>Pilih Jenis Surat:</strong>
                </label>
                <select class="form-select" id="id_jenis_surat" name="id_jenis_surat">
                    <option value="">-- Silakan Pilih --</option>
                    @foreach ($jenisSuratList as $surat)
                        <option value="{{ $surat->id_jenis_surat }}">
                            {{ $surat->nama_surat }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="keperluan" class="form-label">
                    <strong>Keperluan Surat</strong>
                </label>
                <textarea class="form-control" id="keperluan" name="keperluan" rows="3" 
                        placeholder="Contoh: Persyaratan Melamar Pekerjaan">{{ old('keperluan') }}</textarea>
            </div>

            <p class="text-muted small">
                Dengan mengklik "Ajukan Surat", Anda menyatakan bahwa data yang digunakan (data diri Anda) adalah benar dan valid.
            </p>

            <a href="{{ route('warga.dashboard') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane me-2"></i>Ajukan Surat
            </button>
        </form>

    </div>
</div>
@endsection