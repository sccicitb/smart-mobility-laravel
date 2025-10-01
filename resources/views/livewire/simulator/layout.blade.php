<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulator-Intersection</title>

    <!-- Bootstrapt CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add AOS CSS in head -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireStyles
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <link rel="stylesheet" href="{{ asset('css/custom-app.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('styles')

</head>

<body style="background: url('{{ asset('images/bg.png') }}') no-repeat center center fixed; background-size: cover;">
    {{-- @include('livewire.tutorial.nav-tutorial') --}}
    <div class="wrapper d-flex">
        <livewire:layout.sidebar />
        <div class="content-wrapper">
            <livewire:layout.navbar />
            <main class="main-content w-full block">
                <div class="container-fluid intersection-sim-section">
                    <div class="intersection-sim-box">
                        <h2 class="text-center">Intersection Simulator</h2>
                        @yield('content')
                    </div>
                </div>
            </main>
            </livewire:layout.navbar>
        </div>
        </livewire:layout.sidebar>
    </div>
</body>
