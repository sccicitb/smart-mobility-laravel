<div class="content-card">
    <div class="position-relative overflow-hidden p-2 page" style=" min-height: 80vh; height: 100%; margin: auto'">
        <div class="container-fluid h-100 d-flex align-items-center justify-content-center">
            <div class="row w-100 justify-content-center">
                <div class="col-12 col-xl-12 wrapping">
                    <div class="display-6 text-center fw-bold title-topic">Which Topics Do You Want to Simulate?</div>

                    <!-- <div class="content-card horizontal-accordion mx-auto custom-card"> -->
                    <div class="custom-card-wrapper">
                        @foreach ($simulations as $simulation)
                            <div class="card"
                                style="background-image: url('{{ asset('images/bg.png') }}'); background-size: cover;">
                                <div class="card-overlay"></div>
                                <div class="card-header border-0 d-flex justify-content-center align-items-center">
                                    <img src="{{ asset('images/IC2_SMART MOBILITY.png') }}" alt="Logo"
                                        class="logo transition-all" style="width: 50px;">
                                </div>
                                <h1 class="card-initial-title">{{ $simulation['title'] }}</h1>

                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <h3 class="card-title text-white mb-4">{{ $simulation['title'] }}</h3>
                                        <p class="card-text text-white">{{ $simulation['description'] }}</p>
                                    </div>
                                    <a href="{{ route($simulation['route']) }}" class="btn btn-light btn-custom px-3 py-1">
                                        Find {{ $simulation['title'] }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* .custom-card-wrapper {
                                display: flex;
                                gap: 1rem;
                                overflow-x: none;
                                padding-bottom: 1rem;
                            } */

            .custom-card-wrapper {
                justify-content: center;
                /* display: grid;
                grid-template-columns: repeat(2, 1fr); */
                gap: 1.5rem;
                padding: 1rem;
            }

            .card {
                position: relative;
                padding: 1.5rem;
                border-radius: 12px;
                color: white;
                height: 600px;
                overflow: hidden;
                background-size: cover;
                background-position: center;
                min-height: 300px;
            }

            .page {
                width: fit-content;
            }

            .title-topic {
                font-size: 23px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .wrapping {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .glass-bg {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            }

            .horizontal-accordion {
                display: flex;
                width: 100%;
                max-width: 1400px;
                /* height: min(600px, calc(100vh - 200px)); */
                min-height: 600px;
                gap: 1.5rem;
                margin: 0 auto;
            }

            .card-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg,
                        rgba(2, 0, 0, 0.555) 0%,
                        rgba(255, 7, 7, 0.685) 100%);
                z-index: 2;
            }

            .card-header {
                background: transparent;
                padding: 1.5rem;
                position: relative;
                z-index: 2;
            }

            .card-body {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.4s ease;
                position: relative;
                z-index: 3;
            }

            .card:hover {
                flex: 3;
                transform: scale(1) translateY(0px);
                /* box-shadow: 0 10px 10px rgba(0, 0, 0, 0.2); */
            }

            .card:hover .card-body {
                opacity: 1;
                transform: translateY(0);
            }

            .btn-custom {
                margin-top: 20px;
                font-size: 12px;
                border-radius: 50px;
                font-weight: 600;
                text-transform: uppercase;
                font-weight: bold;
                letter-spacing: 0.8px;
                transition: all 0.3s ease;
            }

            .btn-custom:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }

            .transition-all {
                transition: all 0.3s ease;
            }

            .card-initial-title {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: white;
                font-size: 2.5rem;
                font-weight: bold;
                text-align: center;
                z-index: 3;
                /* Higher than overlay */
                transition: opacity 0.4s ease;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
                width: 100%;
                padding: 0 1rem;
            }

            .card:hover .card-initial-title {
                opacity: 0;
            }

            .decoration-1 {
                top: -10%;
                left: -10%;
                width: 300px;
                opacity: 0.1;
                transform: rotate(45deg);
            }

            .decoration-2 {
                bottom: -5%;
                right: -5%;
                width: 250px;
                opacity: 0.1;
                transform: rotate(-135deg);
            }

            .decoration-3 {
                top: 40%;
                right: 10%;
                width: 200px;
                opacity: 0.1;
                transform: rotate(90deg);
            }

            }

            @media (max-width: 900px) {
                .custom-card-wrapper {
                    display: grid;
                    grid-template-columns: repeat(1, 1fr);
                    gap: 1.5rem;
                    padding: 1rem;
                }
            }

            @media (max-width: 768px) {
                .horizontal-accordion {
                    height: auto;
                    flex-direction: column;
                    max-height: calc(100vh - 200px);
                    overflow-y: auto;
                    padding: 1rem;
                }

                .card {
                    min-height: 300px;
                }

                .decoration-1,
                .decoration-2,
                .decoration-3 {
                    width: 150px;
                }

                .custom-card-wrapper {
                    display: grid;
                    grid-template-columns: repeat(1, 1fr);
                    gap: 1.5rem;
                    padding: 1rem;
                }

            }

            @media (min-width: 769px) {
                .horizontal-accordion {
                    overflow: hidden;
                }
            }
        </style>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.card');

            cards.forEach(card => {
                const logoImage = card.querySelector('.logo');

                card.addEventListener('mouseenter', function () {
                    logoImage.src = "{{ asset('images/IC_Smart Mobility_White.png') }}";
                    logoImage.style.width = '110px';
                    logoImage.classList.add('animate__animated', 'animate__pulse');
                });

                card.addEventListener('mouseleave', function () {
                    logoImage.src = "{{ asset('images/IC_Logo_Smart Mobility_White.png') }}";
                    logoImage.style.width = '50px';
                    logoImage.classList.remove('animate__animated', 'animate__pulse');
                });
            });
        });
    </script>
</div>