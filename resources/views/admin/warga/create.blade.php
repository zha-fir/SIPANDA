@extends('layouts.admin')

@section('title', 'Tambah Data Warga')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Warga</h6>
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

        <form action="{{ route('warga.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nik">NIK (16 Digit)</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="id_kk">Pilih Kartu Keluarga (KK)</label>
                <select class="form-control" id="id_kk" name="id_kk">
                    <option value="">-- Pilih KK --</option>
                    @foreach ($kkList as $kk)
                        <option value="{{ $kk->id_kk }}" 
                            {{ old('id_kk') == $kk->id_kk ? 'selected' : '' }}>
                            {{ $kk->no_kk }} - {{ $kk->nama_kepala_keluarga }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                            <option value="">-- Pilih --</option>
                            <option value="LAKI-LAKI" {{ old('jenis_kelamin') == 'LAKI-LAKI' ? 'selected' : '' }}>LAKI-LAKI</option>
                            <option value="PEREMPUAN" {{ old('jenis_kelamin') == 'PEREMPUAN' ? 'selected' : '' }}>PEREMPUAN</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="agama">Agama</label>
                        <input type="text" class="form-control" id="agama" name="agama" value="{{ old('agama') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status_perkawinan">Status Perkawinan</label>
                        <input type="text" class="form-control" id="status_perkawinan" name="status_perkawinan" value="{{ old('status_perkawinan') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pekerjaan">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan') }}">
                    </div>
                </div>
            </div>

             <div class="form-group">
                <label for="kewarganegaraan">Kewarganegaraan</label>
                <input type="text" class="form-control" id="kewarganegaraan" name="kewarganegaraan" value="{{ old('kewarganegaraan', 'WNI') }}">
            </div>
            {{-- TAMBAHKAN INFO INI --}}
            <hr>
            <div class="alert alert-info small">
                <i class="fas fa-info-circle"></i> 
                <strong>Catatan:</strong> Akun login akan dibuat secara otomatis.
                <ul>
                    <li><strong>Username:</strong> Akan diisi sesuai NIK</li>
                    <li><strong>Password Default:</strong> 123456</li>
                </ul>
            </div>
            
            <a href="{{ route('warga.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection