<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Farm Direct')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400;1,600&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ivory:    #FFFBF0;
            --champagne:#F7E7CE;
            --mauve:    #C4A484;
            --olive:    #808000;
            --umber:    #4B3621;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            background: var(--ivory);
            color: var(--umber);
            font-family: 'Jost', sans-serif;
            font-weight: 300;
            line-height: 1.7;
            overflow-x: hidden;
        }

        em { font-family: 'Cormorant Garamond', serif; font-style: italic; font-weight: 400; }

        /* NAV */
        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 48px;
            background: rgba(255,251,240,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(196,164,132,0.2);
        }
        .nav-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px; font-weight: 600;
            color: var(--umber); text-decoration: none;
            display: flex; align-items: center; gap: 10px;
        }
        .nav-logo-leaf {
            width: 28px; height: 28px;
            background: var(--olive);
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            opacity: 0.85;
        }
        .nav-right { display: flex; align-items: center; gap: 24px; }
        .nav-link {
            font-size: 13px; letter-spacing: 0.05em;
            color: var(--mauve); text-decoration: none;
            transition: color 0.2s;
        }
        .nav-link:hover { color: var(--umber); }
        .nav-login {
            font-size: 13px; letter-spacing: 0.08em;
            padding: 9px 22px;
            border: 1px solid var(--mauve);
            border-radius: 999px;
            color: var(--umber); text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }
        .nav-login:hover { background: var(--umber); color: var(--ivory); }

        /* BUTTONS */
        .btn-primary {
            display: inline-block;
            background: var(--umber);
            color: var(--ivory);
            font-family: 'Jost', sans-serif;
            font-size: 13px; font-weight: 400;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 14px 32px;
            border-radius: 999px;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-primary:hover { background: var(--olive); transform: translateY(-1px); }
        .btn-primary--dark { background: var(--ivory); color: var(--umber); }
        .btn-primary--dark:hover { background: var(--champagne); }
        .btn-ghost {
            display: inline-block;
            font-size: 13px; letter-spacing: 0.08em;
            color: var(--mauve); text-decoration: none;
            border-bottom: 1px solid rgba(196,164,132,0.4);
            padding-bottom: 2px;
            transition: color 0.2s, border-color 0.2s;
        }
        .btn-ghost:hover { color: var(--umber); border-color: var(--umber); }

        /* SECTION LABELS */
        .section-label {
            font-size: 11px; font-weight: 500;
            letter-spacing: 0.18em; text-transform: uppercase;
            color: var(--olive); margin-bottom: 12px;
        }
        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 42px; font-weight: 600;
            color: var(--umber); line-height: 1.15;
            margin-bottom: 20px;
        }

        /* HERO */
        .hero {
            min-height: 100vh;
            display: flex; align-items: center;
            padding: 120px 48px 80px;
            position: relative; overflow: hidden;
            background: var(--ivory);
        }
        .hero-inner { position: relative; z-index: 2; max-width: 600px; }
        .hero-tag {
            display: inline-block;
            font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase;
            color: var(--olive);
            border: 1px solid rgba(128,128,0,0.3);
            padding: 6px 16px; border-radius: 999px;
            margin-bottom: 28px;
        }
        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 72px; font-weight: 600;
            color: var(--umber); line-height: 1.05;
            margin-bottom: 24px;
        }
        .hero-sub {
            font-size: 16px; font-weight: 300;
            color: var(--mauve); line-height: 1.7;
            max-width: 440px; margin-bottom: 40px;
        }
        .hero-actions { display: flex; gap: 20px; align-items: center; flex-wrap: wrap; }
        .hero-orb {
            position: absolute; border-radius: 50%;
            pointer-events: none;
        }
        .hero-orb-1 {
            width: 500px; height: 500px;
            right: -100px; top: 50%; transform: translateY(-50%);
            background: radial-gradient(circle, rgba(247,231,206,0.8) 0%, rgba(255,251,240,0) 70%);
        }
        .hero-orb-2 {
            width: 300px; height: 300px;
            right: 200px; top: 10%;
            background: radial-gradient(circle, rgba(196,164,132,0.15) 0%, transparent 70%);
        }

        /* MARQUEE */
        .marquee-wrap {
            background: var(--umber);
            padding: 14px 0; overflow: hidden;
        }
        .marquee-track {
            display: flex; align-items: center; gap: 0;
            white-space: nowrap;
            animation: marquee 30s linear infinite;
        }
        .marquee-item {
            font-size: 12px; letter-spacing: 0.12em;
            text-transform: uppercase; color: var(--champagne);
            padding: 0 24px;
        }
        .marquee-dot { color: var(--olive); font-size: 10px; }
        @keyframes marquee {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }

        /* ABOUT */
        .about {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 80px; align-items: center;
            padding: 100px 48px;
            background: var(--champagne);
        }
        .about-image-wrap { position: relative; }
        .about-image-placeholder {
            background: rgba(196,164,132,0.3);
            border-radius: 4px 40px 4px 40px;
            height: 420px; overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid rgba(196,164,132,0.3);
        }
        .about-image-inner { text-align: center; }
        .placeholder-text {
            font-size: 12px; color: var(--mauve);
            margin-top: 12px; letter-spacing: 0.05em;
        }
        .about-badge {
            position: absolute; bottom: -20px; right: -20px;
            background: var(--olive);
            color: var(--ivory);
            border-radius: 50%;
            width: 100px; height: 100px;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 2px;
        }
        .about-badge-num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 26px; font-weight: 600; line-height: 1;
        }
        .about-badge-label { font-size: 10px; letter-spacing: 0.05em; opacity: 0.85; text-align: center; }
        .about-body { font-size: 15px; color: var(--umber); line-height: 1.8; }
        .about-stats {
            display: flex; gap: 32px; margin-top: 40px;
            padding-top: 32px;
            border-top: 1px solid rgba(196,164,132,0.5);
        }
        .stat { display: flex; flex-direction: column; gap: 4px; }
        .stat-num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 32px; font-weight: 600; color: var(--umber);
        }
        .stat-label { font-size: 12px; letter-spacing: 0.08em; color: var(--mauve); }

        /* PRODUCE */
        .produce { padding: 100px 48px; background: var(--ivory); }
        .produce-header { margin-bottom: 48px; }
        .produce-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            grid-template-rows: auto auto;
            gap: 16px;
        }
        .produce-card {
            background: var(--champagne);
            border-radius: 24px;
            padding: 36px 32px;
            border: 1px solid rgba(196,164,132,0.3);
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .produce-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(75,54,33,0.08); }
        .produce-card--large { grid-row: span 2; display: flex; flex-direction: column; justify-content: flex-end; min-height: 280px; background: var(--umber); }
        .produce-card--large h3, .produce-card--large p { color: var(--champagne); }
        .produce-card--tall { background: rgba(128,128,0,0.08); border-color: rgba(128,128,0,0.2); }
        .produce-icon { font-size: 32px; margin-bottom: 16px; }
        .produce-card h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px; font-weight: 600;
            color: var(--umber); margin-bottom: 8px;
        }
        .produce-card p { font-size: 14px; color: var(--mauve); line-height: 1.6; }
        .produce-card--large p { color: rgba(247,231,206,0.7); }

        /* HOW */
        .how { padding: 100px 48px; background: var(--champagne); }
        .how .section-title { text-align: center; margin-bottom: 64px; }
        .steps {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 0; position: relative; max-width: 800px; margin: 0 auto;
        }
        .step-line {
            position: absolute; top: 28px; left: 15%; right: 15%;
            height: 1px; background: rgba(196,164,132,0.5);
            z-index: 0;
        }
        .step { text-align: center; padding: 0 24px; position: relative; z-index: 1; }
        .step-num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 13px; font-weight: 600;
            letter-spacing: 0.1em;
            color: var(--ivory);
            background: var(--umber);
            width: 48px; height: 48px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
        }
        .step h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 20px; font-weight: 600;
            color: var(--umber); margin-bottom: 8px;
        }
        .step p { font-size: 14px; color: var(--mauve); line-height: 1.6; }

        /* CTA */
        .cta {
            padding: 100px 48px;
            background: var(--umber);
            text-align: center;
            position: relative; overflow: hidden;
        }
        .cta-inner { position: relative; z-index: 2; }
        .cta h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 52px; font-weight: 600;
            color: var(--champagne); margin-bottom: 16px;
        }
        .cta p { font-size: 16px; color: var(--mauve); margin-bottom: 36px; }
        .cta-orb {
            position: absolute; width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(128,128,0,0.15) 0%, transparent 70%);
            top: 50%; left: 50%; transform: translate(-50%, -50%);
        }

        /* FOOTER */
        .footer {
            padding: 32px 48px;
            background: var(--umber);
            border-top: 1px solid rgba(196,164,132,0.15);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 16px;
        }
        .footer-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 18px; color: var(--champagne);
        }
        .footer-links { display: flex; gap: 24px; }
        .footer-links a {
            font-size: 12px; letter-spacing: 0.08em;
            color: var(--mauve); text-decoration: none;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: var(--champagne); }
        .footer-copy { font-size: 12px; color: var(--mauve); }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .nav { padding: 16px 24px; }
            .hero { padding: 100px 24px 60px; }
            .hero-title { font-size: 48px; }
            .about { grid-template-columns: 1fr; padding: 60px 24px; gap: 48px; }
            .produce { padding: 60px 24px; }
            .produce-grid { grid-template-columns: 1fr 1fr; }
            .produce-card--large { grid-column: span 2; grid-row: span 1; }
            .how { padding: 60px 24px; }
            .steps { grid-template-columns: 1fr; gap: 40px; }
            .step-line { display: none; }
            .cta { padding: 60px 24px; }
            .cta h2 { font-size: 36px; }
            .footer { padding: 24px; flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('landing') }}" class="nav-logo">
        <div class="nav-logo-leaf"></div>
        Farm Direct
    </a>
    <div class="nav-right">
        <a href="#about" class="nav-link">About</a>
        <a href="#produce" class="nav-link">Produce</a>
        <a href="{{ route('login') }}" class="nav-login">Login</a>
    </div>
</nav>

@yield('content')

<footer class="footer">
    <div class="footer-logo">Farm Direct</div>
    <div class="footer-links">
        <a href="#about">About</a>
        <a href="#produce">Produce</a>
        <a href="{{ route('login') }}">Login</a>
    </div>
    <p class="footer-copy">© {{ date('Y') }} Farm Direct, Kerala</p>
</footer>

</body>
</html>