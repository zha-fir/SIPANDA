@extends('layouts.admin')

@section('title', 'Manajemen Jenis Surat')

@section('content')

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
        <h6 class="m-0 font-weight-bold text-primary">Data Jenis Surat</h6>
    </div>
    <div class="card-body">
        <a href="{{ route('jenis-surat.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Jenis Surat
        </a>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Surat</th>
                        <th>Kode Surat</th>
                        <th>File Template</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suratList as $surat)
                    <tr>
                        <td>{{ $surat->nama_surat }}</td>
                        <td>{{ $surat->kode_surat }}</td>
                        <td>{{ $surat->template_file }}</td>
                        <td>
                            <a href="{{ route('jenis-surat.edit', $surat->id_jenis_surat) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('jenis-surat.destroy', $surat->id_jenis_surat) }}" method="POST" 
                                class="d-inline" 
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus template surat ini?');">
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
                        <td colspan="4" class="text-center">Data masih kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection