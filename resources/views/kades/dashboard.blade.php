@extends('layouts.kades')

@section('title', 'Dashboard')

@section('content')

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Penduduk</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalWarga }} Jiwa</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total KK</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKK }} KK</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-home fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Surat Masuk (Bulan Ini)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $suratMasuk }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-envelope-open-text fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Selesai (Bulan Ini)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $suratSelesai }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Sebaran Penduduk per Dusun</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar">
                    <canvas id="myBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Top 5 Profesi Warga</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small text-muted">
                    *Data berdasarkan 5 pekerjaan terbanyak
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Aktivitas Surat Terbaru</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pemohon</th>
                        <th>Jenis Surat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suratTerbaru as $surat)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($surat->tanggal_ajuan)->isoFormat('D MMM Y') }}</td>
                        <td>{{ $surat->warga->nama_lengkap }}</td>
                        <td>{{ $surat->jenisSurat->nama_surat ?? '-' }}</td>
                        <td>
                            @if($surat->status == 'BARU') <span class="badge badge-warning">Proses</span>
                            @elseif($surat->status == 'SELESAI') <span class="badge badge-success">Selesai</span>
                            @else <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

<script>
    // --- 1. KONFIGURASI GRAFIK BATANG (DUSUN) ---
    var ctxBar = document.getElementById("myBarChart");
    var myBarChart = new Chart(ctxBar, {
      type: 'bar',
      data: {
        labels: {!! json_encode($chartDusunLabels) !!}, // Ambil dari Controller
        datasets: [{
          label: "Jumlah Warga",
          backgroundColor: "#4e73df",
          hoverBackgroundColor: "#2e59d9",
          borderColor: "#4e73df",
          data: {!! json_encode($chartDusunData) !!}, // Ambil dari Controller
        }],
      },
      options: {
        maintainAspectRatio: false,
        layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
        scales: {
          xAxes: [{ gridLines: { display: false, drawBorder: false }, maxBarThickness: 25 }],
          yAxes: [{ ticks: { min: 0, padding: 10 } }],
        },
        legend: { display: false },
      }
    });

    // --- 2. KONFIGURASI GRAFIK DONAT (PEKERJAAN) ---
    var ctxPie = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctxPie, {
      type: 'doughnut',
      data: {
        labels: {!! json_encode($chartPekerjaanLabels) !!},
        datasets: [{
          data: {!! json_encode($chartPekerjaanData) !!},
          backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
          hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
          hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          caretPadding: 10,
        },
        legend: { display: true, position: 'bottom' },
        cutoutPercentage: 80,
      },
    });
</script>
@endpush