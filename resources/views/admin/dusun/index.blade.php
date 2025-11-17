{{-- Mewarisi template admin SBAdmin2 --}}
@extends('layouts.admin')

{{-- Mengisi title --}}
@section('title', 'Manajemen Data Dusun')

{{-- Mengisi konten --}}
@section('content')

{{-- Menampilkan pesan sukses (jika ada setelah proses 'store') --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Dusun</h6>
    </div>
    <div class="card-body">
        <a href="{{ route('dusun.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Data Dusun
        </a>
        
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Dusun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop data dusunList dari Controller --}}
                    @forelse ($dusunList as $dusun)
                    <tr>
                        {{-- PASTIKAN DUA BARIS INI ADA --}}
                        <td>{{ $dusun->id_dusun }}</td>
                        <td>{{ $dusun->nama_dusun }}</td>

                        {{-- Ini adalah kode untuk Tombol Aksi --}}
                        <td>
                            <a href="{{ route('dusun.edit', $dusun->id_dusun) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <form action="{{ route('dusun.destroy', $dusun->id_dusun) }}" method="POST" 
                                class="d-inline" 
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Data masih kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</div>
@endsection