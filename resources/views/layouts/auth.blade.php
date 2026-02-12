@php
    $assetBaseUrl = asset('nobleui_extracted/NobleUI-Laravel-v3.0.1/template/demo1/public/build/');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LicenseServer - Enterprise Authentication</title>

    <script src="{{ $assetBaseUrl . '/assets/color-modes-CkunOepb.js' }}"></script>
    <script>
        (function () {
            const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="shortcut icon"
        href="{{ asset('nobleui_extracted/NobleUI-Laravel-v3.0.1/template/demo1/public/favicon.ico') }}">

    <!-- Splash Screen -->
    <link href="{{ asset('nobleui_extracted/NobleUI-Laravel-v3.0.1/template/demo1/public/splash-screen.css') }}"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ $assetBaseUrl . '/assets/app-B-efjZPS.css' }}">
    <link rel="stylesheet" href="{{ $assetBaseUrl . '/assets/custom-tn0RQdqM.css' }}">

    @stack('custom-styles')
</head>

<body>
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
        <div class="page-wrapper full-page">
            <div class="page-content container-xxl d-flex align-items-center justify-content-center">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ $assetBaseUrl . '/plugins/bootstrap/bootstrap.bundle.min.js' }}"></script>
    <script src="{{ $assetBaseUrl . '/assets/app-CAiCLEjY.js' }}"></script>
    <script
        src="{{ asset('nobleui_extracted/NobleUI-Laravel-v3.0.1/template/demo1/public/build/plugins/lucide/lucide.min.js') }}"></script>
    <script src="{{ $assetBaseUrl . '/assets/template-B7IAR9tB.js' }}"></script>

    @stack('custom-scripts')
    <script>
        if (window.lucide) {
            lucide.createIcons();
        }
    </script>
</body>

</html>