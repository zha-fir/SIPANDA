@extends('layouts.admin')

@section('title', 'Anggota Kartu Keluarga')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Daftar Anggota KK: {{ $kk->no_kk }}
        </h6>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>Kepala Keluarga:</strong> {{ $kk->nama_kepala_keluarga }} <br>
            <strong>Alamat:</strong> {{ $kk->alamat_kk }} (RT {{ $kk->rt }}/RW {{ $kk->rw }}) <br>
            <strong>Dusun:</strong> {{ $kk->dusun->nama_dusun ?? 'N/A' }}
        </div>

        <a href="{{ route('kk.index') }}" class="btn btn-secondary btn-sm mb-3">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar KK
        </a>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>Tempat, Tgl Lahir</th>
                        <th>Pekerjaan</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop data warga yang ada di dalam $kk --}}
                    @forelse ($kk->warga as $warga)
                    <tr>
                        <td>{{ $warga->nik }}</td>
                        <td>{{ $warga->nama_lengkap }}</td>
                        <td>{{ $warga->jenis_kelamin }}</td>
                        <td>{{ $warga->tempat_lahir }}, {{ $warga->tanggal_lahir ? \Carbon\Carbon::parse($warga->tanggal_lahir)->isoFormat('D MMM Y') : '' }}</td>
                        <td>{{ $warga->pekerjaan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada anggota keluarga yang terdaftar di KK ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection