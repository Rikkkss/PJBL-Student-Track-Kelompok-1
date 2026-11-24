
/* =========================================
   STUDENTTRACK JAVASCRIPT
========================================= */

document.addEventListener('DOMContentLoaded', function () {

    // =====================================
    // 1. LIGHT MODE / DARK MODE
    // =====================================

    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    function applyTheme(theme) {

        const selectedTheme = theme === 'dark' ? 'dark' : 'light';

        document.documentElement.setAttribute(
            'data-theme',
            selectedTheme
        );

        try {
            localStorage.setItem(
                'studenttrack-theme',
                selectedTheme
            );
        } catch (error) {
            console.warn('Tema tidak dapat disimpan:', error);
        }

        if (themeIcon && themeToggle) {

            if (selectedTheme === 'dark') {

                themeIcon.className = 'bi bi-sun-fill';

                themeToggle.setAttribute(
                    'aria-label',
                    'Aktifkan light mode'
                );

                themeToggle.setAttribute(
                    'title',
                    'Ganti ke Light Mode'
                );

            } else {

                themeIcon.className = 'bi bi-moon-stars-fill';

                themeToggle.setAttribute(
                    'aria-label',
                    'Aktifkan dark mode'
                );

                themeToggle.setAttribute(
                    'title',
                    'Ganti ke Dark Mode'
                );
            }
        }
    }

    // Ambil tema yang tersimpan
    let savedTheme = 'light';

    try {
        savedTheme = localStorage.getItem('studenttrack-theme') || 'light';
    } catch (error) {
        console.warn('Tidak dapat membaca preferensi tema.');
    }

    applyTheme(savedTheme);

    // Tombol ganti tema
    if (themeToggle) {

        themeToggle.addEventListener('click', function () {

            const currentTheme =
                document.documentElement.getAttribute('data-theme');

            const newTheme =
                currentTheme === 'dark' ? 'light' : 'dark';

            applyTheme(newTheme);
        });
    }


    // =====================================
    // 2. SIDEBAR MOBILE
    // =====================================

    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (sidebar && sidebarToggle && sidebarOverlay) {

        function openSidebar() {

            sidebar.classList.add('show');
            sidebarOverlay.classList.add('show');

            sidebarToggle.setAttribute(
                'aria-expanded',
                'true'
            );

            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {

            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');

            sidebarToggle.setAttribute(
                'aria-expanded',
                'false'
            );

            document.body.style.overflow = '';
        }

        sidebarToggle.addEventListener('click', function () {

            if (sidebar.classList.contains('show')) {
                closeSidebar();
            } else {
                openSidebar();
            }

        });

        sidebarOverlay.addEventListener('click', closeSidebar);

        document.addEventListener('keydown', function (event) {

            if (event.key === 'Escape') {
                closeSidebar();
            }

        });

        window.addEventListener('resize', function () {

            if (window.innerWidth >= 992) {
                closeSidebar();
            }

        });

        sidebar.querySelectorAll('.sidebar-link').forEach(function (link) {

            link.addEventListener('click', function () {

                if (window.innerWidth < 992) {
                    closeSidebar();
                }

            });

        });
    }


    // =====================================
    // 3. CONFIRM DELETE
    // =====================================

    document.querySelectorAll('[data-confirm]').forEach(function (element) {

        element.addEventListener('click', function (event) {

            const message = this.getAttribute('data-confirm') ||
                'Apakah kamu yakin ingin menghapus data ini?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }

        });

    });


    // =====================================
    // 4. FILE INPUT VALIDATION VISUAL
    // =====================================

    document.querySelectorAll('input[type="file"]').forEach(function (input) {

        input.addEventListener('change', function () {

            if (this.files.length > 0) {
                this.classList.remove('is-invalid');
            }

        });

    });


    // =====================================
    // 5. AUTO RESIZE TEXTAREA
    // =====================================

    document.querySelectorAll('textarea[data-auto-resize]').forEach(
        function (textarea) {

            function resizeTextarea() {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            }

            textarea.addEventListener('input', resizeTextarea);

            resizeTextarea();
        }
    );

});


/* =====================================================
   STUDENTTRACK - GLOBAL PAGE ANIMATION
   Berlaku untuk semua halaman termasuk Login
===================================================== */

document.addEventListener('DOMContentLoaded', function () {

    // Aktifkan animasi halaman
    document.body.classList.add('st-page-ready');


    /* ================================================
       ANIMASI ELEMEN SAAT SCROLL
    ================================================ */

    const animatedElements = document.querySelectorAll(
        '.section-heading, ' +
        '.dashboard-stat, ' +
        '.dashboard-panel, ' +
        '.progress-panel, ' +
        '.dashboard-report-panel, ' +
        '.quick-action-card, ' +
        '.card, ' +
        '.login-card, ' +
        '.auth-card, ' +
        '.table-responsive'
    );

    if ('IntersectionObserver' in window) {

        const observer = new IntersectionObserver(
            function (entries, currentObserver) {

                entries.forEach(function (entry) {

                    if (entry.isIntersecting) {

                        entry.target.classList.add('st-element-visible');

                        currentObserver.unobserve(entry.target);
                    }

                });

            },
            {
                threshold: 0.12
            }
        );

        animatedElements.forEach(function (element, index) {

            element.style.setProperty(
                '--st-animation-delay',
                (index % 4) * 80 + 'ms'
            );

            observer.observe(element);

        });
    }


    /* ================================================
       ANIMASI TOMBOL
    ================================================ */

    document.querySelectorAll(
        '.btn, .hero-btn, .dashboard-primary-btn'
    ).forEach(function (button) {

        button.addEventListener('mousedown', function () {
            button.classList.add('st-button-active');
        });

        ['mouseup', 'mouseleave'].forEach(function (eventName) {

            button.addEventListener(eventName, function () {
                button.classList.remove('st-button-active');
            });

        });

    });


    /* ================================================
       ANIMASI FORM LOGIN DAN FORM LAINNYA
    ================================================ */

    document.querySelectorAll(
        '.login-card input, ' +
        '.auth-card input, ' +
        '.form-control, ' +
        '.form-select'
    ).forEach(function (input) {

        input.addEventListener('focus', function () {
            input.classList.add('st-input-focused');
        });

        input.addEventListener('blur', function () {
            input.classList.remove('st-input-focused');
        });

    });


    /* ================================================
       ANIMASI PROGRESS BAR
    ================================================ */

    const progressElements = document.querySelectorAll(
        '.progress-fill, .progress-bar'
    );

    if ('IntersectionObserver' in window) {

        const progressObserver = new IntersectionObserver(
            function (entries, currentObserver) {

                entries.forEach(function (entry) {

                    if (entry.isIntersecting) {

                        const element = entry.target;

                        const targetWidth = element.style.width;

                        if (targetWidth) {

                            element.style.width = '0%';

                            requestAnimationFrame(function () {

                                requestAnimationFrame(function () {
                                    element.style.width = targetWidth;
                                });

                            });

                        }

                        currentObserver.unobserve(element);
                    }

                });

            },
            {
                threshold: 0.4
            }
        );

        progressElements.forEach(function (element) {
            progressObserver.observe(element);
        });

    }

});
