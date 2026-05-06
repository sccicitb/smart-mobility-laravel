<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Mobility - Login</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #0f0f0f;
        }

        /* ── Layout ── */
        .login-wrapper {
            min-height: 100vh;
            display: flex;
        }

        /* ── Left branding panel ── */
        .login-branding {
            flex: 1;
            background: linear-gradient(160deg, #7c2d2d 0%, #4a1010 55%, #1a0000 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 3.5rem 4.5rem;
            position: relative;
            overflow: hidden;
        }

        .login-branding::before {
            content: '';
            position: absolute;
            width: 520px; height: 520px;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            top: -120px; right: -120px;
            border-radius: 50%;
        }

        .login-branding::after {
            content: '';
            position: absolute;
            width: 320px; height: 320px;
            background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
            bottom: -60px; left: -60px;
            border-radius: 50%;
        }

        .brand-logo { height: 48px; width: auto; margin-bottom: 3rem; }

        .brand-title {
            font-size: 2.8rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 1rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.75;
            max-width: 380px;
            margin-bottom: 3rem;
        }

        .brand-features { display: flex; flex-direction: column; gap: 0.85rem; }

        .brand-feature-item {
            display: flex; align-items: center; gap: 0.75rem;
            color: rgba(255,255,255,0.75);
            font-size: 0.9rem;
        }

        .brand-feature-dot {
            width: 7px; height: 7px;
            background: #e74c3c;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ── Right form panel ── */
        .login-form-panel {
            width: 460px;
            flex-shrink: 0;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3.5rem 3rem;
        }

        .form-heading {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.4rem;
        }

        .form-subheading {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 2.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 0.45rem;
        }

        .form-input {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            color: #1f2937;
            background: #f9fafb;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-input:focus {
            border-color: #7c2d2d;
            box-shadow: 0 0 0 3px rgba(124,45,45,0.1);
            background: #fff;
        }

        .form-group { margin-bottom: 1.25rem; }

        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #7c2d2d, #5c1a1a);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            letter-spacing: 0.3px;
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #5c1a1a, #3d0000);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(124,45,45,0.35);
        }

        .btn-sso {
            width: 100%;
            padding: 0.85rem;
            background: transparent;
            color: #7c2d2d;
            border: 1.5px solid #7c2d2d;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 0.75rem;
        }

        .btn-sso:hover {
            background: #7c2d2d;
            color: #fff;
        }

        .divider {
            display: flex; align-items: center; gap: 1rem;
            margin: 1.25rem 0;
            color: #9ca3af; font-size: 0.78rem;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: #e5e7eb;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 860px) {
            .login-branding { display: none; }
            .login-form-panel { width: 100%; padding: 2.5rem 1.5rem; }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <!-- Left branding panel -->
        <div class="login-branding">
            <img src="{{ asset('images/IC_SMART MOBILITY.png') }}" alt="Smart Mobility" class="brand-logo">
            <div class="brand-title">Smart<br>Mobility</div>
            <p class="brand-subtitle">Sistem analisis lalu lintas berbasis data real-time untuk perencanaan infrastruktur jalan yang lebih cerdas.</p>
            <div class="brand-features">
                <div class="brand-feature-item"><div class="brand-feature-dot"></div> Monitoring arus kendaraan real-time</div>
                <div class="brand-feature-item"><div class="brand-feature-dot"></div> Simulasi persimpangan & kapasitas jalan</div>
                <div class="brand-feature-item"><div class="brand-feature-dot"></div> Analisis emisi CO₂ & kerugian ekonomi</div>
                <div class="brand-feature-item"><div class="brand-feature-dot"></div> Visualisasi peta interaktif</div>
            </div>
        </div>

        <!-- Right form panel -->
        <div class="login-form-panel">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>

</html>
