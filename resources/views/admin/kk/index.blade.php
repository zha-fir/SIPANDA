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

            {{-- BARIS TOMBOL & PENCARIAN --}}
            <div class="d-flex justify-content-between align-items-center mb-3">

                {{-- Kiri: Tombol Aksi --}}
                <div>
                    <a href="{{ route('kk.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah KK
                    </a>
                    <a href="{{ route('admin.kk.import.form') }}" class="btn btn-success ml-2">
                        <i class="fas fa-file-excel"></i> Import Excel
                    </a>
                </div>

                {{-- Kanan: Form Pencarian --}}
                <form action="{{ route('kk.index') }}" method="GET" class="form-inline">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-light border-0 small"
                            placeholder="Cari No. KK / Nama Kepala..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" onclick="this.form.submit()">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                            @if(request('search'))
                                <a href="{{ route('kk.index') }}" class="btn btn-secondary" title="Reset">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

            </div>
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
                                <td>
                                    @if($kk->kepalaKeluarga)
                                        {{-- Jika ada warga yang statusnya KEPALA KELUARGA, tampilkan datanya --}}
                                        <strong>{{ $kk->kepalaKeluarga->nama_lengkap }}</strong><br>
                                        <small class="text-muted">NIK: {{ $kk->kepalaKeluarga->nik }}</small>
                                    @else
                                        {{-- Fallback ke teks manual jika belum di-set --}}
                                        {{ $kk->nama_kepala_keluarga }} <span class="text-danger">(Data Belum Link)</span>
                                    @endif
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
                                    <form action="{{ route('kk.destroy', $kk->id_kk) }}" method="POST" class="d-inline"
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
                {{-- Pagination Links --}}
                <div class="mt-3">
                    {{ $kkList->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection