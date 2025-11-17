@extends('layouts.admin')

@section('title', 'Arsip Surat Selesai & Ditolak')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Arsip Surat</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal Ajuan</th>
                        <th>Nomor Surat</th>
                        <th>Pemohon</th>
                        <th>Jenis Surat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($arsipList as $arsip)
                    <tr>
                        <td>{{ $arsip->tanggal_ajuan }}</td>
                        <td>{{ $arsip->nomor_surat_lengkap ?? 'N/A' }}</td>
                        <td>{{ $arsip->warga->nama_lengkap ?? 'Warga Terhapus' }}</td>
                        <td>{{ $arsip->jenisSurat->nama_surat ?? 'Jenis Surat Terhapus' }}</td>
                        <td>
                            @if($arsip->status == 'SELESAI')
                                <span class="badge badge-success">SELESAI</span>
                            @else
                                <span class="badge badge-danger">DITOLAK</span>
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada surat di arsip.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection