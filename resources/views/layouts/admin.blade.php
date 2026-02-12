<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'License Manager' }} - Enterprise License System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">

    @yield('styles')
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column p-0 flex-shrink-0 shadow" id="sidebar" style="width: 250px;">
            <div class="p-4 d-flex justify-content-between align-items-center">
                <h4 class="text-white fw-bold mb-0">LicenseServer</h4>
                <button class="btn border-0 text-white d-lg-none" id="sidebarClose">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <ul class="nav flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>

                @if(auth()->user()->isSuperAdmin())
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fa fa-users me-2"></i> Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.plans.index') }}"
                            class="nav-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                            <i class="fa fa-layer-group me-2"></i> Plans
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('admin.licenses.index') }}"
                        class="nav-link {{ request()->routeIs('admin.licenses.*') ? 'active' : '' }}">
                        <i class="fa fa-key me-2"></i> Licenses
                    </a>
                </li>

                <!-- Quick Generate for Admins/Distributors -->
                @if(auth()->user()->role !== 'client')
                    <li class="nav-item ms-3 my-2">
                        <a href="{{ route('admin.licenses.create') }}"
                            class="btn btn-info btn-sm rounded-pill px-3 shadow-sm">
                            <i class="fa fa-plus-circle me-1"></i> Generate
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('admin.domains.index') }}"
                        class="nav-link {{ request()->routeIs('admin.domains.*') ? 'active' : '' }}">
                        <i class="fa fa-globe me-2"></i> Domains
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.logs.index') }}"
                        class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                        <i class="fa fa-history me-2"></i> Logs
                    </a>
                </li>

                @if(auth()->user()->role !== 'client')
                    <li class="nav-item">
                        <a href="{{ route('admin.users.quota_history') }}"
                            class="nav-link {{ request()->routeIs('admin.users.quota_history') ? 'active' : '' }}">
                            <i class="fa fa-chart-line me-2"></i> Quota History
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('admin.api_docs') }}"
                        class="nav-link {{ request()->routeIs('admin.api_docs') ? 'active' : '' }}">
                        <i class="fa fa-book me-2"></i> API Docs
                    </a>
                </li>
            </ul>

            <hr class="mx-3 text-white-50">

            <div class="p-3">
                <a href="{{ route('logout') }}" class="nav-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out-alt me-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 main-content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg px-4 mb-4 sticky-top">
                <div class="container-fluid p-0">
                    <button class="btn btn-light rounded-circle shadow-sm me-3 d-lg-none" id="sidebarToggle">
                        <i class="fa fa-bars"></i>
                    </button>

                    <h5 class="mb-0 fw-bold d-none d-sm-block">{{ $title ?? 'Dashboard' }}</h5>

                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                                id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2 fw-medium d-none d-sm-inline">{{ auth()->user()->name }}</span>
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px;">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-10"
                                aria-labelledby="userMenu">
                                <li><a class="dropdown-item p-2" href="#"><i class="fa fa-user me-2 opacity-50"></i>
                                        Profile</a></li>
                                <li><a class="dropdown-item p-2" href="#"><i class="fa fa-cog me-2 opacity-50"></i>
                                        Settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item p-2 text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="px-4 pb-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-10 border-0 shadow-sm" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-10 border-0 shadow-sm" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function () {
            $('#sidebarToggle, #sidebarClose').on('click', function () {
                $('#sidebar').toggleClass('show');
            });
        });
    </script>

    @yield('scripts')
</body>

</html>