@extends('layouts.admin')

@section('title', 'Ajuan Surat Masuk')

@section('content')

{{-- TAMBAHKAN BLOK INI UNTUK MENANGKAP ERROR --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error:</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- Ini adalah kode untuk 'success' (sudah ada) --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        {{-- ... tombol close ... --}}
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Ajuan Surat (Status: BARU)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal Ajuan</th>
                        <th>Pemohon (NIK)</th>
                        <th>Jenis Surat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ajuanList as $ajuan)
                    <tr>
                        <td>{{ $ajuan->tanggal_ajuan }}</td>
                        <td>
                            {{ $ajuan->warga->nama_lengkap ?? 'Warga Terhapus' }}
                            <br>
                            <small>NIK: {{ $ajuan->warga->nik ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $ajuan->jenisSurat->nama_surat ?? 'Jenis Surat Terhapus' }}</td>
                        <td>
                            {{-- Tombol Proses --}}
                            {{-- Tombol Proses (BARU dengan auto-refresh) --}}
                            <a href="{{ route('ajuan-surat.proses', $ajuan->id_ajuan) }}" 
                            class="btn btn-sm btn-success"
                            onclick="setTimeout(function() { location.reload(); }, 2000)"> 
                                <i class="fas fa-cogs"></i> Proses & Generate
                            </a>
                            
                            {{-- Tombol Tolak (menggunakan modal) --}}
                            <button type="button" class="btn btn-sm btn-danger" 
                                    data-toggle="modal" 
                                    data-target="#tolakModal" 
                                    data-id="{{ $ajuan->id_ajuan }}">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada ajuan surat baru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- Modal untuk Konfirmasi Penolakan --}}
<div class="modal fade" id="tolakModal" tabindex="-1" role="dialog" aria-labelledby="tolakModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tolakModalLabel">Tolak Ajuan Surat</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formTolak" action="" method="POST"> {{-- Action-nya akan diisi oleh JS --}}
                @csrf
                <div class="modal-body">
                    <p>Anda yakin ingin menolak ajuan surat ini? Berikan alasan penolakan (opsional namun disarankan).</p>
                    <div class="form-group">
                        <label for="catatan_penolakan">Alasan Penolakan</label>
                        <textarea class="form-control" name="catatan_penolakan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Tolak Ajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Tambahkan script di bawah (wajib ada jQuery) --}}
@push('scripts')
<script>
    // Script untuk mengisi action form modal tolak
    $('#tolakModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang memicu modal
        var ajuanId = button.data('id'); // Ambil data-id dari tombol
        
        // Buat URL action-nya
        var actionUrl = "{{ url('admin/ajuan-surat') }}/" + ajuanId + "/tolak";
        
        // Set action form
        var modal = $(this);
        modal.find('#formTolak').attr('action', actionUrl);
    });
</script>
@endpush
@endsection