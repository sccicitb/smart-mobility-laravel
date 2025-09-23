<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name=o"viewport" content="width=udevice-width, initial-scale=1.0">
    <title>Landing Page</title>

    <!-- Bootstrapt CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style-landing-page.css') }}">
    <!-- Add AOS CSS in head -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top"
        style="background: transparent; backdrop-filter: blur(10px); height: 80px;">
        <div class="container-fluid p-5">
            <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="#home">
                <img src="{{ asset('images/IC_SMART MOBILITY.png') }}" alt="Smart Mobility" height="40" class="me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#homes">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#abouts">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#contacts">Contact</a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                                data-bs-toggle="dropdown">
                                <span class="badge bg-danger">
                                    <i class="fas fa-user me-1"></i>
                                    Hi, {{ Auth::user()->name }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="fas fa-home me-2"></i>Home
                                    </a></li>
                                <li></li>
                                <li><a class="dropdown-item" href="{{ route('settings') }}">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <button class="btn btn-danger px-4 py-2">Login</button>
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- First Hero Section -->
    <div class="hero d-flex align-items-center" style="min-height: 100vh; background-attachment: scroll;" id="homes">
        <div class="container p-5">
            <div class="row align-items-center g-5">
                <!-- Welcome Text Column -->
                <div class="col-lg-6 welcome-text" data-aos="fade-right" data-aos-delay="200">
                    <h1 class="display-3 fw-bold text-white" style="font-size: 4rem;">
                        SMART <span class="text-danger">MOBILITY</span>
                    </h1>
                    <p class="lead text-white-50 mb-4">
                        Revolutionizing urban transportation through intelligent monitoring and simulation.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-danger btn-lg">
                        GET STARTED
                    </a>
                </div>

                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="400">
                    <div class="info-card glass-bg" style="border-radius: 80px 0px 80px 0px;">
                        <div class="container p-4">
                            <div class="row g-4">
                                <div class="overview-section p-4" data-aos="fade-up">
                                    <div class="d-flex align-items-center justify-content-start gap-3 mb-4">
                                        <img src="{{ asset('images/IC2_SMART MOBILITY.png') }}" alt="Icon" height="32"
                                            class="me-3">
                                        <h4 class="text-white fw-bold m-0">Smart Mobility Simulator</h4>
                                    </div>

                                    <div class="overview-content ms-5">
                                        <p class="lead text-white-50 mb-4">
                                            A cutting-edge system designed to revolutionize urban transportation
                                            through:
                                        </p>
                                        <ul class="overview-list list-unstyled">
                                            <li class="mb-3 d-flex align-items-center text-white-50">
                                                <i class="fas fa-chart-line text-danger me-3"></i>
                                                <span>Real-time traffic scenario simulation</span>
                                            </li>
                                            <li class="mb-3 d-flex align-items-center text-white-50">
                                                <i class="fas fa-robot text-danger me-3"></i>
                                                <span>Innovative mobility solutions testing</span>
                                            </li>
                                            <li class="mb-3 d-flex align-items-center text-white-50">
                                                <i class="fas fa-shield-alt text-danger me-3"></i>
                                                <span>Enhanced transportation efficiency & safety</span>
                                            </li>
                                            <li class="d-flex align-items-center text-white-50">
                                                <i class="fas fa-leaf text-danger me-3"></i>
                                                <span>Sustainable urban mobility development</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                {{-- <!-- Card 1 -->
                                <div class="col-md-6">
                                    <div class="glass-item p-4">
                                        <i class="fas fa-chart-line text-danger mb-3 fa-2x"></i>
                                        <h5 class="text-white mb-2">Real-time Analytics</h5>
                                        <p class="text-white-50">Lorem ipsum dolor sit amet, consectetur adipiscing
                                            elit. Sed do eiusmod tempor incididunt ut labore.</p>
                                    </div>
                                </div>

                                <!-- Card 2 -->
                                <div class="col-md-6">
                                    <div class="glass-item p-4">
                                        <i class="fas fa-robot text-danger mb-3 fa-2x"></i>
                                        <h5 class="text-white mb-2">AI-Powered</h5>
                                        <p class="text-white-50">Ut enim ad minim veniam, quis nostrud exercitation
                                            ullamco laboris nisi ut aliquip.</p>
                                    </div>
                                </div>

                                <!-- Card 3 -->
                                <div class="col-md-6">
                                    <div class="glass-item p-4">
                                        <i class="fas fa-database text-danger mb-3 fa-2x"></i>
                                        <h5 class="text-white mb-2">Big Data</h5>
                                        <p class="text-white-50">Duis aute irure dolor in reprehenderit in voluptate
                                            velit esse cillum dolore.</p>
                                    </div>
                                </div>

                                <!-- Card 4 -->
                                <div class="col-md-6">
                                    <div class="glass-item p-4">
                                        <i class="fas fa-cloud text-danger mb-3 fa-2x"></i>
                                        <h5 class="text-white mb-2">Cloud Platform</h5>
                                        <p class="text-white-50">Excepteur sint occaecat cupidatat non proident, sunt
                                            in culpa qui officia.</p>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>

    <div class="hero analysis-tools d-flex align-items-center" id="features">
        <div class="container-fluid p-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h1 class="display-4 fw-bold text-white">Smart Mobility Features</h1>
                <p class="lead text-white-50">Advanced Traffic Analysis & Management System</p>
            </div>

            <div class="row align-items-center justify-content-center">
                <div class="col-lg-5 align-items-center justify-content-center">
                    <div class="h-80 align-items-center justify-content-center">
                        <div class="container">
                            {{-- <h2 class="text-white text-center mb-4">Analysis Tools</h2> --}}
                            <div class="service-item mb-4 d-flex align-items-center justify-content-center py-3"
                                style="border-radius:300px 500px 0px 300px">
                                <h3 class="text-white m-0">Traffic Flow Analysis</h3>
                            </div>
                            <div class="service-item mb-4 d-flex align-items-center justify-content-center py-3"
                                style="border-radius:200px 0px 200px 0px">
                                <h3 class="text-white m-0">Congestion Analysis</h3>
                            </div>
                            <div class="service-item mb-4 d-flex align-items-center justify-content-center py-3"
                                style="border-radius:0px 200px 0px 200px">
                                <h3 class="text-white m-0">Intersection Analysis</h3>
                            </div>
                            <div class="service-item mb-4 d-flex align-items-center justify-content-center py-3"
                                style="border-radius:200px 0px 200px 0px">
                                <h3 class="text-white m-0">Travel Time Analysis</h3>
                            </div>
                            <div class="service-item mb-4 d-flex align-items-center justify-content-center py-3"
                                style="border-radius:0px 300px 300px 500px">
                                {{-- <h5 class="text-white m-0">Travel Time Analysis</h5> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7" data-aos="fade-left" data-aos-delay="300">
                    <div class="info-card">
                        <div class="container p-4">
                            <h2 class="text-white text-center mb-4">Benefit</h2>
                            <div class="description-content">
                                <ul class="benefit-list list-unstyled">
                                    <li class="mb-3 text-white-50">
                                        <h5>Risk-Free Testing</h5>
                                        Simulators allow for testing mobility solutions and traffic scenarios without
                                        risking public safety or damaging infrastructure
                                    </li>
                                    <li class="mb-3 text-white-50">
                                        <h5>Transportation System Optimization</h5>
                                        Simulators can help identify ways to reduce congestion, improve route
                                        efficiency, and optimize public transportation usage, all of which support
                                        smoother urban traffic flow
                                    </li>
                                    <li class="mb-3 text-white-50">
                                        <h5>Improved Infrastructure Planning</h5>
                                        By simulating urban scenarios, city planners can more effectively design and
                                        allocate transportation infrastructure such as bus lanes, bike lanes, or
                                        electric vehicle charging stations
                                    </li>
                                    <li class="mb-3 text-white-50">
                                        <h5>Time and Cost Savings</h5>
                                        Digital simulations allow for the testing of mobility solutions without the time
                                        and expense required for direct implementation, accelerating innovation and
                                        decision-making
                                    </li>
                                    <li class="mb-3 text-white-50">
                                        <h5>Support for Transportation Policy</h5>
                                        Simulators help policymakers assess the potential impact of new transportation
                                        rules or policies before they are implemented, enabling more accurate and
                                        data-driven decisions
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Third Hero Section - About Smart Mobility -->
    <div class="hero d-flex align-items-center" id="abouts">
        <div class="container py-5">
            <div class="glass-bg p-5" style="border-radius: 200px 0px 200px 0px;">
                <h2 class="display-4 fw-bold text-white text-center mb-5">About Us</h2>

                <div class="row justify-content-center g-4">
                    <div class="col-lg-10">
                        <p class="lead text-white-50 text-center mb-5">Part of the innovative SmartX ecosystem, Smart
                            Mobility is a comprehensive traffic monitoring and simulation platform designed to
                            revolutionize urban transportation management.</p>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="glass-item p-4">
                                    <h5 class="text-white mb-3">
                                        <i class="fas fa-traffic-light text-danger me-2"></i>
                                        Real-time Monitoring
                                    </h5>
                                    <p class="text-white-50 mb-0">Advanced surveillance system providing 24/7 traffic
                                        flow monitoring and analysis across multiple zones.</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="glass-item p-4">
                                    <h5 class="text-white mb-3">
                                        <i class="fas fa-chart-line text-danger me-2"></i>
                                        Smart Analytics
                                    </h5>
                                    <p class="text-white-50 mb-0">AI-powered traffic pattern analysis and
                                        infrastructure optimization recommendations.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fourth Hero Section - Contact Us -->
    <div class="hero" style="min-height: 100vh; background-attachment: scroll;" id="contacts">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-4 fw-bold text-white mb-3">Get In Touch</h2>
                <p class="lead text-white-50">We'd love to hear from you. Please reach out to us.</p>
            </div>

            <div class="row g-4 mb-5">
                <!-- Contact Cards -->
                <div class="col-md-4">
                    <div class="glass-bg p-4 text-center h-100" style="border-radius: 20px;">
                        <i class="fas fa-headset fa-3x text-danger mb-4"></i>
                        <h4 class="text-white mb-3">Help Center</h4>
                        <p class="text-white-50 mb-0">Find answers to frequently asked questions</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="glass-bg p-4 text-center h-100" style="border-radius: 20px;">
                        <i class="fas fa-phone-alt fa-3x text-danger mb-4"></i>
                        <h4 class="text-white mb-3">Call Us</h4>
                        <p class="text-white-50 mb-0">+62 812-3456-7890</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="glass-bg p-4 text-center h-100" style="border-radius: 20px;">
                        <i class="fas fa-envelope fa-3x text-danger mb-4"></i>
                        <h4 class="text-white mb-3">Email Us</h4>
                        <p class="text-white-50 mb-0">support@smartmobility.com</p>
                    </div>
                </div>
            </div>

            <!-- Logo Paths Row -->
            <div class="row justify-content-center mt-5">
                <div class="col-12">
                    <div class="glass-bg p-4 d-flex align-items-center justify-content-center gap-4"
                        style="border-radius: 50px;">
                        <div class="logo-item">
                            <img src="{{ asset('images/path_5.svg') }}" alt="Logo" height="48" class="logo-img">
                        </div>
                        <div class="logo-divider"> </div>
                        <div class="logo-item">
                            <img src="{{ asset('images/path_4.svg') }}" alt="Logo" height="48" class="logo-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(0, 0, 0, 0.9)';
            } else {
                navbar.style.background = 'transparent';
            }
        });

        // Parallax effect for hero sections
        document.addEventListener('scroll', function () {
            const parallaxElements = document.querySelectorAll('.parallax');
            parallaxElements.forEach(element => {
                let speed = element.getAttribute('data-speed') || 0.5;
                element.style.transform = `translateY(${window.scrollY * speed}px)`;
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const sections = document.querySelectorAll('.hero');
            const navLinks = document.querySelectorAll('.nav-link');

            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    if (pageYOffset >= sectionTop - 60) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === '#' + current) {
                        link.classList.add('active');
                    }
                });
            });
        });
    </script>

    <style>
        /* Enhanced Effects */
        .glass-item {
            transform: perspective(1000px) translateZ(0);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .glass-item:hover {
            transform: perspective(1000px) translateZ(20px) translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
        }

        .service-item {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .service-item:hover {
            transform: translateX(15px) scale(1.02);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: -5px 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Fix Benefit Content Overflow */
        .benefit-list {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 15px;
            scrollbar-width: thin;
            scrollbar-color: var(--theme-red) rgba(255, 255, 255, 0.1);
        }

        .benefit-list::-webkit-scrollbar {
            width: 6px;
        }

        .benefit-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .benefit-list::-webkit-scrollbar-thumb {
            background: var(--theme-red);
            border-radius: 3px;
        }

        /* Improved Login Badge */
        .nav-link .badge {
            padding: 8px 16px;
            font-size: 0.95rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .nav-link .badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        /* Additional Smooth Transitions */
        .nav-link,
        .btn,
        .glass-bg {
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .benefit-list {
                max-height: 50vh;
            }

            .nav-link .badge {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 768px) {
            .glass-item:hover {
                transform: translateY(-3px);
            }

            .service-item:hover {
                transform: translateX(10px);
            }
        }
    </style>
</body>

</html>