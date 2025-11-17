@extends('layouts.admin')

@section('title', 'Edit Data Dusun')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Dusun</h6>
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

        {{-- Form di-submit ke route 'dusun.update' --}}
        <form action="{{ route('dusun.update', $dusun->id_dusun) }}" method="POST">
            @csrf 
            @method('PUT') {{-- PENTING: Method 'PUT' untuk update --}}
            
            <div class="form-group">
                <label for="nama_dusun">Nama Dusun</label>
                {{-- Tampilkan data lama di 'value' --}}
                <input type="text" 
                       class="form-control" 
                       id="nama_dusun" 
                       name="nama_dusun" 
                       value="{{ old('nama_dusun', $dusun->nama_dusun) }}">
            </div>
            
            <a href="{{ route('dusun.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection