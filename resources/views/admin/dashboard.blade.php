@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Penduduk</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahWarga }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kartu Keluarga</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahKK }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-home fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Perlu Diproses</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ajuanBaru }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-inbox fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Surat Selesai</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $suratSelesai }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-signature fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-8 mb-4">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-info-circle me-2"></i> Informasi Sistem</h6>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    
                    <div class="col-auto pr-4"> {{-- pr-4 memberi jarak kanan --}}
                        {{-- Pastikan file gambar ada di public/img/logo.png --}}
                        <img src="{{ asset('img/Logo_Kabupaten.png') }}" alt="Logo Kabupaten" style="width: 80px; height: auto;">
                    </div>

                    <div class="col border-left pl-4"> {{-- border-left memberi garis pemisah --}}
                        <p class="mb-1">Selamat datang di <strong>SIPANDA</strong> (Sistem Pelayanan Administrasi Desa).</p>
                        <p class="mb-2 text-muted small">Desa Panggulo, Kabupaten Bone Bolango.</p>
                        
                        <hr class="my-2">
                        
                        <p class="mb-0 small font-weight-bold">Panduan Singkat:</p>
                        <ul class="mt-1 mb-0 small pl-3">
                            <li>Cek kelengkapan data sebelum konfirmasi.</li>
                            <li>Lakukan backup arsip secara berkala.</li>
                            <li>Jaga kerahasiaan akun.</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                {{-- Ganti Judul jadi 3 --}}
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history me-2"></i> Ajuan Surat Terakhir</h6>
                <a href="{{ route('ajuan-surat.index') }}" class="btn btn-sm btn-primary shadow-sm">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-4">Tanggal</th>
                                <th>Nama Warga</th>
                                <th>Jenis Surat</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestAjuan as $ajuan)
                            <tr>
                                <td class="pl-4 text-muted small">
                                    {{ \Carbon\Carbon::parse($ajuan->tanggal_ajuan)->diffForHumans() }}
                                </td>
                                <td class="font-weight-bold">{{ $ajuan->warga->nama_lengkap ?? '-' }}</td>
                                <td>{{ $ajuan->jenisSurat->nama_surat ?? '-' }}</td>
                                <td class="text-center">
                                    @if($ajuan->status == 'BARU')
                                        <span class="badge badge-warning px-2">Baru</span>
                                    @elseif($ajuan->status == 'SELESAI')
                                        <span class="badge badge-success px-2">Selesai</span>
                                    @else
                                        <span class="badge badge-danger px-2">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada aktivitas surat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-4 mb-4">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-pie me-2"></i> Demografi Penduduk</h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">Laki-laki <span class="float-right">{{ $persenLaki }}%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $persenLaki }}%"></div>
                </div>
                <h4 class="small font-weight-bold">Perempuan <span class="float-right">{{ $persenPerempuan }}%</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $persenPerempuan }}%"></div>
                </div>
                <hr>
                <div class="text-center small text-muted">
                    Total Laki-laki: <strong>{{ $wargaLaki }}</strong> | Total Perempuan: <strong>{{ $wargaPerempuan }}</strong>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-bolt me-2"></i> Akses Cepat</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('warga.create') }}" class="btn btn-success btn-icon-split btn-block mb-2 align-items-start justify-content-start">
                    <span class="icon text-white-50"><i class="fas fa-user-plus"></i></span>
                    <span class="text text-left">Tambah Warga Baru</span>
                </a>
                <a href="{{ route('kk.create') }}" class="btn btn-info btn-icon-split btn-block mb-2 align-items-start justify-content-start">
                    <span class="icon text-white-50"><i class="fas fa-users"></i></span>
                    <span class="text text-left">Buat Kartu Keluarga</span>
                </a>
                <a href="{{ route('jenis-surat.index') }}" class="btn btn-secondary btn-icon-split btn-block align-items-start justify-content-start">
                    <span class="icon text-white-50"><i class="fas fa-file-word"></i></span>
                    <span class="text text-left">Kelola Template Surat</span>
                </a>
            </div>
        </div>

    </div>
</div>

@endsection