@extends('layouts.citizen')

@section('title', 'Riwayat Status Surat')

@section('content')

{{-- Tambahkan mt-4 agar tidak menempel ke navbar, dan mb-5 agar tidak menempel ke footer --}}
<div class="row justify-content-center mt-2 mb-5"> 
    <div class="col-lg-12">
        
        {{-- Saya ganti mb-4 menjadi mb-5 agar jarak ke tabel lebih jauh --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="text-muted small mb-0">Daftar seluruh surat yang pernah Anda ajukan.</h4>
            </div>
            <a href="{{ route('warga.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 15px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4 py-4 text-uppercase small fw-bold" width="5%"></th> {{-- py-4 agar header tabel lebih tinggi --}}
                                <th class="text-uppercase small fw-bold" width="20%">Tanggal</th>
                                <th class="text-uppercase small fw-bold" width="35%">Jenis Surat & Keperluan</th>
                                <th class="text-uppercase small fw-bold" width="15%">Status</th>
                                <th class="text-uppercase small fw-bold" width="25%">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($riwayatAjuan as $index => $ajuan)
                                <tr>
                                    <td class="ps-4 text-muted py-3">{{ $loop->iteration }}</td> {{-- py-3 agar baris lebih renggang --}}
                                    
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3 text-primary">
                                                <i class="far fa-calendar-alt"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold d-block text-dark">
                                                    {{ \Carbon\Carbon::parse($ajuan->tanggal_ajuan)->isoFormat('D MMM Y') }}
                                                </span>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($ajuan->tanggal_ajuan)->format('H:i') }} WITA
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-3">
                                        <span class="fw-bold text-dark d-block" style="font-size: 1rem;">
                                            {{ $ajuan->jenisSurat->nama_surat ?? 'Jenis Surat Dihapus' }}
                                        </span>
                                        <div class="text-muted small mt-1">
                                            <i class="fas fa-info-circle me-1 text-secondary"></i> 
                                            {{ Str::limit($ajuan->keperluan, 60) }}
                                        </div>
                                    </td>

                                    <td class="py-3">
                                        @if($ajuan->status == 'BARU')
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                <i class="fas fa-clock me-1"></i> Proses
                                            </span>
                                        @elseif($ajuan->status == 'SELESAI')
                                            <span class="badge bg-success rounded-pill px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i> Selesai
                                            </span>
                                        @elseif($ajuan->status == 'DITOLAK')
                                            <span class="badge bg-danger rounded-pill px-3 py-2">
                                                <i class="fas fa-times-circle me-1"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-3">
                                        @if($ajuan->status == 'SELESAI')
                                            <div class="alert alert-success py-2 px-3 mb-0 border-0 small" style="background-color: #d1e7dd;">
                                                <i class="fas fa-check me-1"></i> Surat diterbitkan.<br>
                                                <strong>No: {{ $ajuan->nomor_surat }}</strong>
                                            </div>
                                        @elseif($ajuan->status == 'DITOLAK')
                                            <div class="alert alert-danger py-2 px-3 mb-0 border-0 small" style="background-color: #f8d7da;">
                                                <i class="fas fa-exclamation-circle me-1"></i> <strong>Alasan:</strong><br>
                                                {{ $ajuan->catatan_penolakan }}
                                            </div>
                                        @else
                                            <span class="text-muted small fst-italic">Menunggu konfirmasi admin...</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted opacity-50 mb-3">
                                            <i class="fas fa-folder-open fa-4x"></i>
                                        </div>
                                        <h6 class="text-muted fw-bold">Belum ada riwayat pengajuan.</h6>
                                        <a href="{{ route('warga.ajuan.create') }}" class="btn btn-primary btn-sm mt-2 shadow-sm">
                                            <i class="fas fa-plus me-1"></i> Buat Ajuan Baru
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection