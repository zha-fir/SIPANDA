@extends('layouts.citizen')

@section('title', 'Dashboard')

@section('content')

    {{-- Alert Sukses dengan Animasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert"
            style="background-color: #d1e7dd; color: #0f5132;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card text-white shadow-lg border-0"
                style="background: linear-gradient(120deg, #0d6efd 0%, #6610f2 100%); border-radius: 20px;">
                <div class="card-body p-5 position-relative overflow-hidden">
                    <div
                        style="position: absolute; top: -20px; right: -20px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;">
                    </div>
                    <div
                        style="position: absolute; bottom: -40px; right: 50px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;">
                    </div>

                    <h2 class="fw-bold">Halo, {{ $warga->nama_lengkap }}! 👋</h2>
                    <p class="lead mb-4" style="opacity: 0.9;">Selamat datang di SIPANDA, Sistem Pelayanan Administrasi Desa
                        Panggulo.</p>
                    <a href="{{ route('warga.ajuan.create') }}"
                        class="btn btn-light text-primary fw-bold px-4 py-2 shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i>Buat Ajuan Baru
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 h-100 card-hover">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-history me-2 text-primary"></i>Status Ajuan Terakhir
                    </h6>
                    <a href="{{ route('warga.ajuan.history') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="ps-4 py-3" width="40%">Jenis Surat</th>
                                    <th width="30%">Tanggal</th>
                                    <th width="30%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatAjuan as $ajuan)
                                    <tr>
                                        {{-- KOLOM 1: JENIS SURAT --}}
                                        <td class="ps-4">
                                            <span class="fw-bold text-dark d-block">
                                                {{ $ajuan->jenisSurat->nama_surat ?? 'Jenis Surat Telah Dihapus' }}
                                            </span>
                                            {{-- Menampilkan sedikit preview keperluan --}}
                                            <small class="text-muted" style="font-size: 0.8rem;">
                                                {{ Str::limit($ajuan->keperluan, 30) }}
                                            </small>
                                        </td>

                                        {{-- KOLOM 2: TANGGAL --}}
                                        <td>
                                            <div class="text-muted small">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ \Carbon\Carbon::parse($ajuan->tanggal_ajuan)->isoFormat('dddd, D MMMM Y') }}
                                            </div>
                                        </td>

                                        {{-- KOLOM 3: STATUS --}}
                                        <td>
                                            @if($ajuan->status == 'BARU')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                    <i class="fas fa-clock me-1"></i> Proses
                                                </span>
                                            @elseif($ajuan->status == 'SELESAI')
                                                <span class="badge bg-success rounded-pill px-3 py-2">
                                                    <i class="fas fa-check me-1"></i> Selesai
                                                </span>
                                            @elseif($ajuan->status == 'DITOLAK')
                                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                                    <i class="fas fa-times me-1"></i> Ditolak
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <div class="mb-2">
                                                <i class="fas fa-inbox fa-3x opacity-25"></i>
                                            </div>
                                            <p class="mb-0">Belum ada pengajuan surat.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 h-100 card-hover">
                <div class="card-body text-center p-4">
                    <div class="mb-3 position-relative d-inline-block">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                            style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ substr($warga->nama_lengkap, 0, 1) }}
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $warga->nama_lengkap }}</h5>
                    <p class="text-muted small mb-3">{{ $warga->nik }}</p>

                    <hr class="my-4 opacity-10">

                    <div class="text-start">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded p-2 me-3 text-primary"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <small class="text-muted d-block">Dusun</small>
                                <span class="fw-bold">{{ $warga->kk->dusun->nama_dusun ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded p-2 me-3 text-success"><i class="fas fa-briefcase"></i></div>
                            <div>
                                <small class="text-muted d-block">Pekerjaan</small>
                                <span class="fw-bold">{{ $warga->pekerjaan }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection