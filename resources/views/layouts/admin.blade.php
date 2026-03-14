@php
    // Standard asset paths after refactor
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="License Manager Dashboard">
    <meta name="author" content="Enterprise">

    <title>{{ $title ?? 'Dashboard' }} - License Manager</title>

    <!-- color-modes:js -->
    <script src="{{ asset('assets/js/color-modes-CkunOepb.js') }}"></script>

    <script>
        (function () {
            // New theme toggle logic
            // Default to dark since current UI is dark
            const theme = localStorage.getItem('admin-theme') || 'dark';
            // Also keep the existing BS theme attribute just in case bootstrap relies on it
            const bsTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', bsTheme);
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- End fonts -->

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- Splash Screen -->
    <link href="{{ asset('assets/css/splash-screen.css') }}" rel="stylesheet" />

    <!-- plugin css -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/flag-icons/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}">
    <!-- end plugin css -->

    <!-- common css -->
    <link rel="stylesheet" href="{{ asset('assets/css/app-B-efjZPS.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-tn0RQdqM.css') }}">
    <!-- end common css -->

    @stack('custom-styles')
    @yield('styles')
    <style>
        /* Fix for dropdowns being clipped in responsive tables */
        .table-responsive {
            overflow: visible !important;
        }

        @media (max-width: 991px) {
            .table-responsive {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
            }
        }

        /* --- THEME TOGGLE STYLES --- */
        body {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        /* Dark Mode (Default Theme - using existing colors) */
        body.dark-mode {
            --bg-color: #0c1427; /* From existing body bg */
            --card-bg: #111c34;
            --text-color: #e4e9f2;
            --border-color: #1e2d4a;
            --sidebar-bg: #111c34;
            --navbar-bg: rgba(17, 28, 52, 0.9);
            --table-bg: transparent;
            --modal-bg: #111c34;
            --input-bg: #0c1427;
            --input-border: #233554;
            --text-muted: #8b9eb7;
        }

        /* Light Mode Overrides */
        body.light-mode {
            --bg-color: #f4f7fa;
            --card-bg: #ffffff;
            --text-color: #333333;
            --border-color: #e3e8ee;
            --sidebar-bg: #ffffff;
            --navbar-bg: rgba(255, 255, 255, 0.95);
            --table-bg: #ffffff;
            --modal-bg: #ffffff;
            --input-bg: #f9fbfd;
            --input-border: #d2dce6;
            --text-muted: #6c757d;
        }

        /* Apply Theme Variables */
        body.dark-mode, body.light-mode {
            background-color: var(--bg-color) !important;
            color: var(--text-color) !important;
        }

        body.dark-mode .page-content, body.light-mode .page-content {
            background-color: var(--bg-color) !important;
        }
        
        body.dark-mode .sidebar, body.light-mode .sidebar {
            background-color: var(--sidebar-bg) !important;
            border-right: 1px solid var(--border-color) !important;
        }

        body.dark-mode .navbar, body.light-mode .navbar {
            background-color: var(--navbar-bg) !important;
            border-bottom: 1px solid var(--border-color) !important;
        }

        body.dark-mode .card, body.light-mode .card {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }

        body.dark-mode .card-header, body.light-mode .card-header,
        body.dark-mode .card-footer, body.light-mode .card-footer {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }

        body.dark-mode .table, body.light-mode .table {
            color: var(--text-color) !important;
            border-color: var(--border-color) !important;
        }
        
        body.dark-mode .table td, body.dark-mode .table th,
        body.light-mode .table td, body.light-mode .table th {
            border-color: var(--border-color) !important;
            background-color: var(--table-bg) !important;
            color: var(--text-color) !important;
        }

        body.dark-mode .modal-content, body.light-mode .modal-content {
            background-color: var(--modal-bg) !important;
            border-color: var(--border-color) !important;
            color: var(--text-color) !important;
        }

        body.dark-mode .modal-header, body.light-mode .modal-header,
        body.dark-mode .modal-footer, body.light-mode .modal-footer {
            border-color: var(--border-color) !important;
        }

        body.dark-mode .form-control, body.light-mode .form-control,
        body.dark-mode .form-select, body.light-mode .form-select {
            background-color: var(--input-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--text-color) !important;
        }

        body.dark-mode .form-control:focus, body.light-mode .form-control:focus,
        body.dark-mode .form-select:focus, body.light-mode .form-select:focus {
            background-color: var(--card-bg) !important;
            color: var(--text-color) !important;
        }

        body.dark-mode .input-group-text, body.light-mode .input-group-text {
            background-color: var(--card-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--text-muted) !important;
        }

        body.dark-mode .text-muted, body.light-mode .text-muted,
        body.dark-mode .text-secondary, body.light-mode .text-secondary {
            color: var(--text-muted) !important;
        }

        body.dark-mode .dropdown-menu, body.light-mode .dropdown-menu {
            background-color: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }

        body.dark-mode .dropdown-item, body.light-mode .dropdown-item {
            color: var(--text-color) !important;
        }

        body.dark-mode .dropdown-item:hover, body.light-mode .dropdown-item:hover {
            background-color: var(--bg-color) !important;
            color: var(--text-color) !important;
        }
        
        body.dark-mode .dropdown-divider, body.light-mode .dropdown-divider {
            border-top-color: var(--border-color) !important;
        }

        body.dark-mode .nav-link, body.light-mode .nav-link,
        body.dark-mode .sidebar-body .nav-item .nav-link, body.light-mode .sidebar-body .nav-item .nav-link,
        body.dark-mode .sidebar-body .nav-item .nav-link .link-title, body.light-mode .sidebar-body .nav-item .nav-link .link-title {
            color: var(--text-color);
        }

        body.dark-mode .sidebar-header .sidebar-brand, body.light-mode .sidebar-header .sidebar-brand {
            color: var(--text-color);
        }

        /* Ensure smooth transitions for structural elements */
        body, .page-content, .sidebar, .navbar, .card, .card-header, .card-footer, 
        .table, .table td, .table th, .modal-content, .modal-header, .modal-footer, 
        .form-control, .form-select, .input-group-text, .dropdown-menu, .dropdown-item {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
    </style>
</head>

<body>
    <script>
        // Anti-flicker: Apply theme immediately before content renders
        (function() {
            const savedTheme = localStorage.getItem('admin-theme') || 'dark';
            document.body.classList.add(savedTheme + '-mode');
            // If bootstrap theme was also being set, ensure it doesn't conflict
            if (savedTheme === 'light') {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            }
        })();
    </script>
    <script>
        var splash = document.createElement("div");
        splash.innerHTML = `
            <div class="splash-screen">
                <div class="logo"></div>
                <div class="spinner"></div>
            </div>`;
        document.body.insertBefore(splash, document.body.firstChild);
        document.addEventListener("DOMContentLoaded", function () {
            document.body.classList.add("loaded");
        });
    </script>

    <div class="main-wrapper" id="app">
        @include('layouts.partials.sidebar')

        <div class="page-wrapper">
            @include('layouts.partials.header')

            <div class="page-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>

            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- base js -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/app-CAiCLEjY.js') }}"></script>
    <script src="{{ asset('assets/plugins/lucide/lucide.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <!-- end base js -->

    <!-- plugin js -->
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ asset('assets/js/template-B7IAR9tB.js') }}"></script>
    <!-- end common js -->

    @stack('custom-scripts')
    @yield('scripts')

    <script>
        if (window.lucide) {
            lucide.createIcons();
        }

        // --- Theme Toggle Logic ---
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            
            if (themeToggleBtn && themeIcon) {
                // Determine initial logo based on theme
                const savedTheme = localStorage.getItem('admin-theme') || 'dark';
                updateLogoVisiblity(savedTheme);

                themeToggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const isDark = document.body.classList.contains('dark-mode');
                    const newTheme = isDark ? 'light' : 'dark';
                    
                    // Toggle body classes
                    document.body.classList.remove('dark-mode', 'light-mode');
                    document.body.classList.add(newTheme + '-mode');
                    
                    // Update bootstrap theme
                    document.documentElement.setAttribute('data-bs-theme', newTheme);
                    
                    // Save preference
                    localStorage.setItem('admin-theme', newTheme);
                    
                    // Update icon dynamically
                    if (newTheme === 'dark') {
                        themeIcon.setAttribute('data-lucide', 'sun');
                    } else {
                        themeIcon.setAttribute('data-lucide', 'moon');
                    }
                    
                    // Re-render lucide icons to apply new icon
                    if (window.lucide) {
                        lucide.createIcons();
                    }

                    // Update Logo visibility
                    updateLogoVisiblity(newTheme);
                });

                function updateLogoVisiblity(theme) {
                    const lightLogo = document.querySelector('.logo-mini-light');
                    const darkLogo = document.querySelector('.logo-mini-dark');
                    const sidebarLightLogo = document.querySelector('.sidebar-brand-light');
                    const sidebarDarkLogo = document.querySelector('.sidebar-brand-dark');

                    if (theme === 'light') {
                        if (lightLogo && darkLogo) {
                            lightLogo.style.display = 'block';
                            darkLogo.style.display = 'none';
                        }
                        if (sidebarLightLogo && sidebarDarkLogo) {
                            sidebarLightLogo.style.display = 'block';
                            sidebarDarkLogo.style.display = 'none';
                        }
                    } else {
                        if (lightLogo && darkLogo) {
                            lightLogo.style.display = 'none';
                            darkLogo.style.display = 'block';
                        }
                        if (sidebarLightLogo && sidebarDarkLogo) {
                            sidebarLightLogo.style.display = 'none';
                            sidebarDarkLogo.style.display = 'block';
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>