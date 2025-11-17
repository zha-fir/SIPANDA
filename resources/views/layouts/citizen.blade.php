<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-content">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Pelayanan Warga') - {{ config('app.name') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (untuk ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f4f7f6;
        }
        .sidebar {
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            min-height: 100vh;
        }
        .sidebar .list-group-item {
            border: none;
            padding: 1rem 1.5rem;
        }
        .sidebar .list-group-item.active {
            background-color: #0d6efd;
            color: #fff;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    
    <!-- Navbar Atas -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('warga.dashboard') }}">
                <i class="fas fa-home me-2"></i>Sistem Administrasi Warga
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                  {{-- Hanya tampilkan menu ini JIKA user sudah login --}}
                  @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-2"></i>{{ Auth::user()->nama_lengkap }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <!-- Tombol Logout -->
                            <li>
                                <form action="{{ route('warga.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                  @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Kiri -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar p-4">
                <div class="list-group">
                    <a href="{{ route('warga.dashboard') }}" 
                       class="list-group-item list-group-item-action @if(Request::is('warga/dashboard*')) active @endif">
                        <i class="fas fa-tachometer-alt fa-fw me-2"></i>Dashboard
                    </a>
                    <a href="#" 
                       class="list-group-item list-group-item-action @if(Request::is('warga/ajuan-surat*')) active @endif">
                        <i class="fas fa-file-alt fa-fw me-2"></i>Buat Ajuan Surat
                    </a>
                    <a href="#" 
                       class="list-group-item list-group-item-action @if(Request::is('warga/profil*')) active @endif">
                        <i class="fas fa-user-cog fa-fw me-2"></i>Ubah Password
                    </a>
                </div>
            </nav>

            <!-- Konten Utama -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <h1 class="h2 mb-4">@yield('title')</h1>
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>