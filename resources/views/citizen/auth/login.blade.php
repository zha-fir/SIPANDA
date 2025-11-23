@extends('layouts.guest')

@section('title', 'Login')

@section('content')

{{-- PERUBAHAN GRID SYSTEM --}}
{{-- col-12: HP Penuh --}}
{{-- col-sm-10: Tablet Kecil agak tengah --}}
{{-- col-md-6: Tablet Besar setengah layar --}}
{{-- col-lg-4: Laptop sepertiga layar --}}

<div class="col-12 col-sm-10 col-md-6 col-lg-4">
    
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        
        <div class="card-header bg-primary text-white text-center py-3 border-0" 
             style="background: linear-gradient(45deg, #0d6efd, #0a58ca);">
            <div class="mb-1">
                <i class="fas fa-landmark fa-2x"></i>
            </div>
            <h5 class="fw-bold mb-0">SIPANDA</h5>
            <small class="text-white-50" style="font-size: 0.75rem;">Sistem Pelayanan Administrasi Desa Panggulo</small>
        </div>

        <div class="card-body p-4 bg-white">
            
            @if ($errors->any())
                <div class="alert alert-danger py-2 small border-0 shadow-sm rounded-3 mb-3">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('warga.login.submit') }}" method="POST">
                @csrf
                
                <div class="form-group mb-3">
                    <label class="form-label small text-muted fw-bold mb-1">USERNAME / NIK</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-primary">
                            <i class="fas fa-id-card"></i>
                        </span>
                        <input type="text" class="form-control bg-light border-start-0 ps-0" 
                               id="username" name="username" 
                               value="{{ old('username') }}" 
                               placeholder="Masukkan NIK" required>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label small text-muted fw-bold mb-1">PASSWORD</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-primary">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control bg-light border-start-0 ps-0" 
                               id="password" name="password" 
                               placeholder="Masukkan Password" required>
                        <button class="btn btn-light border border-start-0 btn-toggle-password" type="button">
                            <i class="fas fa-eye text-muted"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary fw-bold shadow-sm rounded-3 py-2">
                        MASUK <i class="fas fa-sign-in-alt ms-2"></i>
                    </button>
                </div>

            </form>
        </div>
        
        <div class="card-footer bg-light text-center py-2 border-0">
            <small class="text-muted" style="font-size: 0.75rem;">Belum punya akun? Hubungi Kantor Desa.</small>
        </div>
    </div>

</div>
@endsection