<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Smart Mobility' }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/IC_SMART MOBILITY.png') }}" title="Smart Mobility Icon">
    <link rel="apple-touch-icon" href="{{ asset('images/IC_SMART MOBILITY.png') }}" title="Smart Mobility Icon">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/IC_SMART MOBILITY.png') }}"
        title="Smart Mobility Icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Video.js CSS -->
    <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">

    <!-- Video.js JS -->
    <script src="https://unpkg.com/video.js/dist/video.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom-app.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl/dist/maplibre-gl.css" />
    <script src="https://unpkg.com/maplibre-gl/dist/maplibre-gl.js"></script>
    <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@motionone/dom/dist/motion.min.js"></script>
    @livewireStyles
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('styles')

</head>

<body>
    @livewireScripts

    @if (Auth::check() && !request()->is('login'))
        <div class="wrapper d-flex">
            <livewire:layout.sidebar />
            <div class="content-wrapper">
                <livewire:layout.navbar />
                {{-- <div class="pt-16"> --}}
                <main class="main-content w-full block {{ request()->routeIs('maps') ? 'map-wrapper' : '' }}">
                    <!-- <div class="content-card {{ request()->routeIs('maps') ? 'map-content' : '' }}"> -->
                    {{ $slot }}
                    <!-- </div> -->
                </main>
                {{-- </div> --}}
            </div>
        </div>
    @else
        <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
            {{ $slot }}
        </div>
    @endif

    <script>
        // Listen for sidebar toggle events
        Livewire.on('toggle-sidebar', (data) => {
            const contentWrapper = document.querySelector('.content-wrapper');
            if (data.collapsed) {
                contentWrapper.classList.add('collapsed');
            } else {
                contentWrapper.classList.remove('collapsed');
            }
        });
    </script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        smartblue: '#16598b',
                        emerald: '#10b981',
                    }
                }
            }
        }
    </script>

    <script>
        document.addEventListener("livewire:navigated", () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });

        document.addEventListener("livewire:load", () => {
            Livewire.hook('message.processed', (message, component) => {
                if (window.lucide) {
                    lucide.createIcons();
                }
            });
        });
    </script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>
