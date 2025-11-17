@extends('layouts.admin')

@section('title', 'Tambah Jenis Surat')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Jenis Surat</h6>
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

        {{-- INI PENTING: enctype="multipart/form-data" WAJIB ada untuk form upload file --}}
        <form action="{{ route('jenis-surat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="nama_surat">Nama Surat</label>
                <input type="text" class="form-control" id="nama_surat" name="nama_surat" value="{{ old('nama_surat') }}" placeholder="Contoh: Surat Keterangan Usaha">
            </div>

            <div class="form-group">
                <label for="kode_surat">Kode Surat (Opsional)</label>
                <input type="text" class="form-control" id="kode_surat" name="kode_surat" value="{{ old('kode_surat') }}" placeholder="Contoh: SKU">
            </div>

            <div class="form-group">
                <label for="template_file">File Template (.docx)</label>
                <input type="file" class="form-control-file" id="template_file" name="template_file" accept=".docx">
                <small class="form-text text-muted">Upload file template .docx. Maksimal 2MB.</small>
            </div>
            
            <a href="{{ route('jenis-surat.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection