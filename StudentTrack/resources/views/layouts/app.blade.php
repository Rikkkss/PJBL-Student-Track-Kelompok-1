
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'StudentTrack')</title>

    <!-- Bootstrap 5.3.3 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid px-4">

            <!-- Brand -->
            <a class="navbar-brand fw-bold"
               href="{{ route('dashboard') }}">
                StudentTrack
            </a>

            <!-- Toggle Button -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarStudentTrack"
                aria-controls="navbarStudentTrack"
                aria-expanded="false"
                aria-label="Buka navigasi"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse"
                 id="navbarStudentTrack">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <!-- Mata Kuliah -->
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('mata-kuliah.index') }}">
                            Mata Kuliah
                        </a>
                    </li>

                    <!-- Tugas -->
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('tugas.index') }}">
                            Tugas
                        </a>
                    </li>

                    <!-- Nilai Akademik -->
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('nilai.index') }}">
                            Nilai Akademik
                        </a>
                    </li>

                    <!-- Kalender Libur -->
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('kalender-libur.index') }}">
                            Kalender Libur
                        </a>
                    </li>

                </ul>

                <!-- Navbar Information -->
                <span class="navbar-text text-white">
                    Academic Management System
                </span>

            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container py-4">
        @yield('content')
    </main>

    <!-- Bootstrap JavaScript -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>
