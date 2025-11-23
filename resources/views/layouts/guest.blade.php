<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk Aplikasi') - Pelayanan Desa</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            
            /* --- PERBAIKAN RESPONSIF KEYBOARD --- */
            min-height: 100vh;       /* Gunakan min-height agar bisa tumbuh */
            width: 100%;
            
            /* Izinkan scroll vertikal agar keyboard tidak menutupi form */
            overflow-y: auto;       
            overflow-x: hidden;
            
            /* Flexbox untuk menengahkan (Saat keyboard tutup) */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            
            margin: 0;
            /* Tambahkan padding atas-bawah agar ada ruang saat keyboard muncul */
            padding: 40px 20px; 
            
            position: relative;
        }
        
        /* Dekorasi tetap di belakang */
        .bg-shape {
            position: fixed; 
            background: rgba(13, 110, 253, 0.1);
            border-radius: 50%;
            z-index: -1;
        }

        .copyright-text {
            color: #6c757d;
            font-size: 0.8rem;
            margin-top: 2rem;
            text-align: center;
            /* Pastikan copyright tidak menutupi form saat discroll */
            position: relative; 
            z-index: 1;
        }
    </style>
</head>
<body>
    
    <div class="bg-shape" style="width: 400px; height: 400px; top: -100px; left: -100px;"></div>
    <div class="bg-shape" style="width: 300px; height: 300px; bottom: -50px; right: -50px; background: rgba(13, 253, 161, 0.1);"></div>

    <main class="w-100 d-flex justify-content-center">
        @yield('content')
    </main>

    <div class="copyright-text">
        &copy; {{ date('Y') }} Pemerintah Desa Panggulo. Hak Cipta Dilindungi.
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Script Toggle Password (Yang sudah ada)
            $(document).on('click', '.btn-toggle-password', function() {
                // ... (kode toggle password Anda biarkan saja) ...
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

            // 2. SCRIPT BARU: AUTO SCROLL SAAT KEYBOARD MUNCUL
            // Saat input di-klik/fokus (keyboard mau muncul)
            $('input').on('focus', function() {
                // Tunggu 300ms (biarkan keyboard naik dulu)
                setTimeout(() => {
                    // Scroll elemen input ini ke tengah layar dengan mulus
                    this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
            });
        });
    </script>
</body>
</html>