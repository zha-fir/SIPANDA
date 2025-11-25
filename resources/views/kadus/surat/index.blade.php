@extends('layouts.kadus')

@section('title', 'Monitoring Surat Penduduk')

@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Pengajuan Surat</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Pemohon</th>
                            <th>Jenis Surat</th>
                            <th>Status Terkini</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ajuanList as $ajuan)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($ajuan->tanggal_ajuan)->isoFormat('D MMMM Y') }}
                                    <br><small
                                        class="text-muted">{{ \Carbon\Carbon::parse($ajuan->tanggal_ajuan)->format('H:i') }}
                                        WITA</small>
                                </td>
                                <td>
                                    <strong>{{ $ajuan->warga->nama_lengkap }}</strong>
                                    <br><small>NIK: {{ $ajuan->warga->nik }}</small>
                                </td>
                                <td>
                                    <span
                                        class="font-weight-bold text-dark">{{ $ajuan->jenisSurat->nama_surat ?? 'Jenis Surat Dihapus' }}</span>
                                    <br>
                                    <small class="text-muted">Keperluan: {{ Str::limit($ajuan->keperluan, 30) }}</small>
                                </td>
                                <td>
                                    @if($ajuan->status == 'BARU')
                                        <span class="badge badge-warning px-2 py-1">
                                            <i class="fas fa-clock"></i> Sedang Diproses Admin
                                        </span>
                                    @elseif($ajuan->status == 'SELESAI')
                                        <span class="badge badge-success px-2 py-1">
                                            <i class="fas fa-check-circle"></i> Selesai
                                        </span>
                                        <div class="small text-success mt-1">No: {{ $ajuan->nomor_surat }}</div>
                                    @elseif($ajuan->status == 'DITOLAK')
                                        <span class="badge badge-danger px-2 py-1">
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        </span>
                                        <div class="small text-danger mt-1">Alasan: {{ $ajuan->catatan_penolakan }}</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Belum ada aktivitas pengajuan surat dari warga di dusun ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $ajuanList->links() }}
            </div>
        </div>
    </div>
@endsection