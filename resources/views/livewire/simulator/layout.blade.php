<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulator — Intersection</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <script src="https://code.highcharts.com/highcharts.js" defer></script>
    <link rel="stylesheet" href="{{ asset('css/custom-app.css') }}">
    <script src="https://unpkg.com/lucide@latest" defer></script>
    @stack('styles')
</head>

<body class="bg-gray-50 bg-cover bg-center bg-fixed"
    style="background-image: url('{{ asset('images/bg.png') }}')">

    <div class="flex w-full min-h-screen">

        <livewire:layout.sidebar />

        <div class="flex-1 flex flex-col min-h-screen">
            <livewire:layout.navbar />

            <main class="flex-1 pt-20 pb-8 px-6 transition-all duration-300 ease-in-out">
                <div class="w-full">
                    {{-- Section header --}}
                    <div class="mb-6">
                        <h1 class="text-xl font-bold text-gray-800">Intersection Simulator</h1>
                        <p class="text-sm text-gray-500 mt-0.5">Simulasi dan analisis kapasitas simpang</p>
                    </div>

                    {{-- Page content --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>

    </div>
</body>
