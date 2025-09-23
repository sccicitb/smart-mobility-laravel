<style>
    .navside-back {
        display: flex;
        gap: 10px;
    }

    .sidebar-side {
        position: sticky;
        top: 0;
    }

    .navside span {
        transition: opacity 0.3s ease;
    }

    .collapsed .navside span {
        opacity: 0;
    }

    .navside {
        width: 90%;
        align-items: center;
        margin: 0 auto;
        outline: none;
        padding: 6px;
        border-radius: 6px;
        color: #fff;
        text-decoration: none;
        background-color: transparent;
        transition: all 0.3s ease;
    }

    .navside:hover {
        background-color: rgba(255, 255, 255, 0.1);
        text-decoration: none;
    }

    .navside-active {
        background-color: #fff;
        color: #892120 !important;
    }

    .navside-active i,
    .navside-active span {
        color: #892120 !important;
    }

    .navside:focus {
        outline: none;
    }

    .collapsed .nav-text {
        opacity: 0;
        width: 0;
        padding: 0;
        margin: 0;
    }

    .sidebar-side {
        width: 250px;
        transition: all 0.3s ease;
    }

    .sidebar-side.collapsed {
        width: 50px !important;
        padding: 0 5px;
    }

    /* Menyembunyikan teks navigasi */
    .collapsed .nav-text {
        opacity: 0;
        width: 0;
        padding: 0;
        margin: 0;
        transition: all 0.3s ease;
    }

    /* Lucide icon di tengah saat collapsed */
    .collapsed .navside i {
        margin-left: auto;
        margin-right: auto;
    }

    /* Logo lebih kecil saat collapsed */
    .sidebar-side.collapsed #sidebarLogo {
        max-height: 40px !important;
    }

    /* Optional animasi untuk logo */
    #sidebarLogo {
        transition: max-height 0.3s ease;
    }
</style>
<!-- @class(['collapsed' => $collapsed]) -->
<div class="sidebar-side"
    style="background: #892120; border-radius: 0px 0px 0px 0px; overflow: hidden; width: {{ $collapsed ? '70px' : '250px' }}; transition: all 0.3s ease;">
    <div class="d-flex flex-column align-items-stretch py-[10px] gap-2">
        <!-- Toggle Button -->
        <button type="button" class="btn d-flex align-items-center" onclick="toggleSidebar(this)"
            style="border-radius: 0px 16px 0px 0px; min-height: 64px; border: none;">


            <img id="sidebarLogo" src="{{ asset('images/IC_Smart Mobility_White.png') }}" alt="Smart Mobility"
                style="height: 60px;" onerror="this.style.display='none'">

            <div wire:ignore class="ms-auto">
                <i id="chevronIcon" data-lucide="chevron-left" class="text-white"></i>
            </div>
            <!-- <div wire:ignore class="ms-auto" onclick="toggleChevron(this, event)"> -->

        </button>

        <!-- Logo / Header -->

        <!-- Navigation Items -->
        <div class="navside-back flex-column">
            {{-- Dashboard --}}
            {{-- @php
                $isDashboardActive = Str::contains(url()->current(), 'dashboard/mobility');
            @endphp --}}
            {{-- <a href="http://63.250.52.19:9091/dashboard/mobility"
                class="navside d-flex align-items-center {{ $isDashboardActive ? 'navside-active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="layout-dashboard"></i>
                </div>
                <span class="nav-text ms-3 font-semibold">{{ $collapsed ? '' : 'Dashboard' }}</span>
            </a> --}}
            <a href="{{ route('dashboard') }}"
                class="navside d-flex align-items-center {{ request()->routeIs('dashboard') ? 'navside-active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="tv-minimal-play"></i>
                </div>
                <span class="nav-text ms-3 font-semibold">{{ $collapsed ? '' : 'Dashboard' }}</span>
            </a>

            {{-- Simulation --}}
            <a href="{{ route('simulations') }}"
                class="navside d-flex align-items-center {{ request()->routeIs('simulations') ? 'navside-active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="tv-minimal-play"></i>
                </div>
                <span class="nav-text ms-3 font-semibold">{{ $collapsed ? '' : 'Simulation' }}</span>
            </a>

            {{-- Maps --}}
            {{-- <a href="{{ route('maps') }}"
                class="navside d-flex align-items-center {{ request()->routeIs('maps') ? 'navside-active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="map"></i>
                </div>
                <span class="nav-text ms-3 font-semibold">{{ $collapsed ? '' : 'Maps' }}</span>
            </a>

            <a href="{{ route('tutorial') }}"
                class="navside d-flex align-items-center {{ request()->routeIs('tutorial') ? 'navside-active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="book-open"></i>
                </div>
                <span class="nav-text ms-3 font-semibold">{{ $collapsed ? '' : 'Tutorial' }}</span>
            </a>
            <a href="{{ route('settings') }}"
                class="navside d-flex align-items-center {{ request()->routeIs('settings') ? 'navside-active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="settings"></i>
                </div>
                <span class="nav-text ms-3 font-semibold">{{ $collapsed ? '' : 'Settings' }}</span>
            </a> --}}
            <a href="{{ route('logout') }}" class="navside d-flex align-items-center"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <div wire:ignore>
                    <i data-lucide="log-out"></i>
                </div>
                <span class="nav-text ms-3 font-semibold">{{ $collapsed ? '' : 'Logout' }}</span>
            </a> 
            <form id="logout-form" action="{{ route('logout') }}" method="GET" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.querySelector('.sidebar-side');
        const icon = document.getElementById('chevronIcon');
        const logo = document.getElementById('sidebarLogo');
        const collapsedState = localStorage.getItem('sidebarCollapsed');

        if (collapsedState === 'true') {
            sidebar.classList.add('collapsed');
            icon.setAttribute('data-lucide', 'chevron-right');
            lucide.createIcons();
            logo.setAttribute('src', "{{ asset('') }}");
            logo.setAttribute('style', 'max-height: 5px;')
        } else {
            logo.setAttribute('src', "{{ asset('images/IC_Smart Mobility_White.png') }}");
            logo.setAttribute('style', 'max-height: 60px;');
        }

        if (logo.getAttribute('src') === '') {
            logo.style.display = 'none';
        } else {
            logo.style.display = 'block';
        }

        lucide.createIcons();
    });

    function toggleSidebar (button) {
        const sidebar = button.closest('.sidebar-side');
        const icon = document.getElementById('chevronIcon');
        const logo = document.getElementById('sidebarLogo');
        // $collapsed = !$collapsed
        // Livewire.emit('toggleSidebar'); // Trigger ke Livewire component
        const isCollapsed = sidebar.classList.toggle('collapsed');

        // Ganti ikon panah
        icon.setAttribute('data-lucide', isCollapsed ? 'chevron-right' : 'chevron-left');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
        lucide.createIcons();

        // Ganti logo
        logo.setAttribute('src', isCollapsed
            ? "{{ asset('') }}"
            : "{{ asset('images/IC_Smart Mobility_White.png') }}"
        );
        logo.setAttribute('style', isCollapsed
            ? 'max-height: 5px;'
            : 'max-height: 60px;'
        );

    }
</script>