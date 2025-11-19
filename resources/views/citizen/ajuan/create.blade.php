@extends('layouts.citizen')

@section('title', 'Buat Ajuan Surat Baru')

@section('content')

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        
        {{-- Tampilkan Error Validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('warga.ajuan.store') }}" method="POST">
            @csrf
            
            <div class="alert alert-info mb-4">
                <h6 class="alert-heading fw-bold"><i class="fas fa-user-circle me-1"></i> Informasi Pemohon</h6>
                <p class="mb-0 small">
                    NIK: <strong>{{ $warga->nik }}</strong> | Nama: <strong>{{ $warga->nama_lengkap }}</strong>
                </p>
            </div>

            {{-- 1. PILIH JENIS SURAT --}}
            <div class="mb-3">
                <label for="id_jenis_surat" class="form-label fw-bold">Pilih Jenis Surat</label>
                <select class="form-select" id="id_jenis_surat" name="id_jenis_surat" required>
                    <option value="">-- Silakan Pilih Surat --</option>
                    @foreach ($jenisSuratList as $surat)
                        <option value="{{ $surat->id_jenis_surat }}">
                            {{ $surat->nama_surat }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ================================================= --}}
            {{-- AREA FORM DINAMIS (Awalnya Tersembunyi / d-none)  --}}
            {{-- ================================================= --}}

            {{-- A. FORM KHUSUS SKU (Usaha) --}}
            <div class="card bg-light border-0 mb-3 d-none extra-form" id="form_sku">
                <div class="card-body">
                    <h6 class="text-primary fw-bold"><i class="fas fa-store me-1"></i> Detail Usaha</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Jenis/Bidang Usaha</label>
                                <input type="text" class="form-control input-extra" name="bidang_usaha" placeholder="Contoh: Kuliner / Pertanian">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Nama Usaha (Opsional)</label>
                                <input type="text" class="form-control input-extra" name="nama_usaha" placeholder="Contoh: Warung Makan Berkah">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Alamat/Lokasi Usaha</label>
                                <input type="text" class="form-control input-extra" name="lokasi_usaha" placeholder="Contoh: Dusun I, Desa Panggulo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- B. FORM KHUSUS SKTM (Miskin) --}}
            <div class="card bg-light border-0 mb-3 d-none extra-form" id="form_sktm">
                <div class="card-body">
                    <h6 class="text-primary fw-bold"><i class="fas fa-wallet me-1"></i> Detail Ekonomi</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Penghasilan Rata-rata (Per Bulan)</label>
                                <input type="text" class="form-control input-extra" name="penghasilan" placeholder="Contoh: Rp 500.000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Jumlah Tanggungan Keluarga</label>
                                <input type="number" class="form-control input-extra" name="jumlah_tanggungan" placeholder="Contoh: 3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- C. FORM KHUSUS KEHILANGAN --}}
            <div class="card bg-light border-0 mb-3 d-none extra-form" id="form_kehilangan">
                <div class="card-body">
                    <h6 class="text-primary fw-bold"><i class="fas fa-search me-1"></i> Detail Kehilangan</h6>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Barang/Dokumen yang Hilang</label>
                        <input type="text" class="form-control input-extra" name="barang_hilang" placeholder="Contoh: KTP, SIM, atau ATM">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Lokasi & Waktu Kejadian</label>
                        <input type="text" class="form-control input-extra" name="lokasi_kehilangan" placeholder="Contoh: Pasar Sentral, Hari Senin jam 10 pagi">
                    </div>
                </div>
            </div>

            {{-- D. FORM KHUSUS KEMATIAN --}}
            <div class="card bg-light border-0 mb-3 d-none extra-form" id="form_kematian">
                <div class="card-body">
                    <h6 class="text-primary fw-bold"><i class="fas fa-book-dead me-1"></i> Detail Kematian</h6>
                        <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Hari Meninggal</label>
                                <input type="text" class="form-control input-extra" name="hari_meninggal" placeholder="Contoh: Senin">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Tanggal Meninggal</label>
                                <input type="date" class="form-control input-extra" name="tgl_meninggal">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Penyebab Kematian</label>
                                <input type="text" class="form-control input-extra" name="penyebab_kematian" placeholder="Contoh: Sakit Tua">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Tempat Meninggal</label>
                                <input type="text" class="form-control input-extra" name="tempat_meninggal" placeholder="Contoh: Rumah Sakit">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================================================= --}}
            {{-- BATAS AKHIR FORM DINAMIS --}}
            {{-- ================================================= --}}

            {{-- 2. KEPERLUAN (Wajib untuk semua surat) --}}
            <div class="mb-3">
                <label for="keperluan" class="form-label fw-bold">
                    Keperluan Surat <span class="text-danger">*</span>
                </label>
                <textarea class="form-control" id="keperluan" name="keperluan" rows="3" 
                          placeholder="Contoh: Persyaratan Melamar Pekerjaan" required>{{ old('keperluan') }}</textarea>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('warga.dashboard') }}" class="btn btn-secondary text-white" style="text-decoration: none;">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-paper-plane me-2"></i>Ajukan Surat
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

{{-- SCRIPT UNTUK MENGATUR FORM DINAMIS --}}
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Saat dropdown berubah
        $('#id_jenis_surat').change(function() {
            
            // Ambil teks pilihan dan ubah ke huruf kecil
            var selectedText = $(this).find("option:selected").text().toLowerCase();

            // 1. Sembunyikan SEMUA form tambahan & Kosongkan isinya
            $('.extra-form').addClass('d-none'); 
            $('.input-extra').val(''); 

            // 2. Cek logika kata kunci
            
            // SKU
            if (selectedText.includes('usaha')) {
                $('#form_sku').removeClass('d-none');
            }
            // SKTM
            else if (selectedText.includes('miskin') || selectedText.includes('tidak mampu') || selectedText.includes('sktm')) {
                $('#form_sktm').removeClass('d-none');
            }
            // Kehilangan
            else if (selectedText.includes('hilang') || selectedText.includes('kehilangan')) {
                $('#form_kehilangan').removeClass('d-none');
            }
            // Kematian
            else if (selectedText.includes('mati') || selectedText.includes('meninggal')) {
                $('#form_kematian').removeClass('d-none');
            }
        });
    });
</script>
@endpush