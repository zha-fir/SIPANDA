@extends('layouts.citizen')

@section('title', 'Dashboard')

@section('content')

{{-- Menampilkan pesan sukses --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <!-- Kartu Selamat Datang -->
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h3 class="mb-1">Selamat Datang, {{ $warga->nama_lengkap }}!</h3>
                <p class="text-muted">Ini adalah Halaman Pelayanan mandiri Anda.</p>
                <a href="#" class="btn btn-primary mt-2">
                    <i class="fas fa-file-alt me-2"></i>Mulai Buat Ajuan Surat
                </a>
            </div>
        </div>
    </div>

    <!-- Kartu Data Diri -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Data Diri Anda</h5>
            </div>
            <div class="card-body p-4">
                <dl class="row">
                    <dt class="col-sm-4">NIK</dt>
                    <dd class="col-sm-8">{{ $warga->nik }}</dd>

                    <dt class="col-sm-4">No. KK</dt>
                    <dd class="col-sm-8">{{ $warga->kk->no_kk ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-4">Nama</dt>
                    <dd class="col-sm-8">{{ $warga->nama_lengkap }}</dd>

                    <dt class="col-sm-4">Tempat, Tgl Lahir</dt>
                    <dd class="col-sm-8">
                        {{ $warga->tempat_lahir }}, {{ \Carbon\Carbon::parse($warga->tanggal_lahir)->isoFormat('D MMMM Y') }}
                    </dd>

                    <dt class="col-sm-4">Jenis Kelamin</dt>
                    <dd class="col-sm-8">{{ $warga->jenis_kelamin }}</dd>
                    
                    <dt class="col-sm-4">Pekerjaan</dt>
                    <dd class="col-sm-8">{{ $warga->pekerjaan }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <!-- Kartu Info Alamat -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Info Alamat</h5>
            </div>
            <div class="card-body p-4">
                <dl class="row">
                    <dt class="col-sm-4">Alamat</dt>
                    <dd class="col-sm-8">{{ $warga->kk->alamat_kk ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">RT / RW</dt>
                    <dd class="col-sm-8">{{ $warga->kk->rt ?? 'N/A' }} / {{ $warga->kk->rw ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Dusun</dt>
                    <dd class="col-sm-8">{{ $warga->kk->dusun->nama_dusun ?? 'N/A' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection