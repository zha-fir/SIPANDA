@extends('layouts.admin')
@section('title', 'Pengajuan Surat Masuk')
@section('content')

    {{-- Tampilkan Pesan Sukses/Error --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div> @endif

    {{-- Tampilkan Error Validasi (jika modal gagal) --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            Gagal memproses ajuan. Pastikan semua field terisi.
            <ul>
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif


    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengajuan (Status: BARU)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <th>Pemohon (NIK)</th>
                            <th>Jenis Surat</th>
                            <th>Keperluan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ajuanList as $ajuan)
                            <tr>
                                <td>{{ $ajuan->tanggal_ajuan }}</td>
                                <td>
                                    {{ $ajuan->warga->nama_lengkap ?? 'N/A' }}
                                    <br><small>NIK: {{ $ajuan->warga->nik ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $ajuan->jenisSurat->nama_surat ?? 'N/A' }}</td>
                                <td>{{ $ajuan->keperluan }}</td>
                                <td>
                                    {{-- Tombol Pemicu Modal Konfirmasi --}}
                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                        data-target="#konfirmasiModal" data-id="{{ $ajuan->id_ajuan }}"
                                        data-nama="{{ $ajuan->warga->nama_lengkap ?? '' }}"
                                        data-nik="{{ $ajuan->warga->nik ?? '' }}"
                                        data-jenis-surat="{{ $ajuan->jenisSurat->nama_surat ?? '' }}"
                                        data-keperluan="{{ $ajuan->keperluan }}"> {{-- PASTIKAN BARIS INI ADA --}}
                                        <i class="fas fa-check"></i> Konfirmasi
                                    </button>

                                    {{-- Tombol Pemicu Modal Tolak --}}
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#tolakModal" data-id="{{ $ajuan->id_ajuan }}">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada ajuan surat baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="konfirmasiModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="formKonfirmasi" action="" method="POST"> {{-- Action diisi JS --}}
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Surat Keterangan</h5>
                        <button class="close" type="button" data-dismiss="modal"><span>×</span></button>
                    </div>
                    <div class="modal-body">
                        <p>Anda akan mengonfirmasi ajuan surat untuk:</p>
                        <ul>
                            <li><strong>Nama:</strong> <span id="modalNama"></span></li>
                            <li><strong>NIK:</strong> <span id="modalNik"></span></li>
                            <li><strong>Jenis Surat:</strong> <span id="modalJenisSurat"></span></li>
                            <li><strong>Keperluan:</strong> <span id="modalKeperluan"></span></li>
                        </ul>
                        <hr>
                        <p>Silakan isi detail surat resmi di bawah ini:</p>
                        <div class="form-group">
                            <label for="nomor_surat">No. Surat (Resmi)</label>
                            <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" required
                                placeholder="Contoh: PLB/2025/XI/123">
                        </div>
                        {{-- Dropdown Pejabat 1 (Yang sudah ada) --}}
                        <div class="form-group">
                            <label for="id_pejabat_desa">Pejabat Penandatangan 1 (Utama/Kanan)</label>
                            <select class="form-control" id="id_pejabat_desa" name="id_pejabat_desa" required>
                                <option value="">-- PILIH PEJABAT 1 --</option>
                                @foreach ($pejabatList as $pejabat)
                                    <option value="{{ $pejabat->id_pejabat_desa }}">
                                        {{ $pejabat->nama_pejabat }} ({{ $pejabat->jabatan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dropdown Pejabat 2 (BARU) --}}
                        <div class="form-group">
                            <label for="id_pejabat_desa_2">Pejabat Penandatangan 2 (Opsional/Kiri)</label>
                            <select class="form-control" id="id_pejabat_desa_2" name="id_pejabat_desa_2">
                                <option value="">-- KOSONGKAN JIKA TIDAK PERLU --</option>
                                @foreach ($pejabatList as $pejabat)
                                    <option value="{{ $pejabat->id_pejabat_desa }}">
                                        {{ $pejabat->nama_pejabat }} ({{ $pejabat->jabatan }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Biasanya untuk Kepala Dusun atau mengetahui Camat.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Konfirmasi & Simpan ke Arsip</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tolakModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formTolak" action="" method="POST"> {{-- Action diisi JS --}}
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Ajuan Surat</h5>
                        <button class="close" type="button" data-dismiss="modal"><span>×</span></button>
                    </div>
                    <div class="modal-body">
                        <p>Anda yakin ingin menolak ajuan ini? Berikan alasan penolakan (wajib).</p>
                        <div class="form-group">
                            <label for="catatan_penolakan">Alasan Penolakan</label>
                            <textarea class="form-control" name="catatan_penolakan" rows="3" required></textarea>
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
@endsection

@push('scripts')
    {{-- Script untuk mengisi data-data ke Modal --}}
    <script>
        $(document).ready(function () {
            // Script untuk Modal Konfirmasi
            $('#konfirmasiModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var ajuanId = button.data('id');
                var nama = button.data('nama');
                var nik = button.data('nik');
                var keperluan = button.data('keperluan');
                var jenisSurat = button.data('jenis-surat');

                var modal = $(this);
                var actionUrl = "{{ url('admin/ajuan-surat') }}/" + ajuanId + "/konfirmasi";

                modal.find('#formKonfirmasi').attr('action', actionUrl);
                modal.find('#modalNama').text(nama);
                modal.find('#modalNik').text(nik);
                modal.find('#modalKeperluan').text(keperluan);
                modal.find('#modalJenisSurat').text(jenisSurat);
            });

            // Script untuk Modal Tolak
            $('#tolakModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var ajuanId = button.data('id');
                var actionUrl = "{{ url('admin/ajuan-surat') }}/" + ajuanId + "/tolak";

                var modal = $(this);
                modal.find('#formTolak').attr('action', actionUrl);
            });
        });
    </script>
@endpush