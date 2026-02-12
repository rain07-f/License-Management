@php
    $sidebarBaseUrl = asset('nobleui_extracted/NobleUI-Laravel-v3.0.1/template/demo1/public/build/');
@endphp
<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            License<span>Manager</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav" id="sidebarNav">
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="link-icon" data-lucide="home"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            <li class="nav-item nav-category">Core Management</li>
            <li class="nav-item {{ request()->routeIs('admin.licenses.*') ? 'active' : '' }}">
                <a href="{{ route('admin.licenses.index') }}" class="nav-link">
                    <i class="link-icon" data-lucide="key"></i>
                    <span class="link-title">Licenses</span>
                </a>
            </li>

            @if(auth()->user()->isSuperAdmin())
                <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="link-icon" data-lucide="users"></i>
                        <span class="link-title">User Management</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.plans.index') }}" class="nav-link">
                        <i class="link-icon" data-lucide="layers"></i>
                        <span class="link-title">Plans</span>
                    </a>
                </li>
            @endif

            <li class="nav-item {{ request()->routeIs('admin.domains.*') ? 'active' : '' }}">
                <a href="{{ route('admin.domains.index') }}" class="nav-link">
                    <i class="link-icon" data-lucide="globe"></i>
                    <span class="link-title">Activated Domains</span>
                </a>
            </li>

            <li class="nav-item nav-category">Advanced</li>
            <li class="nav-item {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                <a href="{{ route('admin.logs.index') }}" class="nav-link">
                    <i class="link-icon" data-lucide="history"></i>
                    <span class="link-title">System Logs</span>
                </a>
            </li>

            @if(auth()->user()->role !== 'client')
                <li class="nav-item {{ request()->routeIs('admin.users.quota_history') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.quota_history') }}" class="nav-link">
                        <i class="link-icon" data-lucide="trending-up"></i>
                        <span class="link-title">Quota History</span>
                    </a>
                </li>
            @endif

            <li class="nav-item {{ request()->routeIs('admin.api_docs') ? 'active' : '' }}">
                <a href="{{ route('admin.api_docs') }}" class="nav-link">
                    <i class="link-icon" data-lucide="book-open"></i>
                    <span class="link-title">API Documentation</span>
                </a>
            </li>



            <li class="nav-item nav-category">Account</li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="link-icon text-danger" data-lucide="log-out"></i>
                    <span class="link-title text-danger">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>