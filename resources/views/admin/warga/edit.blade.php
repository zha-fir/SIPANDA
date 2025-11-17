@extends('layouts.admin')

@section('title', 'Edit Data Warga')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Warga</h6>
    </div>
    <div class="card-body">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('warga.update', $warga->id_warga) }}" method="POST">
            @csrf
            @method('PUT') {{-- PENTING: Method 'PUT' --}}
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nik">NIK (16 Digit)</label>
                        {{-- Menggunakan data lama: $warga->nik --}}
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $warga->nik) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        {{-- Menggunakan data lama: $warga->nama_lengkap --}}
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $warga->nama_lengkap) }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="id_kk">Pilih Kartu Keluarga (KK)</label>
                <select class="form-control" id="id_kk" name="id_kk">
                    <option value="">-- Pilih KK --</option>
                    @foreach ($kkList as $kk)
                        <option value="{{ $kk->id_kk }}" 
                            {{-- Memilih KK yang sesuai dengan data lama: $warga->id_kk --}}
                            {{ old('id_kk', $warga->id_kk) == $kk->id_kk ? 'selected' : '' }}>
                            {{ $kk->no_kk }} - {{ $kk->nama_kepala_keluarga }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- INI ADALAH BAGIAN YANG HILANG (SISA FORM) --}}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        {{-- Menggunakan data lama: $warga->tempat_lahir --}}
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $warga->tempat_lahir) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        {{-- Menggunakan data lama: $warga->tanggal_lahir --}}
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $warga->tanggal_lahir) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                            <option value="">-- Pilih --</option>
                            {{-- Memilih jenis kelamin yang sesuai data lama: $warga->jenis_kelamin --}}
                            <option value="LAKI-LAKI" {{ old('jenis_kelamin', $warga->jenis_kelamin) == 'LAKI-LAKI' ? 'selected' : '' }}>LAKI-LAKI</option>
                            <option value="PEREMPUAN" {{ old('jenis_kelamin', $warga->jenis_kelamin) == 'PEREMPUAN' ? 'selected' : '' }}>PEREMPUAN</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="agama">Agama</label>
                        {{-- Menggunakan data lama: $warga->agama --}}
                        <input type="text" class="form-control" id="agama" name="agama" value="{{ old('agama', $warga->agama) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status_perkawinan">Status Perkawinan</label>
                        {{-- Menggunakan data lama: $warga->status_perkawinan --}}
                        <input type="text" class="form-control" id="status_perkawinan" name="status_perkawinan" value="{{ old('status_perkawinan', $warga->status_perkawinan) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pekerjaan">Pekerjaan</label>
                        {{-- Menggunakan data lama: $warga->pekerjaan --}}
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan', $warga->pekerjaan) }}">
                    </div>
                </div>
            </div>

             <div class="form-group">
                <label for="kewarganegaraan">Kewarganegaraan</label>
                {{-- Menggunakan data lama: $warga->kewarganegaraan --}}
                <input type="text" class="form-control" id="kewarganegaraan" name="kewarganegaraan" value="{{ old('kewarganegaraan', $warga->kewarganegaraan) }}">
            </div>

            {{-- AKHIR DARI BAGIAN YANG HILANG --}}
            
            <hr>
            
            {{-- Bagian Akun --}}
            @if($warga->user)
                <h5><i class="fas fa-key"></i> Reset Password Akun</h5>
                <p class="text-muted">
                    Warga ini memiliki akun login (Username: <strong>{{ $warga->user->username }}</strong>).
                    Centang kotak di bawah untuk me-reset passwordnya.
                </p>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="reset_password" id="reset_password">
                    <label class="form-check-label" for="reset_password">
                        Ya, reset password warga ini
                    </label>
                </div>
                <div class="form-group">
                    <label for="password">Password Baru (Default: 123456)</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" value="123456">
                </div>
            @else
                <h5><i class="fas fa-key"></i> Buat Akun Login</h5>
                <p class="text-danger">Warga ini belum memiliki akun login. Gunakan fitur "Import Warga" untuk membuat akun secara otomatis.</p>
            @endif
            
            <a href="{{ route('warga.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection