@extends('layouts.kadus')

@section('title', 'Data Penduduk Saya')

@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Penduduk</h6>

            <form action="{{ route('kadus.warga') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small"
                        placeholder="Cari Nama / NIK..." value="{{ request('search') }}">
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
                            <th>Alamat (RT/RW)</th>
                            <th>Pekerjaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($wargaList as $warga)
                            <tr>
                                <td>{{ $warga->nik }}</td>
                                <td>
                                    <strong>{{ $warga->nama_lengkap }}</strong>
                                    <br><small class="text-muted">TTL: {{ $warga->tempat_lahir }},
                                        {{ $warga->tanggal_lahir }}</small>
                                </td>
                                <td>
                                    @if($warga->jenis_kelamin == 'LAKI-LAKI')
                                        <span class="text-primary"><i class="fas fa-male"></i> Laki-laki</span>
                                    @else
                                        <span class="text-pink"><i class="fas fa-female"></i> Perempuan</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $warga->kk->alamat_kk ?? '-' }}
                                    <br>
                                    <small>RT {{ $warga->kk->rt ?? '-' }} / RW {{ $warga->kk->rw ?? '-' }}</small>
                                </td>
                                <td>{{ $warga->pekerjaan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-users fa-2x mb-3 d-block"></i>
                                    Data warga tidak ditemukan di dusun ini.
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