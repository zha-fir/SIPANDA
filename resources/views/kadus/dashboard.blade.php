@extends('layouts.kadus')

@section('title', 'Dashboard')

@section('content')

<div class="alert alert-primary shadow-sm mb-4 border-0">
    <i class="fas fa-map-marker-alt me-2"></i>
    Anda sedang mengelola data wilayah: <strong>{{ Auth::user()->dusun->nama_dusun ?? 'Wilayah Tidak Diketahui' }}</strong>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Warga</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalWarga }}</div>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKK }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-home fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Surat Proses</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $suratMasuk }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Surat Selesai</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $suratSelesai }}</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-8 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aktivitas Surat Warga (Terbaru)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Warga</th>
                                <th>Jenis Surat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suratTerbaru as $surat)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($surat->tanggal_ajuan)->format('d/m/Y') }}</td>
                                <td>{{ $surat->warga->nama_lengkap }}</td>
                                <td>{{ $surat->jenisSurat->nama_surat ?? '-' }}</td>
                                <td>
                                    @if($surat->status == 'BARU') <span class="badge badge-warning">Proses</span>
                                    @elseif($surat->status == 'SELESAI') <span class="badge badge-success">Selesai</span>
                                    @else <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center">Belum ada aktivitas surat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-2">
                    <a href="{{ route('kadus.surat') }}">Lihat Semua Surat &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Demografi Dusun</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Laki-laki
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-danger"></i> Perempuan
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

<script>
    // Grafik Pie Gender
    var ctx = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ["Laki-laki", "Perempuan"],
        datasets: [{
          data: [{{ $wargaLaki }}, {{ $wargaPerempuan }}],
          backgroundColor: ['#4e73df', '#e74a3b'],
          hoverBackgroundColor: ['#2e59d9', '#be2617'],
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
        legend: { display: false },
        cutoutPercentage: 80,
      },
    });
</script>
@endpush