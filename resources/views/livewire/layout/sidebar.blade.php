<div class="sidebar" @class(['collapsed' => $collapsed])
    style="background: #7585C2; border-radius: 0px 0px 0px 0px; overflow: hidden; width: {{ $collapsed ? '70px' : '250px' }}; transition: all 0.3s ease;">
    <div class="d-flex flex-column align-items-stretch">
        <!-- Toggle Button -->
        <button type="button" class="btn d-flex align-items-center p-3" wire:click="toggle"
            style="background: #002F34; border-radius: 0px 16px 0px 0px; min-height: 64px; border: none;">
            <span class="sidebar-heading text-white" style="display: {{ $collapsed ? 'none' : 'inline' }}">
                <img src="{{ asset('images/IC_SMART MOBILITY.png') }}" alt="Smart Mobility" style="max-height: 40px;">
            </span>
            <div wire:ignore class="ms-auto">
                <i data-lucide="{{ $collapsed ? 'chevron-left' : 'chevron-right' }}" class="text-white"></i>
            </div>
        </button>

        <!-- Navigation Items -->
        <div class="nav flex-column">    
            <a href="http://63.250.52.19:9091/dashboard/mobility" class="nav-link d-flex align-items-center">
                <div wire:ignore>
                    <i data-lucide="layout-dashboard" class="text-white"></i>
                </div>
                <span class="text-white ms-3" style="display: {{ $collapsed ? 'none' : 'inline' }}">Dashboard</span>
            </a>
            <a href="{{ route('simulations') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('simulations') ? 'active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="tv-minimal-play" class="text-white"></i>
                </div>
                <span class="text-white ms-3" style="display: {{ $collapsed ? 'none' : 'inline' }}">Simulation</span>
            </a>
            <a href="{{ route('maps') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('maps') ? 'active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="map" class="text-white"></i>
                </div>
                <span class="text-white ms-3" style="display: {{ $collapsed ? 'none' : 'inline' }}">Maps</span>
            </a>
            {{-- <a href="{{ route('survey') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('maps') ? 'active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="binoculars" class="text-white"></i>
                </div>
                <span class="text-white ms-3" style="display: {{ $collapsed ? 'none' : 'inline' }}">Survey</span>
            </a> --}}
            {{-- 
            <a href="{{ route('cameras') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('cameras') ? 'active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="video" class="text-white"></i>
                </div>
                <span class="text-white ms-3" style="display: {{ $collapsed ? 'none' : 'inline' }}">Cameras</span>
            </a>

            <a href="{{ route('traffic-flow') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('traffic-flow') ? 'active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="traffic-cone" class="text-white"></i>
                </div>
                <span class="text-white ms-3" style="display: {{ $collapsed ? 'none' : 'inline' }}">Traffic Flow</span>
            </a>

            <a href="{{ route('congestions') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('congestions') ? 'active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="alert-triangle" class="text-white"></i>
                </div>
                <span class="text-white ms-3" style="display: {{ $collapsed ? 'none' : 'inline' }}">Congestion</span>
            </a>

            <a href="{{ route('intersections') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('intersections') ? 'active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="crosshair" class="text-white"></i>
                </div>
                <span class="text-white ms-3"
                    style="display: {{ $collapsed ? 'none' : 'inline' }}">Intersections</span>
            </a>

            <a href="{{ route('travel-times') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('travel-times') ? 'active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="clock" class="text-white"></i>
                </div>
                <span class="text-white ms-3" style="display: {{ $collapsed ? 'none' : 'inline' }}">Travel Time</span>
            </a> --}}

            <a href="{{ route('tutorial') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('tutorial') ? 'active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="book-open" class="text-white"></i>
                </div>
                <span class="text-white ms-3" style="display: {{ $collapsed ? 'none' : 'inline' }}">Tutorial</span>
            </a>
            <a href="{{ route('settings') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('settings') ? 'active' : '' }}">
                <div wire:ignore>
                    <i data-lucide="settings" class="text-white"></i>
                </div>
                <span class="text-white ms-3" style="display: {{ $collapsed ? 'none' : 'inline' }}">Settings</span>
            </a>
        </div>
    </div>
</div>

