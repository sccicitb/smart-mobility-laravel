<style>
    html, body {
        overflow-x: hidden;
    }

    #sidebar {
        width: 256px;
        height: 100vh;
        display: flex;
        flex-direction: column;
        background: #7c2d2d;
        transition: width 0.3s ease;
    }

    #sidebar.collapsed {
        width: 80px;
    }

    /* Header */
    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        border-bottom: 1px solid #5c1f1f;
        height: 64px;
        flex-shrink: 0;
    }

    #sidebar.collapsed .sidebar-header {
        justify-content: center;
        padding: 12px 8px;
    }

    .sidebar-logo {
        height: 40px;
        width: auto;
    }

    #sidebar.collapsed .sidebar-logo {
        display: none;
    }

    .sidebar-toggle {
        background: none;
        border: none;
        padding: 8px;
        cursor: pointer;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .sidebar-toggle:hover {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 6px;
    }

    .sidebar-toggle svg {
        width: 20px;
        height: 20px;
    }

    /* Nav */
    .sidebar-nav {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 12px 8px;
        gap: 8px;
    }

    #sidebar.collapsed .sidebar-nav {
        padding: 12px 8px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 12px 16px;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s;
        white-space: nowrap;
        cursor: pointer;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .nav-link.active {
        background: white;
        color: #7c2d2d;
        font-weight: 600;
    }

    .nav-link svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        margin-right: 12px;
    }

    .nav-link span {
        font-size: 14px;
        font-weight: 500;
    }

    #sidebar.collapsed .nav-link {
        justify-content: center;
        padding: 12px 8px;
    }

    #sidebar.collapsed .nav-link svg {
        margin-right: 0;
        width: 24px;
        height: 24px;
    }

    #sidebar.collapsed .nav-link span {
        display: none;
    }

    /* Tooltip */
    #sidebar.collapsed .nav-link {
        position: relative;
    }

    #sidebar.collapsed .nav-link:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        left: calc(100% + 10px);
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.9);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 100;
    }

    /* Footer */
    .sidebar-footer {
        border-top: 1px solid #5c1f1f;
        padding: 8px;
        flex-shrink: 0;
    }

    .sidebar-footer .nav-link {
        color: white;
    }
</style>

<!-- Sidebar -->
<aside id="sidebar" class="fixed left-0 top-0 bg-red-900 text-white z-40">
    <!-- Header -->
    <div class="sidebar-header">
        <img src="{{ asset('images/IC_Smart Mobility_White.png') }}" alt="Smart Mobility" class="sidebar-logo">
        <button id="sidebarToggle" class="sidebar-toggle" aria-label="Toggle sidebar">
            <svg id="chevronIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" data-tooltip="Dashboard" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V3"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('simulations') }}" data-tooltip="Simulation" class="nav-link {{ request()->routeIs('simulations') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Simulation</span>
        </a>

        <a href="{{ route('simulasi-rute') }}" data-tooltip="Simulasi Rute" class="nav-link {{ request()->routeIs('simulasi-rute') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
            </svg>
            <span>Simulasi Rute</span>
        </a>

        <a href="{{ route('transportasi-publik') }}" data-tooltip="Transportasi Publik" class="nav-link {{ request()->routeIs('transportasi-publik') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>
            <span>Transportasi Publik</span>
        </a>

        <a href="{{ route('kendaraan-otonom') }}" data-tooltip="Kendaraan Otonom" class="nav-link {{ request()->routeIs('kendaraan-otonom') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"></path>
            </svg>
            <span>Kendaraan Otonom</span>
        </a>
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
        <form id="logoutForm" action="{{ route('logout') }}" method="GET" class="hidden">@csrf</form>
        <button onclick="document.getElementById('logoutForm').submit()" data-tooltip="Logout" class="nav-link w-full">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <span>Logout</span>
        </button>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        const chevron = document.getElementById('chevronIcon');

        if (!sidebar || !toggle || !chevron) return;

        // Restore sidebar state from localStorage
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            chevron.style.transform = 'scaleX(-1)';
        }

        // Toggle sidebar on button click
        toggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
            chevron.style.transform = isCollapsed ? 'scaleX(-1)' : 'scaleX(1)';
        });
    });
</script>