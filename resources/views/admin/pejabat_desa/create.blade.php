@extends('layouts.admin')

@section('title', 'Tambah Pejabat Desa')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">

            {{-- Tampilkan Error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pejabat-desa.store') }}" method="POST">
                @csrf

                <h5 class="text-primary font-weight-bold">Data Pejabat (Untuk Tanda Tangan)</h5>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Nama Lengkap (beserta gelar)</label>
                        <input type="text" class="form-control" id="nama_pejabat" name="nama_pejabat"
                            value="{{ old('nama_pejabat') }}" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" name="jabatan" value="{{ old('jabatan') }}"
                            placeholder="Contoh: KEPALA DUSUN MAWAR" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>NIP (Opsional)</label>
                        <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Tanggal Lahir (Untuk Hitung Umur)</label>
                        <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                    </div>
                </div>

                <hr>

                {{-- BAGIAN BUAT AKUN --}}
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="buat_akun" id="buat_akun" {{ old('buat_akun') ? 'checked' : '' }}>
                    <label class="form-check-label font-weight-bold text-success" for="buat_akun">
                        <i class="fas fa-user-plus mr-1"></i> Sekaligus Buat Akun Login untuk Pejabat ini?
                    </label>
                </div>

                <div id="form_akun" class="d-none bg-light p-3 rounded border mb-3">
                    <h6 class="text-success font-weight-bold">Pengaturan Akun Login</h6>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Username Login</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Contoh: kadus_mawar" value="{{ old('username') }}">
                            <small class="text-muted">Password Default: <strong>123456</strong></small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Role / Hak Akses</label>
                            <select name="role_akun" id="role_akun" class="form-control">
                                <option value="">-- Pilih Role --</option>
                                <option value="kades">Kepala Desa</option>
                                <option value="kadus">Kepala Dusun</option>
                                <option value="admin">Admin/Operator</option>
                            </select>
                        </div>
                    </div>

                    {{-- Dropdown Dusun (Khusus Kadus) --}}
                    <div class="form-group d-none" id="area_dusun">
                        <label class="font-weight-bold text-danger">Pilih Wilayah Dusun (Wajib untuk Kadus)</label>
                        <select name="id_dusun_akun" class="form-control">
                            <option value="">-- Pilih Dusun --</option>
                            @foreach($dusunList as $dusun)
                                <option value="{{ $dusun->id_dusun }}">{{ $dusun->nama_dusun }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <a href="{{ route('pejabat-desa.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </form>
        </div>
    </div>

    {{-- SCRIPT UNTUK MENGATUR FORM --}}
    @push('scripts')
        <script>
            $(document).ready(function () {

                // 1. Toggle Form Akun
                $('#buat_akun').change(function () {
                    if ($(this).is(':checked')) {
                        $('#form_akun').removeClass('d-none');
                        // Otomatis copy NIP ke Username jika ada
                        var nip = $('#nip').val();
                        if (nip) { $('#username').val(nip); }
                    } else {
                        $('#form_akun').addClass('d-none');
                    }
                });

                // 2. Toggle Dropdown Dusun
                $('#role_akun').change(function () {
                    if ($(this).val() == 'kadus') {
                        $('#area_dusun').removeClass('d-none');
                    } else {
                        $('#area_dusun').addClass('d-none');
                    }
                });

            });
        </script>
    @endpush
@endsection