<nav class="navbar">
    <div class="navbar-content">

        <div class="logo-mini-wrapper">
            <img src="{{ asset('assets/images/logo-mini-light.png') }}" class="logo-mini logo-mini-light" alt="logo">
            <img src="{{ asset('assets/images/logo-mini-dark.png') }}" class="logo-mini logo-mini-dark" alt="logo">
        </div>

        <form class="search-form">
            <div class="input-group">
                <div class="input-group-text">
                    <i data-lucide="search"></i>
                </div>
                <input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
            </div>
        </form>

        <ul class="navbar-nav">
            <!-- Theme Toggle Button -->
            <li class="nav-item">
                <a class="nav-link" href="#" id="theme-toggle" title="Toggle Theme">
                    <script>
                        // Synchronous script to immediately output the right icon to avoid flicker
                        (function() {
                            const initialTheme = localStorage.getItem('admin-theme') || 'dark';
                            // If dark mode, show sun. If light mode, show moon.
                            if (initialTheme === 'dark') {
                                document.write('<i id="theme-icon" data-lucide="sun"></i>');
                            } else {
                                document.write('<i id="theme-icon" data-lucide="moon"></i>');
                            }
                        })();
                    </script>
                </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="w-30px h-30px ms-1 rounded-circle shadow-sm"
                        src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=4B49AC&color=fff' }}"
                        alt="profile">
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                    <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                        <div class="mb-3">
                            <img class="w-80px h-80px rounded-circle shadow-sm"
                                src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=4B49AC&color=fff' }}"
                                alt="">
                        </div>
                        <div class="text-center">
                            <p class="fs-16px fw-bolder">{{ auth()->user()->full_name ?? auth()->user()->name }}</p>
                            <p class="fs-12px text-secondary">{{ auth()->user()->email }}</p>
                            <p class="badge bg-primary mt-1">{{ strtoupper(auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <ul class="list-unstyled p-1">
                        <li>
                            <a href="{{ route('admin.profile.edit') }}" class="dropdown-item py-2 text-body ms-0">
                                <i class="me-2 icon-md" data-lucide="user"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" class="dropdown-item py-2 text-danger ms-0"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="me-2 icon-md" data-lucide="log-out"></i>
                                <span>Log Out</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        <a href="#" class="sidebar-toggler">
            <i data-lucide="menu"></i>
        </a>

    </div>
</nav>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>