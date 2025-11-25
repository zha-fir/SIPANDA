@extends('layouts.kades')

@section('title', 'Data Penduduk Desa')

@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Seluruh Penduduk</h6>

            <form action="{{ route('kades.penduduk.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small"
                        placeholder="Cari NIK atau Nama..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>Dusun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($wargaList as $warga)
                            <tr>
                                <td>{{ $warga->nik }}</td>
                                <td>{{ $warga->nama_lengkap }}</td>
                                <td>
                                    @if($warga->jenis_kelamin == 'LAKI-LAKI')
                                        <span class="text-primary"><i class="fas fa-male"></i> Laki-laki</span>
                                    @else
                                        <span class="text-danger"><i class="fas fa-female"></i> Perempuan</span>
                                    @endif
                                </td>
                                <td>{{ $warga->kk->dusun->nama_dusun ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('kades.penduduk.show', $warga->id_warga) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-id-card me-1"></i> Detail Profil
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    Data tidak ditemukan. Coba kata kunci lain.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $wargaList->links() }}
            </div>
        </div>
    </div>
@endsection