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
            const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', theme);
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
        
        /* Sidebar toggle transition */
        .sidebar {
            transition: width 0.3s ease, left 0.3s ease;
        }
        .page-wrapper {
            transition: margin-left 0.3s ease;
        }
    </style>
</head>

<body class="">
    <script>
        if (localStorage.getItem('sidebar-folded') === 'true' && window.innerWidth >= 992) {
            document.body.classList.add('sidebar-folded');
        }
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
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle logic
            const body = document.body;
            const sidebarTogglers = document.querySelectorAll('.sidebar-toggler');
            
            // Initial state from localStorage
            if (localStorage.getItem('sidebar-folded') === 'true' && window.innerWidth >= 992) {
                body.classList.add('sidebar-folded');
                // Set togglers to active state if needed (for sidebar header toggler)
                document.querySelectorAll('.sidebar-header .sidebar-toggler').forEach(t => t.classList.add('active'));
            }

            sidebarTogglers.forEach(toggler => {
                toggler.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (window.innerWidth >= 992) {
                        // Desktop: narrow/folded sidebar
                        body.classList.toggle('sidebar-folded');
                        this.classList.toggle('active');
                        localStorage.setItem('sidebar-folded', body.classList.contains('sidebar-folded'));
                    } else {
                        // Mobile: show/hide sidebar
                        body.classList.toggle('sidebar-open');
                        this.classList.toggle('active');
                    }
                });
            });

            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>