<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pelayanan Warga')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- GLOBAL STYLE --- */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #374151;
        }

        /* --- NAVBAR --- */
        .navbar {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            z-index: 1040; /* Di bawah Offcanvas */
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 1.25rem;
        }

/* --- SIDEBAR CONTAINER (MEMBUAT LATAR PUTIH UTUH) --- */
        
        /* Tampilan Desktop */
        @media (min-width: 768px) {
            .sidebar-wrapper {
                position: sticky;
                top: 75px;
                height: calc(100vh - 75px);
                overflow-y: auto;
                
                /* KUNCI PERUBAHAN DI SINI: */
                background-color: #ffffff; /* Latar Sidebar Putih */
                border-right: 1px solid #eaeaea; /* Garis pemisah tipis di kanan */
                
                padding-top: 20px;
                /* Hapus border-radius atau shadow jika ingin flat full */
                box-shadow: 2px 0 15px rgba(0,0,0,0.02); 
            }
        }

       /* Tampilan Mobile (Layar Kecil - Offcanvas Style) */
        @media (max-width: 767.98px) {
            .offcanvas-md {
                background-color: #ffffff; /* Latar Putih */
                width: 280px !important;
            }
            
            /* PERBAIKAN HEADER MENU MOBILE */
            .sidebar-header-mobile {
                display: flex;              /* Gunakan Flexbox */
                align-items: center;        /* Rata tengah vertikal */
                justify-content: space-between; /* Jarak maksimal (Kiri - Kanan) */
                padding: 1rem 1.5rem;       /* Jarak dalam */
                border-bottom: 1px solid #f0f0f0; /* Garis bawah tipis */
                background-color: #ffffff;  /* Latar Putih */
            }
        }
        /* --- MENU ITEM (TOMBOL MENYATU) --- */
        .list-group-item {
            border: none;
            margin-bottom: 5px;
            border-radius: 8px !important;
            
            /* KUNCI PERUBAHAN DI SINI: */
            background-color: transparent; /* Tombol transparan (ikut warna sidebar) */
            color: #555; /* Warna teks abu tua */
            
            font-weight: 500;
            padding: 12px 20px;
            transition: all 0.2s ease-in-out;
        }

        /* Efek Hover (Saat kursor diarahkan) */
        .list-group-item:hover {
            background-color: #f8f9fa; /* Abu sangat muda saat hover */
            color: #0d6efd; /* Teks jadi biru */
            transform: translateX(5px); /* Geser sedikit ke kanan */
        }

        /* Efek Aktif (Menu yang sedang dipilih) */
        .list-group-item.active {
            background-color: #e7f1ff; /* Biru muda lembut */
            color: #0d6efd; /* Teks Biru Utama */
            font-weight: 700;
            border-left: none; /* Hapus border kiri jika ada */
        }
        
        /* Ikon di dalam menu */
        .list-group-item i {
            width: 25px;
            text-align: center;
            margin-right: 10px;
        }

        /* Menu Item Style */
        .list-group-item {
            border: none;
            margin-bottom: 5px;
            border-radius: 10px !important;
            color: #666;
            font-weight: 500;
            padding: 12px 20px;
            transition: all 0.2s;
        }
        .list-group-item:hover {
            background-color: #f0f7ff;
            color: #0d6efd;
        }
        .list-group-item.active {
            background-color: #e9f2ff;
            color: #0d6efd;
            font-weight: 600;
            border-left: 4px solid #0d6efd;
        }
        
        /* Avatar Kecil */
        .nav-avatar {
            width: 32px; height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-md navbar-dark sticky-top px-3">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-white d-md-none me-2 p-0 border-0" type="button" 
                        data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <a class="navbar-brand" href="{{ route('warga.dashboard') }}">
                    <i class="fas fa-landmark me-2"></i>SIPANDA
                </a>
            </div>

            <div class="d-none d-md-block">
                <div class="dropdown">
                    <a class="nav-link text-white d-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="nav-avatar me-2"><i class="fas fa-user"></i></div>
                        <span class="fw-medium">{{ Auth::user()->nama_lengkap ?? 'Warga' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                        <li><a class="dropdown-item py-2" href="{{ route('warga.password.edit') }}"><i class="fas fa-key me-2 text-warning"></i>Ubah Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('warga.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger fw-bold"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            
            <div class="col-md-3 col-lg-2 p-0">
                
                <div class="offcanvas-md offcanvas-start sidebar-wrapper" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
                    
                    <div class="d-md-none sidebar-header-mobile">
                        {{-- Judul di Kiri --}}
                        <h5 class="offcanvas-title fw-bold text-primary m-0" id="sidebarMenuLabel">
                            <i class="fas fa-landmark me-2"></i>SIPANDA
                        </h5>
                        
                        {{-- Tombol Close (X) di Kanan --}}
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body d-block px-3 pt-md-0 pt-3">
                        
                        <div class="text-muted small fw-bold text-uppercase mb-2 mt-2 ps-2">Menu Utama</div>
                        <div class="list-group mb-4">
                            <a href="{{ route('warga.dashboard') }}" class="list-group-item list-group-item-action @if(Request::is('warga/dashboard*')) active @endif">
                                <i class="fas fa-th-large fa-fw me-2"></i>Dashboard
                            </a>
                            <a href="{{ route('warga.ajuan.create') }}" class="list-group-item list-group-item-action @if(Request::is('warga/ajuan-surat*')) active @endif">
                                <i class="fas fa-file-signature fa-fw me-2"></i>Buat Pengajuan Surat
                            </a>
                            <a href="{{ route('warga.ajuan.history') }}" class="list-group-item list-group-item-action @if(Request::is('warga/riwayat-ajuan*')) active @endif">
                                <i class="fas fa-history fa-fw me-2"></i>Riwayat & Status
                            </a>
                        </div>

                        <div class="d-md-none">
                            <div class="text-muted small fw-bold text-uppercase mb-2 mt-2 ps-2 border-top pt-3">Akun Saya</div>
                            
                            <div class="d-flex align-items-center px-3 mb-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    {{ substr(Auth::user()->nama_lengkap, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ Str::limit(Auth::user()->nama_lengkap, 18) }}</div>
                                    <div class="small text-muted">Warga Desa</div>
                                </div>
                            </div>

                            <div class="list-group">
                                <a href="{{ route('warga.password.edit') }}" class="list-group-item list-group-item-action @if(Request::is('warga/profil*')) active @endif">
                                    <i class="fas fa-key fa-fw me-2 text-warning"></i>Ubah Password
                                </a>
                                
                                <form action="{{ route('warga.logout') }}" method="POST" class="w-100">
                                    @csrf
                                    <button type="submit" class="list-group-item list-group-item-action text-danger fw-bold w-100 text-start">
                                        <i class="fas fa-sign-out-alt fa-fw me-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="d-none d-md-block">
                            <div class="text-muted small fw-bold text-uppercase mb-2 ps-2">Pengaturan</div>
                            <div class="list-group">
                                <a href="{{ route('warga.password.edit') }}" class="list-group-item list-group-item-action @if(Request::is('warga/profil*')) active @endif">
                                    <i class="fas fa-key fa-fw me-2"></i>Ubah Password
                                </a>
                            </div>
                        </div>

                    </div> </div> 
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 d-flex flex-column" style="min-height: calc(100vh - 75px);">
                <div class="d-flex align-items-center mb-4">
                    <h2 class="h4 fw-bold text-dark mb-0">@yield('title')</h2>
                </div>

                {{-- WRAPPER KONTEN (Tambahkan mb-5 di sini) --}}
                <div class="flex-grow-1 mb-5"> 
                    @yield('content')
                </div>

                {{-- mt-auto adalah kuncinya: "Margin Top Auto" (Dorong ke paling bawah) --}}
                <footer class="mt-auto mt-5">
                    <div class="border-top pt-2">
                        <div class="container text-center">
                            <p class="text-muted small mb-2">&copy; {{ date('Y') }} <strong>Kelompok 1 SIPANDA 2025</strong>.</p>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Script Toggle Password (untuk form)
            $(document).on('click', '.btn-toggle-password', function() {
                let input = $(this).closest('.input-group').find('input');
                let icon = $(this).find('i');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>