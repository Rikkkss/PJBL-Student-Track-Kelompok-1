
@extends('layouts.app')

@section('title', 'Dashboard StudentTrack')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="mb-4">
        <h2 class="fw-bold">Dashboard StudentTrack</h2>
        <p class="text-muted mb-0">
            Ringkasan aktivitas dan perkembangan akademikmu.
        </p>
    </div>

    {{-- STATISTIK UTAMA --}}
    <div class="row g-3 mb-4">

        {{-- TOTAL MATA KULIAH --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Mata Kuliah</p>

                    <h2 class="fw-bold text-primary">
                        {{ $totalMataKuliah }}
                    </h2>

                    <a href="{{ route('mata-kuliah.index') }}"
                       class="btn btn-outline-primary btn-sm">
                        Lihat detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- TOTAL SKS --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Total SKS</p>

                    <h2 class="fw-bold text-success">
                        {{ $totalSKS }}
                    </h2>

                    <small class="text-muted">
                        Total beban studi
                    </small>
                </div>
            </div>
        </div>

        {{-- TOTAL TUGAS --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Tugas</p>

                    <h2 class="fw-bold text-warning">
                        {{ $totalTugas }}
                    </h2>

                    <a href="{{ route('tugas.index') }}"
                       class="btn btn-outline-primary btn-sm">
                        Lihat detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- TUGAS BELUM DIKERJAKAN --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Belum Dikerjakan</p>

                    <h2 class="fw-bold text-danger">
                        {{ $tugasBelumDikerjakan }}
                    </h2>

                    <small class="text-muted">
                        Tugas yang perlu diselesaikan
                    </small>
                </div>
            </div>
        </div>

    </div>

    {{-- STATISTIK TAMBAHAN --}}
    <div class="row g-3 mb-4">

        {{-- TUGAS SELESAI --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Tugas Selesai</p>

                    <h2 class="fw-bold text-success">
                        {{ $tugasSelesai }}
                    </h2>

                    <small class="text-muted">
                        Tugas yang sudah diselesaikan
                    </small>
                </div>
            </div>
        </div>

        {{-- RATA-RATA NILAI --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Rata-rata Nilai</p>

                    <h2 class="fw-bold text-primary">
                        {{ $rataRataNilai !== null
                            ? number_format((float) $rataRataNilai, 2, ',', '.')
                            : '-' }}
                    </h2>

                    <a href="{{ route('nilai.index') }}"
                       class="btn btn-outline-primary btn-sm">
                        Lihat Nilai
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- ACADEMIC ANALYTICS --}}
    <div class="mb-3">
        <h5 class="fw-bold">Academic Analytics</h5>
        <p class="text-muted small">
            Pantau hasil belajar dan penyelesaian tugas.
        </p>
    </div>

    <div class="row g-3 mb-4">

        {{-- GRAFIK NILAI --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-1">
                        Performa Nilai Akademik
                    </h5>

                    <p class="text-muted small mb-4">
                        Rata-rata nilai berdasarkan mata kuliah.
                    </p>

                    @if ($labelGrafik->count() > 0)

                        <div style="position: relative; height: 350px;">
                            <canvas id="grafikNilai"></canvas>
                        </div>

                    @else

                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-bar-chart-line fs-1 text-primary"></i>
                            </div>

                            <h6 class="fw-semibold">
                                Belum Ada Data Nilai
                            </h6>

                            <p class="text-muted">
                                Tambahkan nilai akademik untuk mulai melihat
                                performa setiap mata kuliah.
                            </p>

                            <a href="{{ route('nilai.create') }}"
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i>
                                Tambah Nilai
                            </a>
                        </div>

                    @endif

                </div>
            </div>
        </div>

        {{-- GRAFIK STATUS TUGAS --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-1">
                        Distribusi Status Tugas
                    </h5>

                    <p class="text-muted small mb-4">
                        Ringkasan progres penyelesaian.
                    </p>

                    @if ($totalTugas > 0)

                        <div style="position: relative; height: 300px;">
                            <canvas id="grafikTugas"></canvas>
                        </div>

                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                Total {{ $totalTugas }} tugas terdaftar
                            </small>
                        </div>

                    @else

                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-clipboard-data fs-1 text-primary"></i>
                            </div>

                            <h6 class="fw-semibold">
                                Belum Ada Data Tugas
                            </h6>

                            <p class="text-muted">
                                Tambahkan tugas untuk melihat distribusi status.
                            </p>

                            <a href="{{ route('tugas.create') }}"
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i>
                                Tambah Tugas
                            </a>
                        </div>

                    @endif

                </div>
            </div>
        </div>

    </div>

    {{-- ACADEMIC PROGRESS --}}
    <div class="mb-3">
        <h5 class="fw-bold">Academic Progress</h5>
        <p class="text-muted small">
            Ringkasan pencapaian aktivitas akademik.
        </p>
    </div>

    <div class="row g-3 mb-4">

        {{-- PROGRES PENYELESAIAN TUGAS --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        Progres Penyelesaian Tugas
                    </h5>

                    @php
                        $persentaseTugas = $totalTugas > 0
                            ? ($tugasSelesai / $totalTugas) * 100
                            : 0;
                    @endphp

                    <h2 class="fw-bold text-success">
                        {{ number_format($persentaseTugas, 1) }}%
                    </h2>

                    <div class="progress mt-3 mb-3"
                         style="height: 12px;">
                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width: {{ $persentaseTugas }}%">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            {{ $tugasSelesai }} tugas selesai
                        </small>

                        <small class="text-muted">
                            {{ $tugasBelumDikerjakan }} belum dikerjakan
                        </small>
                    </div>

                </div>
            </div>
        </div>

        {{-- RATA-RATA NILAI AKADEMIK --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        Rata-rata Nilai Akademik
                    </h5>

                    <h2 class="fw-bold text-primary">
                        {{ $rataRataNilai !== null
                            ? number_format((float) $rataRataNilai, 2, ',', '.')
                            : '-' }}
                    </h2>

                    <p class="text-muted small mb-3">
                        Rata-rata dari seluruh catatan nilai yang tersimpan.
                    </p>

                    <a href="{{ route('nilai.index') }}"
                       class="btn btn-outline-primary btn-sm">
                        Lihat Detail Nilai
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>

                </div>
            </div>
        </div>

    </div>

    {{-- LAPORAN AKADEMIK --}}
    <div class="mb-3">
        <h5 class="fw-bold">ort</h5>
        <p class="text-muted small">
            Akses laporan hasil studi dan transkrip nilai.
        </p>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <div class="row align-items-center g-3">

                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-3">

                        <div class="rounded-3 p-3"
                             style="background: rgba(101, 88, 220, .10);
                                    color: #6558dc;">
                            <i class="bi bi-mortarboard fs-2"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Laporan Akademik
                            </h5>

                            <p class="text-muted mb-0 small">
                                Unduh Kartu Hasil Studi (KHS) dan transkrip
                                nilai akademik dalam format PDF.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">

                        <a href="{{ route('nilai.transkrip.pdf') }}"
                           class="btn btn-primary">
                            <i class="bi bi-file-earmark-pdf me-1"></i>
                            Unduh Transkrip PDF
                        </a>

                        <a href="{{ route('nilai.index') }}"
                           class="btn btn-outline-primary">
                            <i class="bi bi-journal-text me-1"></i>
                            Lihat Nilai
                        </a>

                    </div>
                </div>

            </div>

            <hr class="my-3">

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-text text-primary fs-5"></i>

                        <div>
                            <div class="fw-semibold small">
                                Kartu Hasil Studi (KHS)
                            </div>

                            <small class="text-muted">
                                Laporan nilai berdasarkan semester.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-pdf text-primary fs-5"></i>

                        <div>
                            <div class="fw-semibold small">
                                Transkrip Nilai
                            </div>

                            <small class="text-muted">
                                Rekap nilai seluruh semester beserta IPK simulasi.
                            </small>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <h5 class="fw-bold">
                Mulai Kelola Akademikmu
            </h5>

            <p class="text-muted">
                Pantau mata kuliah, tugas, deadline, dan nilai akademik
                dalam satu tempat.
            </p>

            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route('mata-kuliah.create') }}"
                   class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Tambah Mata Kuliah
                </a>

                <a href="{{ route('tugas.create') }}"
                   class="btn btn-outline-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Tambah Tugas
                </a>

                <a href="{{ route('nilai.create') }}"
                   class="btn btn-outline-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Tambah Nilai
                </a>

            </div>

        </div>
    </div>

</div>

{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ==================================
    // GRAFIK RATA-RATA NILAI
    // ==================================

    const canvasNilai = document.getElementById('grafikNilai');

    if (canvasNilai) {

        const labelGrafik = {{ Illuminate\Support\Js::from($labelGrafik) }};
        const dataGrafik = {{ Illuminate\Support\Js::from($dataGrafik) }};

        new Chart(canvasNilai, {
            type: 'bar',

            data: {
                labels: labelGrafik,

                datasets: [{
                    label: 'Rata-rata Nilai',
                    data: dataGrafik,

                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: 'rgb(13, 110, 253)',
                    borderWidth: 1,
                    borderRadius: 6,
                    maxBarThickness: 55
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: 100,

                        title: {
                            display: true,
                            text: 'Nilai'
                        }
                    },

                    x: {
                        title: {
                            display: true,
                            text: 'Mata Kuliah'
                        }
                    }
                },

                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },

                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rata-rata: ' +
                                    Number(context.raw).toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    }

    // ==================================
    // GRAFIK STATUS TUGAS
    // ==================================

    const canvasTugas = document.getElementById('grafikTugas');

    if (canvasTugas) {

        const labelTugas = {{ Illuminate\Support\Js::from($labelGrafikTugas) }};
        const dataTugas = {{ Illuminate\Support\Js::from($dataGrafikTugas) }};

        new Chart(canvasTugas, {
            type: 'doughnut',

            data: {
                labels: labelTugas,

                datasets: [{
                    data: dataTugas,

                    backgroundColor: [
                        '#198754',
                        '#dc3545',
                        '#ffc107'
                    ],

                    borderWidth: 2,
                    hoverOffset: 8
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },

                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' +
                                    context.raw + ' tugas';
                            }
                        }
                    }
                }
            }
        });
    }

});
</script>

@endsection
