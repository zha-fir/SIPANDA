@extends('layouts.admin')

@section('title', 'Edit Data KK')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Kartu Keluarga</h6>
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

        <form action="{{ route('kk.update', $kk->id_kk) }}" method="POST">
            @csrf
            @method('PUT') {{-- PENTING: Method 'PUT' untuk update --}}
            
            <div class="form-group">
                <label for="no_kk">Nomor KK (16 Digit)</label>
                <input type="text" class="form-control" id="no_kk" name="no_kk" value="{{ old('no_kk', $kk->no_kk) }}">
            </div>

            <div class="form-group">
                <label for="nama_kepala_keluarga">Nama Kepala Keluarga</label>
                <input type="text" class="form-control" id="nama_kepala_keluarga" name="nama_kepala_keluarga" value="{{ old('nama_kepala_keluarga', $kk->nama_kepala_keluarga) }}">
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="id_dusun">Pilih Dusun</label>
                        <select class="form-control" id="id_dusun" name="id_dusun">
                            <option value="">-- Pilih Dusun --</option>
                            @foreach ($dusunList as $dusun)
                                <option value="{{ $dusun->id_dusun }}" 
                                    {{-- Pilih dusun yang sesuai dengan data lama --}}
                                    {{ old('id_dusun', $kk->id_dusun) == $dusun->id_dusun ? 'selected' : '' }}>
                                    {{ $dusun->nama_dusun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="rt">RT</label>
                        <input type="text" class="form-control" id="rt" name="rt" value="{{ old('rt', $kk->rt) }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="rw">RW</label>
                        <input type="text" class="form-control" id="rw" name="rw" value="{{ old('rw', $kk->rw) }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="alamat_kk">Alamat Lengkap (Sesuai KK)</label>
                <textarea class="form-control" id="alamat_kk" name="alamat_kk" rows="3">{{ old('alamat_kk', $kk->alamat_kk) }}</textarea>
            </div>
            
            <a href="{{ route('kk.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection