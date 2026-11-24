
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Dashboard') | StudentTrack</title>
    <meta name="description" content="StudentTrack Academic Management System">

    <!-- Apply saved theme before rendering -->
    <script>
        (function () {
            try {
                const savedTheme = localStorage.getItem('studenttrack-theme');
                document.documentElement.setAttribute(
                    'data-theme',
                    savedTheme === 'dark' ? 'dark' : 'light'
                );
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Main CSS -->
    <link href="{{ asset('css/studenttrack.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>

<div class="app-layout">

    <!-- SIDEBAR OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div class="brand-text">
                <h4>StudentTrack</h4>
                <span>Academic Management</span>
            </div>
        </div>

        <div class="sidebar-section">
            <span class="sidebar-label">MENU UTAMA</span>

            <nav class="sidebar-nav">

                <a href="{{ route('dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('mata-kuliah.index') }}"
                   class="sidebar-link {{ request()->routeIs('mata-kuliah.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Mata Kuliah</span>
                </a>

                <a href="{{ route('tugas.index') }}"
                   class="sidebar-link {{ request()->routeIs('tugas.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check-fill"></i>
                    <span>Daftar Tugas</span>
                </a>

                <a href="{{ route('nilai.index') }}"
                   class="sidebar-link {{ request()->routeIs('nilai.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Nilai Akademik</span>
                </a>

                <a href="{{ route('kalender-libur.index') }}"
                   class="sidebar-link {{ request()->routeIs('kalender-libur.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event-fill"></i>
                    <span>Kalender Libur</span>
                </a>

            </nav>
        </div>

        <div class="sidebar-bottom">

            <div class="sidebar-help">
                <div class="help-icon">
                    <i class="bi bi-lightbulb-fill"></i>
                </div>

                <div>
                    <strong>StudentTrack</strong>
                    <p>Kelola aktivitas akademikmu dengan mudah.</p>
                </div>
            </div>

            <div class="sidebar-footer">
                © {{ date('Y') }} StudentTrack
            </div>

        </div>

    </aside>

    <!-- MAIN AREA -->
    <div class="main-area">

        <!-- TOPBAR -->
        <header class="topbar">

            <div class="topbar-left">

                <button type="button"
                        class="sidebar-toggle"
                        id="sidebarToggle"
                        aria-label="Buka navigasi"
                        aria-expanded="false">
                    <i class="bi bi-list"></i>
                </button>

                <div class="breadcrumb-area">
                    <span>StudentTrack</span>
                    <i class="bi bi-chevron-right"></i>
                    <strong>@yield('title', 'Dashboard')</strong>
                </div>

            </div>

            <div class="topbar-right">

                <!-- THEME SWITCHER -->
                <button type="button"
                        class="theme-toggle"
                        id="themeToggle"
                        aria-label="Aktifkan dark mode"
                        title="Ganti tema">

                    <i class="bi bi-moon-stars-fill" id="themeIcon"></i>

                </button>

                <!-- DATE -->
                <div class="topbar-date">
                    <i class="bi bi-calendar3"></i>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>

                <!-- USER ICON -->
                
<!-- USER PROFILE DROPDOWN -->
<div class="dropdown">

    <button
        type="button"
        class="user-avatar border-0"
        id="profileDropdown"
        data-bs-toggle="dropdown"
        aria-expanded="false"
        title="Profil Pengguna"
    >
        <i class="bi bi-person-fill"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-end shadow border-0 p-2"
         aria-labelledby="profileDropdown"
         style="min-width: 230px; border-radius: 12px;">

        <!-- INFORMASI PENGGUNA -->
        <div class="px-3 py-2">
            <div class="fw-bold">
                {{ auth()->user()->name }}
            </div>

            <small class="text-muted">
                {{ auth()->user()->email }}
            </small>

            <div class="mt-2">
                <span class="badge bg-primary">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>

        <hr class="dropdown-divider">

        <!-- LOGOUT -->
        <form
            action="{{ route('logout') }}"
            method="POST"
            onsubmit="return confirm('Apakah Anda yakin ingin logout?')"
        >
            @csrf

            <button
                type="submit"
                class="dropdown-item text-danger rounded py-2"
            >
                <i class="bi bi-box-arrow-right me-2"></i>
                Logout
            </button>
        </form>

    </div>
</div>


        </header>

        <!-- CONTENT -->
        <main class="main-content">

            <div class="content-inner">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Tutup"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ session('error') }}

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Tutup"></button>
                    </div>
                @endif

                @yield('content')

            </div>

        </main>

        <!-- FOOTER -->
        <footer class="main-footer">
            StudentTrack · Academic Management System
        </footer>

    </div>

</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- CUSTOM JS -->
<script src="{{ asset('js/studenttrack.js') }}" defer></script>

@stack('scripts')

</body>
</html>
