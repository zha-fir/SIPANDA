@extends('layouts.admin')

@section('title', 'Manajemen Warga (Kependudukan)')

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
        <h6 class="m-0 font-weight-bold text-primary">Data Warga</h6>
    </div>
    <div class="card-body">
        <a href="{{ route('warga.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Data Warga
        </a>
        <a href="{{ route('admin.warga.import.form') }}" class="btn btn-success mb-3 ml-2">
            <i class="fas fa-file-excel"></i> Import Warga .XLS
        </a>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                
                {{-- HEADER HARUS DI LUAR LOOP --}}
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>No. KK</th>
                        <th>Akun Login</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                
                {{-- BODY (ISI DATA) --}}
                <tbody>
                    @forelse ($wargaList as $warga)
                    <tr>
                        <td>{{ $warga->nik }}</td>
                        <td>{{ $warga->nama_lengkap }}</td>
                        <td>
                            {{ $warga->kk->no_kk ?? 'Tidak ada KK' }}
                        </td>
                        <td>
                            @if($warga->user)
                                <span class="badge badge-success">Ada</span>
                            @else
                                <span class="badge badge-secondary">Belum Ada</span> 
                            @endif
                        </td>
                        <td>
                            {{-- Tombol Edit --}}
                            <a href="{{ route('warga.edit', $warga->id_warga) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('warga.destroy', $warga->id_warga) }}" method="POST" 
                                class="d-inline" 
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data warga ini? Akun login yang terhubung (jika ada) juga akan dihapus permanen.');">
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
                        <td colspan="6" class="text-center">Data masih kosong.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div> {{-- PERBAIKAN KEDUA: '}' yang error sudah dihapus dari sini --}}
@endsection