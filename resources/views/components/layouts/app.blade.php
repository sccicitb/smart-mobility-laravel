<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Smart Mobility' }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/IC_SMART MOBILITY.png') }}" title="Smart Mobility Icon">
    <link rel="apple-touch-icon" href="{{ asset('images/IC_SMART MOBILITY.png') }}" title="Smart Mobility Icon">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/IC_SMART MOBILITY.png') }}" title="Smart Mobility Icon">

    <!-- Bootstrap CSS (used in legacy views: cctv-stream, simulator, download-modal) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Video.js -->
    <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
    <script src="https://unpkg.com/video.js/dist/video.js" defer></script>

    <!-- Third-party libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom-app.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl/dist/maplibre-gl.css" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <script src="https://unpkg.com/maplibre-gl/dist/maplibre-gl.js" defer></script>
    <script src="https://cdn.socket.io/4.5.4/socket.io.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@motionone/dom/dist/motion.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest" defer></script>

    @stack('styles')
</head>

<body class="bg-gray-50">

    @if (Auth::check() && !request()->is('login'))
        <!-- Main wrapper with sidebar -->
        <div class="flex w-full">
            @include('livewire.layout.sidebar')

            <!-- Main content area -->
            <div id="main-content" class="flex-1 min-h-screen">
                @include('livewire.layout.navbar')

                <!-- Page content -->
                <main id="page-content" class="pt-20 pb-8 {{ request()->routeIs('maps') ? 'p-0 pt-16' : 'pl-6' }} transition-all duration-300 ease-in-out">
                    {{ $slot }}
                </main>
            </div>
        </div>
    @else
        <div class="flex items-center justify-center min-h-screen bg-gray-50">
            {{ $slot }}
        </div>
    @endif

    @livewireScripts

    <script>
        // Initialize Lucide icons
        function initLucide() {
            if (window.lucide) {
                try { lucide.createIcons(); } catch(e) { console.error('Lucide error:', e); }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initLucide();

            const sidebar = document.getElementById('sidebar');
            const navbar  = document.getElementById('navbar');
            const pageContent = document.getElementById('page-content');

            function updateLayout() {
                if (!sidebar || !navbar || !pageContent) return;
                const collapsed = sidebar.classList.contains('collapsed');
                const offset = collapsed ? '80px' : '256px';
                navbar.style.left  = offset;
                navbar.style.width = `calc(100% - ${offset})`;
                pageContent.style.marginLeft = offset;
                pageContent.style.width = `calc(100% - ${offset})`;
            }

            updateLayout();

            if (sidebar) {
                new MutationObserver(updateLayout).observe(sidebar, {
                    attributes: true, attributeFilter: ['class']
                });
            }
        });

        document.addEventListener('livewire:navigated', initLucide);
    </script>

    @stack('scripts')

</body>

</html>
