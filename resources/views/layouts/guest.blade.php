<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Login') | StudentTrack</title>
    <meta name="description" content="StudentTrack Academic Management System">

    <!-- Theme -->
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

    <!-- StudentTrack CSS -->
    <link href="{{ asset('css/studenttrack.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>

    @yield('content')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- StudentTrack JS -->
    <script src="{{ asset('js/studenttrack.js') }}" defer></script>

    @stack('scripts')

</body>
</html>
