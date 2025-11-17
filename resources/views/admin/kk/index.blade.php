@extends('layouts.admin')

@section('title', 'Manajemen Kartu Keluarga (KK)')

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
        <h6 class="m-0 font-weight-bold text-primary">Data Kartu Keluarga</h6>
    </div>
    <div class="card-body">
        <a href="{{ route('kk.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Data KK
        </a>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nomor KK</th>
                        <th>Kepala Keluarga</th>
                        <th>Dusun</th>
                        <th>RT/RW</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kkList as $kk)
                    <tr>
                        <td>{{ $kk->no_kk }}</td>
                        <td>{{ $kk->nama_kepala_keluarga }}</td>
                        <td>
                            {{-- Kita panggil relasi 'dusun' yang ada di Model KK --}}
                            {{-- Tanda '??' adalah null-safe operator, 
                                 jika data dusun tidak ada, tampilkan 'Belum Diatur' --}}
                            {{ $kk->dusun->nama_dusun ?? 'Belum Diatur' }}
                        </td>
                        <td>{{ $kk->rt }}/{{ $kk->rw }}</td>
                        {{-- ... (Di dalam loop @forelse) ... --}}
                        <td>
                            <a href="{{ route('kk.members', $kk->id_kk) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-users"></i> Anggota
                            </a>
                            
                            {{-- 1. Perbaiki link Edit --}}
                            <a href="{{ route('kk.edit', $kk->id_kk) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            {{-- 2. Ubah link Hapus menjadi Form --}}
                            <form action="{{ route('kk.destroy', $kk->id_kk) }}" method="POST" 
                                class="d-inline" 
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data KK ini? SEMUA warga yang terhubung juga akan terpengaruh.');">
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
                        <td colspan="5" class="text-center">Data masih kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection