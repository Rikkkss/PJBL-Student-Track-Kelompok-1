
    @extends('layouts.app')

    @section('title', 'Dashboard')

    @section('content')

    <div class="dashboard-page">

        {{-- HERO --}}
        
        @if(auth()->user()->role === 'admin')
    <h1 class="greeting-title">
        Halo,
        <span class="greeting-name">Admin</span>
        <span class="greeting-wave">!</span>
    </h1>

    <p>
        Selamat datang di StudentTrack.
        Kelola dan pantau aktivitas akademik seluruh mahasiswa.
    </p>
@else
    <h1 class="greeting-title">
        Halo,
        <span class="greeting-name">{{ auth()->user()->name }}</span>
        <span class="greeting-wave">!</span>
    </h1>

    <p>
        Selamat datang kembali di StudentTrack.
        Kelola perkuliahan, pantau tugas, dan capai target
        akademikmu dalam satu tempat.
    </p>
@endif
            <div class="hero-actions">
                <a href="{{ route('nilai.create') }}" class="hero-btn hero-btn-light">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Nilai
                </a>

                <a href="{{ route('tugas.create') }}" class="hero-btn hero-btn-outline">
                    <i class="bi bi-clipboard-plus"></i>
                    Buat Tugas
                </a>
            </div>

            <div class="hero-decoration hero-decoration-one"></div>
            <div class="hero-decoration hero-decoration-two"></div>
        </div>


        {{-- RINGKASAN AKADEMIK --}}
        <div class="section-heading">
            <div>
                <h5>Ringkasan Akademik</h5>
                <p>Informasi aktivitas akademik secara keseluruhan.</p>
            </div>
        </div>

        <div class="row g-3 mb-4">

            {{-- TOTAL MATA KULIAH --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-stat stat-purple">
                    <div class="stat-top">
                        <div class="stat-icon-modern">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <span class="stat-tag">AKADEMIK</span>
                    </div>

                    <div class="stat-name">Total Mata Kuliah</div>

                    <div class="stat-number">
                        {{ $totalMataKuliah }}
                    </div>

                    <div class="stat-foot">
                        <span>Mata kuliah terdaftar</span>
                        <a href="{{ route('mata-kuliah.index') }}">
                            Lihat detail <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- TOTAL SKS --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-stat stat-green">
                    <div class="stat-top">
                        <div class="stat-icon-modern">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <span class="stat-tag">KREDIT</span>
                    </div>

                    <div class="stat-name">Total SKS</div>

                    <div class="stat-number">
                        {{ $totalSKS }}
                    </div>

                    <div class="stat-foot">
                        <span>Total beban studi</span>
                    </div>
                </div>
            </div>

            {{-- TOTAL TUGAS --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-stat stat-orange">
                    <div class="stat-top">
                        <div class="stat-icon-modern">
                            <i class="bi bi-clipboard-check-fill"></i>
                        </div>
                        <span class="stat-tag">AKTIVITAS</span>
                    </div>

                    <div class="stat-name">Total Tugas</div>

                    <div class="stat-number">
                        {{ $totalTugas }}
                    </div>

                    <div class="stat-foot">
                        <span>Seluruh tugas akademik</span>
                        <a href="{{ route('tugas.index') }}">
                            Lihat detail <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- TUGAS BELUM DIKERJAKAN --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-stat stat-red">
                    <div class="stat-top">
                        <div class="stat-icon-modern">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <span class="stat-tag">PENDING</span>
                    </div>

                    <div class="stat-name">Belum Dikerjakan</div>

                    <div class="stat-number">
                        {{ $tugasBelumDikerjakan }}
                    </div>

                    <div class="stat-foot">
                        <span>Tugas yang perlu diselesaikan</span>
                    </div>
                </div>
            </div>

        </div>


        {{-- ACADEMIC ANALYTICS --}}
        <div class="section-heading">
            <div>
                <h5>Academic Analytics</h5>
                <p>Pantau hasil belajar dan penyelesaian tugas.</p>
            </div>
        </div>

        <div class="row g-3 mb-4">

            {{-- GRAFIK NILAI --}}
            <div class="col-12 col-xl-8">
                <div class="dashboard-panel h-100">

                    <div class="panel-header">
                        <div>
                            <h5>Performa Nilai Akademik</h5>
                            <p>Rata-rata nilai berdasarkan mata kuliah.</p>
                        </div>

                        <div class="overview-title-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>

                    @if(count($labelGrafik) > 0)

                        <div class="chart-container dashboard-chart">
                            <canvas id="grafikNilai"></canvas>
                        </div>

                    @else

                        <div class="empty-chart">
                            <div class="empty-chart-icon">
                                <i class="bi bi-bar-chart-line"></i>
                            </div>

                            <h6>Belum Ada Data Nilai</h6>

                            <p>
                                Tambahkan nilai akademik untuk mulai melihat
                                performa setiap mata kuliah.
                            </p>

                            <a href="{{ route('nilai.create') }}"
                            class="dashboard-primary-btn">
                                <i class="bi bi-plus-lg"></i>
                                Tambah Nilai
                            </a>
                        </div>

                    @endif

                </div>
            </div>


            {{-- GRAFIK TUGAS --}}
            <div class="col-12 col-xl-4">
                <div class="dashboard-panel h-100">

                    <div class="panel-header">
                        <div>
                            <h5>Distribusi Status Tugas</h5>
                            <p>Ringkasan progres penyelesaian.</p>
                        </div>

                        <div class="overview-title-icon">
                            <i class="bi bi-pie-chart-fill"></i>
                        </div>
                    </div>

                    @if($totalTugas > 0)

                        <div class="chart-container dashboard-chart dashboard-doughnut">
                            <canvas id="grafikTugas"></canvas>
                        </div>

                    @else

                        <div class="empty-chart">
                            <div class="empty-chart-icon">
                                <i class="bi bi-clipboard-data"></i>
                            </div>

                            <h6>Belum Ada Data Tugas</h6>

                            <p>
                                Tambahkan tugas untuk melihat distribusi
                                status pengerjaan.
                            </p>

                            <a href="{{ route('tugas.create') }}"
                            class="dashboard-primary-btn">
                                <i class="bi bi-plus-lg"></i>
                                Tambah Tugas
                            </a>
                        </div>

                    @endif

                </div>
            </div>

        </div>


        {{-- ACADEMIC PROGRESS --}}
        <div class="section-heading">
            <div>
                <h5>Academic Progress</h5>
                <p>Ringkasan pencapaian aktivitas akademik.</p>
            </div>
        </div>

        <div class="row g-3 mb-4">

            {{-- PROGRES TUGAS --}}
            <div class="col-12 col-lg-6">
                <div class="progress-panel">

                    <div class="progress-header">
                        <h5>Progres Penyelesaian Tugas</h5>

                        <div class="progress-circle">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                    </div>

                    @php
                        $persentaseSelesai = $totalTugas > 0
                            ? round(($tugasSelesai / $totalTugas) * 100)
                            : 0;
                    @endphp

                    <div class="d-flex align-items-end gap-2">
                        <div class="progress-number">
                            {{ $persentaseSelesai }}%
                        </div>

                        <span class="text-muted small mb-2">
                            tugas selesai
                        </span>
                    </div>

                    <div class="progress-track">
                        <div class="progress-fill"
                            style="width: {{ $persentaseSelesai }}%">
                        </div>
                    </div>

                    <div class="progress-footer">
                        <span>{{ $tugasSelesai }} tugas selesai</span>
                        <span>{{ $tugasBelumDikerjakan }} belum dikerjakan</span>
                    </div>

                </div>
            </div>


            {{-- RATA-RATA NILAI --}}
            <div class="col-12 col-lg-6">
                <div class="progress-panel">

                    <div class="progress-header">
                        <h5>Rata-rata Nilai Akademik</h5>

                        <div class="overview-title-icon">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                    </div>

                    <div class="progress-number">
                        {{ $rataRataNilai !== null
                            ? number_format((float) $rataRataNilai, 2, ',', '.')
                            : '-' }}
                    </div>

                    <div class="progress-footer">
                        <span>Rata-rata seluruh komponen penilaian</span>

                        <a href="{{ route('nilai.index') }}">
                            Lihat nilai <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>
            </div>

        </div>


        {{-- ACADEMIC REPORT --}}
        <div class="section-heading">
            <div>
                <h5>Academic Report</h5>
                <p>Dokumen hasil studi dan rekapitulasi nilai akademik.</p>
            </div>
        </div>

        <div class="dashboard-report-panel mb-4">

            <div class="report-main">

                <div class="report-icon">
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                </div>

                <div class="report-description">
                    <span class="report-label">STUDENT ACADEMIC DOCUMENT</span>

                    <h5>Transkrip Nilai Akademik</h5>

                    <p>
                        Unduh laporan akademik dalam format PDF yang berisi
                        rekapitulasi nilai, nilai akhir, IPS, dan IPK simulasi.
                    </p>

                    <div class="report-features">
                        <span>
                            <i class="bi bi-check-circle-fill"></i>
                            Rekap nilai
                        </span>

                        <span>
                            <i class="bi bi-check-circle-fill"></i>
                            Nilai akhir
                        </span>

                        <span>
                            <i class="bi bi-check-circle-fill"></i>
                            IPS dan IPK
                        </span>
                    </div>
                </div>

            </div>

            <div class="report-action">

                <a href="{{ route('nilai.transkrip.pdf') }}"
                class="report-download-btn">

                    <i class="bi bi-download"></i>
                    Unduh Transkrip PDF

                </a>

                <a href="{{ route('nilai.index') }}"
                class="report-secondary-btn">

                    Lihat Nilai Akademik
                    <i class="bi bi-arrow-up-right"></i>

                </a>

            </div>

        </div>


        {{-- QUICK ACTIONS --}}
        <div class="section-heading">
            <div>
                <h5>Quick Actions</h5>
                <p>Akses cepat untuk mengelola aktivitas akademik.</p>
            </div>
        </div>

        <div class="quick-action-panel mb-4">

            <a href="{{ route('mata-kuliah.create') }}"
            class="quick-action-card">

                <div class="quick-icon quick-purple">
                    <i class="bi bi-journal-plus"></i>
                </div>

                <div>
                    <strong>Tambah Mata Kuliah</strong>
                    <small>Kelola daftar mata kuliah</small>
                </div>

                <i class="bi bi-arrow-up-right quick-arrow"></i>

            </a>


            <a href="{{ route('tugas.create') }}"
            class="quick-action-card">

                <div class="quick-icon quick-orange">
                    <i class="bi bi-clipboard-plus"></i>
                </div>

                <div>
                    <strong>Tambah Tugas</strong>
                    <small>Catat tugas dan deadline</small>
                </div>

                <i class="bi bi-arrow-up-right quick-arrow"></i>

            </a>


            <a href="{{ route('nilai.create') }}"
            class="quick-action-card">

                <div class="quick-icon quick-green">
                    <i class="bi bi-file-earmark-plus"></i>
                </div>

                <div>
                    <strong>Tambah Nilai</strong>
                    <small>Masukkan hasil penilaian</small>
                </div>

                <i class="bi bi-arrow-up-right quick-arrow"></i>

            </a>

        </div>

    </div>


    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // GRAFIK NILAI AKADEMIK
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

                        backgroundColor: 'rgba(101, 88, 220, 0.75)',
                        borderColor: '#6558dc',
                        borderWidth: 1,
                        borderRadius: 8,
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

                            grid: {
                                color: 'rgba(130, 130, 160, 0.12)'
                            },

                            ticks: {
                                color: '#85899b'
                            }
                        },

                        x: {
                            grid: {
                                display: false
                            },

                            ticks: {
                                color: '#85899b'
                            }
                        }
                    },

                    plugins: {
                        legend: {
                            display: false
                        },

                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Nilai: ' +
                                        Number(context.raw).toFixed(2);
                                }
                            }
                        }
                    }
                }
            });
        }


        // GRAFIK STATUS TUGAS
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
                            '#159b75',
                            '#e05269',
                            '#f2ad45'
                        ],

                        borderWidth: 3,
                        borderColor: 'transparent',
                        hoverOffset: 8
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',

                    plugins: {
                        legend: {
                            position: 'bottom',

                            labels: {
                                usePointStyle: true,
                                padding: 18,
                                color: '#85899b',
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

    });
    </script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    // =====================================
    // 1. ANIMASI KEMUNCULAN ELEMEN
    // =====================================

    const dashboard = document.querySelector('.dashboard-page');

    if (!dashboard) return;

    const animatedElements = dashboard.querySelectorAll(
        '.section-heading, .dashboard-stat, ' +
        '.dashboard-panel, .progress-panel, ' +
        '.dashboard-report-panel, .quick-action-card'
    );

    animatedElements.forEach((element, index) => {
        element.classList.add('reveal-item');

        element.style.transitionDelay =
            `${(index % 4) * 100}ms`;
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12
    });

    animatedElements.forEach(element => {
        observer.observe(element);
    });


    // =====================================
    // 2. ANIMASI ANGKA STATISTIK
    // =====================================

    function animateNumber(element) {

        const target = Number(
            element.textContent.trim().replace(',', '.')
        );

        if (!Number.isFinite(target)) return;

        const duration = 1300;
        const startTime = performance.now();

        function updateNumber(currentTime) {

            const progress = Math.min(
                (currentTime - startTime) / duration,
                1
            );

            const eased = 1 - Math.pow(1 - progress, 4);

            const current = target * eased;

            element.textContent = Number.isInteger(target)
                ? Math.round(current)
                : current.toFixed(2);

            if (progress < 1) {
                requestAnimationFrame(updateNumber);
            }
        }

        element.textContent = '0';

        requestAnimationFrame(updateNumber);
    }

    const numberObserver = new IntersectionObserver((entries) => {

        entries.forEach(entry => {

            if (entry.isIntersecting) {
                animateNumber(entry.target);
                numberObserver.unobserve(entry.target);
            }

        });

    }, {
        threshold: 0.5
    });

    dashboard.querySelectorAll('.stat-number').forEach(element => {
        numberObserver.observe(element);
    });


    // =====================================
    // 3. ANIMASI PROGRESS BAR
    // =====================================

    const progressObserver = new IntersectionObserver((entries) => {

        entries.forEach(entry => {

            if (entry.isIntersecting) {

                const bar = entry.target;
                const targetWidth = bar.getAttribute('style');

                bar.style.width = '0%';

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        bar.style.width =
                            targetWidth.match(/width:\s*([^;]+)/)?.[1] || '0%';
                    });
                });

                progressObserver.unobserve(bar);
            }

        });

    }, {
        threshold: 0.5
    });

    dashboard.querySelectorAll('.progress-fill').forEach(bar => {
        progressObserver.observe(bar);
    });

});
</script>

    @endsection
