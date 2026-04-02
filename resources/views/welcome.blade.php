<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Matatu System — Nairobi Fleet Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --teal:       #0f766e;
            --teal-deep:  #134e4a;
            --teal-light: #ccfbf1;
            --gold:       #ffd700;
            --gold-soft:  rgba(255,215,0,0.14);
            --text:       #17313a;
            --muted:      #5f7580;
            --border:     #d8e4df;
            --bg:         #f4faf9;
            --surface:    #ffffff;
            --shadow:     0 24px 64px rgba(15,118,110,0.13);
        }

        html, body {
            min-height: 100%;
            font-family: 'Manrope', sans-serif;
            color: var(--text);
            background: var(--bg);
            overflow-x: hidden;
        }

        /* ── Background ─────────────────────────────────── */
        .bg-wrap {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
        }

        .bg-orb-1 {
            width: 700px; height: 700px;
            top: -200px; right: -200px;
            background: radial-gradient(circle, rgba(15,118,110,0.18) 0%, transparent 70%);
        }

        .bg-orb-2 {
            width: 500px; height: 500px;
            bottom: -100px; left: -100px;
            background: radial-gradient(circle, rgba(255,215,0,0.12) 0%, transparent 70%);
        }

        .bg-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(15,118,110,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15,118,110,0.04) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* ── Nav ────────────────────────────────────────── */
        nav {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 48px;
            border-bottom: 1px solid var(--border);
            background: rgba(255,255,255,0.82);
            backdrop-filter: blur(12px);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text);
        }

        .nav-brand-mark {
            width: 42px; height: 42px;
            border-radius: 14px;
            background: var(--teal);
            display: flex; align-items: center; justify-content: center;
            color: var(--gold);
            font-size: 1.2rem;
            font-weight: 900;
            box-shadow: 0 6px 18px rgba(15,118,110,0.28);
        }

        .traffic-lights {
            display: flex; gap: 5px; margin-top: 2px;
        }

        .tl { width: 8px; height: 8px; border-radius: 50%; }
        .tl-r { background: #ff5f57; }
        .tl-y { background: var(--gold); animation: blink-y 2.4s ease-in-out infinite; }
        .tl-g { background: #28c840; animation: blink-g 2.4s ease-in-out 0.8s infinite; }

        @keyframes blink-y { 0%,100%{opacity:1} 50%{opacity:.4} }
        @keyframes blink-g { 0%,100%{opacity:1} 50%{opacity:.4} }

        .nav-brand-text h1 { font-size: 1.05rem; font-weight: 800; letter-spacing: -.3px; }
        .nav-brand-text p  { font-size: 0.72rem; color: var(--muted); }

        .nav-actions { display: flex; align-items: center; gap: 10px; }

        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 20px;
            border-radius: 12px;
            font-family: 'Manrope', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 180ms ease;
        }

        .btn-outline {
            background: transparent;
            color: var(--teal);
            border: 1.5px solid var(--border);
        }
        .btn-outline:hover { background: var(--teal-light); border-color: var(--teal); }

        .btn-primary {
            background: var(--teal);
            color: #fff;
            box-shadow: 0 6px 16px rgba(15,118,110,0.28);
        }
        .btn-primary:hover { background: var(--teal-deep); transform: translateY(-1px); box-shadow: 0 10px 24px rgba(15,118,110,0.32); }

        /* ── Hero ───────────────────────────────────────── */
        .hero {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 60px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 96px 48px 80px;
        }

        .hero-kicker {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 0.78rem; font-weight: 700; letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--teal);
            background: var(--teal-light);
            border: 1px solid rgba(15,118,110,0.18);
            border-radius: 30px;
            padding: 5px 14px;
            margin-bottom: 22px;
        }

        .hero-kicker span { width: 6px; height: 6px; border-radius: 50%; background: var(--teal); animation: blink-g 1.8s ease-in-out infinite; }

        .hero-title {
            font-size: clamp(2.4rem, 4vw, 3.4rem);
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -.04em;
            color: var(--teal-deep);
            margin-bottom: 20px;
        }

        .hero-title em {
            font-style: normal;
            color: var(--teal);
            position: relative;
        }

        .hero-title em::after {
            content: '';
            position: absolute;
            bottom: 2px; left: 0; right: 0;
            height: 4px;
            background: var(--gold);
            border-radius: 2px;
            opacity: .8;
        }

        .hero-sub {
            font-size: 1.05rem;
            color: var(--muted);
            line-height: 1.65;
            margin-bottom: 36px;
            max-width: 480px;
        }

        .hero-cta {
            display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
        }

        .btn-hero {
            padding: 13px 28px;
            font-size: 0.95rem;
            border-radius: 14px;
        }

        .hero-note {
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 18px;
        }

        .hero-note a { color: var(--teal); text-decoration: none; font-weight: 600; }
        .hero-note a:hover { text-decoration: underline; }

        /* ── Dashboard preview card ─────────────────────── */
        .hero-preview {
            position: relative;
        }

        .preview-card {
            background: var(--surface);
            border-radius: 24px;
            box-shadow: var(--shadow), 0 0 0 1px rgba(15,118,110,0.08);
            overflow: hidden;
            transform: perspective(1000px) rotateY(-6deg) rotateX(3deg);
            transition: transform 500ms ease;
        }

        .preview-card:hover { transform: perspective(1000px) rotateY(-2deg) rotateX(1deg); }

        .preview-topbar {
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-deep) 100%);
            padding: 14px 18px;
            display: flex; align-items: center; gap: 10px;
        }

        .preview-topbar-lights { display: flex; gap: 5px; }
        .preview-topbar-lights span { width: 9px; height: 9px; border-radius: 50%; }

        .preview-topbar-title { color: rgba(255,255,255,0.9); font-size: 0.82rem; font-weight: 700; margin-left: 4px; }

        .preview-body { padding: 18px; }

        .preview-metrics {
            display: grid; grid-template-columns: repeat(4,1fr); gap: 8px; margin-bottom: 14px;
        }

        .preview-metric {
            background: var(--bg);
            border-radius: 12px;
            padding: 10px 12px;
            border: 1px solid var(--border);
        }

        .pm-label { font-size: 0.65rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
        .pm-val   { font-size: 1.3rem; font-weight: 800; color: var(--teal); line-height: 1.1; margin-top: 2px; }

        .preview-map {
            background: #eef5f3;
            border-radius: 12px;
            height: 140px;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .preview-map svg { width: 100%; height: 100%; }

        /* Animated route lines in preview */
        .prev-line { stroke-dasharray: 200; animation: dash 3s linear infinite; }
        @keyframes dash { to { stroke-dashoffset: -400; } }

        .prev-dot { animation: moveDot 3s linear infinite; }

        .preview-table {
            margin-top: 12px;
        }

        .preview-row {
            display: flex; align-items: center; gap: 10px;
            padding: 7px 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.75rem;
        }

        .preview-row:last-child { border-bottom: none; }

        .preview-chip {
            display: inline-flex; align-items: center;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
        }

        .chip-green  { background: #dcfce7; color: #166534; }
        .chip-yellow { background: #fef9c3; color: #854d0e; }
        .chip-red    { background: #fee2e2; color: #991b1b; }

        /* Floating badge on preview */
        .preview-badge {
            position: absolute;
            top: -16px; right: -16px;
            background: var(--gold);
            color: var(--teal-deep);
            font-size: 0.72rem;
            font-weight: 800;
            padding: 8px 14px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(255,215,0,0.4);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-6px)} }

        /* ── Feature pills ──────────────────────────────── */
        .features {
            position: relative;
            z-index: 1;
            background: var(--surface);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .features-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 48px;
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 32px;
        }

        .feature {
            display: flex; flex-direction: column; gap: 12px;
        }

        .feature-icon {
            width: 48px; height: 48px;
            border-radius: 16px;
            background: var(--teal-light);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.35rem;
            color: var(--teal);
        }

        .feature h3 { font-size: 1rem; font-weight: 800; color: var(--teal-deep); }
        .feature p  { font-size: 0.86rem; color: var(--muted); line-height: 1.6; }

        /* ── Footer ─────────────────────────────────────── */
        footer {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 32px 48px;
            font-size: 0.8rem;
            color: var(--muted);
        }

        footer a { color: var(--teal); text-decoration: none; font-weight: 600; }
        footer a:hover { text-decoration: underline; }

        /* ── Responsive ─────────────────────────────────── */
        @media (max-width: 900px) {
            nav { padding: 16px 24px; }
            .hero { grid-template-columns: 1fr; padding: 60px 24px 48px; gap: 40px; }
            .preview-card { transform: none; }
            .features-inner { grid-template-columns: 1fr; padding: 40px 24px; }
        }
    </style>
</head>
<body>

<!-- Background -->
<div class="bg-wrap">
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-grid"></div>
</div>

<!-- Nav -->
<nav>
    <a class="nav-brand" href="/">
        <div class="nav-brand-mark">e</div>
        <div class="nav-brand-text">
            <h1>e-Matatu System</h1>
            <div class="traffic-lights">
                <span class="tl tl-r"></span>
                <span class="tl tl-y"></span>
                <span class="tl tl-g"></span>
            </div>
        </div>
    </a>

    <div class="nav-actions">
        @auth
            <a class="btn btn-primary btn-hero" href="{{ route('dashboard') }}">
                <i class='bx bxs-dashboard'></i> Go to Dashboard
            </a>
        @else
            <a class="btn btn-outline" href="{{ route('login') }}">
                <i class='bx bx-log-in'></i> Sign In
            </a>
            @if (Route::has('register'))
                <a class="btn btn-primary" href="{{ route('register') }}">
                    <i class='bx bx-user-plus'></i> Create Account
                </a>
            @endif
        @endauth
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-kicker">
            <span></span>
            Digital Matatus · Nairobi Fleet Platform
        </div>

        <h1 class="hero-title">
            Manage every<br>
            <em>matatu route</em><br>
            in one dashboard
        </h1>

        <p class="hero-sub">
            Real-time fleet management for Nairobi's matatu network —
            assign drivers, track inspections, manage insurance, and monitor
            your entire fleet from a single live dashboard.
        </p>

        <div class="hero-cta">
            @auth
                <a class="btn btn-primary btn-hero" href="{{ route('dashboard') }}">
                    <i class='bx bxs-dashboard'></i> Open Dashboard
                </a>
                <a class="btn btn-outline btn-hero" href="{{ route('routes') }}">
                    <i class='bx bx-map'></i> View Route Map
                </a>
            @else
                <a class="btn btn-primary btn-hero" href="{{ route('register') }}">
                    <i class='bx bx-user-plus'></i> Get Started Free
                </a>
                <a class="btn btn-outline btn-hero" href="{{ route('login') }}">
                    <i class='bx bx-log-in'></i> Sign In
                </a>
            @endauth
        </div>

        @guest
            <p class="hero-note">
                Already have an account? <a href="{{ route('login') }}">Sign in here</a>.
            </p>
        @endguest
    </div>

    <!-- Dashboard preview -->
    <div class="hero-preview">
        <div class="preview-badge">
            <i class='bx bxs-map-pin'></i> 22 Live Routes
        </div>

        <div class="preview-card">
            <div class="preview-topbar">
                <div class="preview-topbar-lights">
                    <span style="background:#ff5f57;"></span>
                    <span style="background:#febc2e;"></span>
                    <span style="background:#28c840;"></span>
                </div>
                <span class="preview-topbar-title">e-Matatu · Dashboard</span>
            </div>

            <div class="preview-body">
                <!-- Metric cards -->
                <div class="preview-metrics">
                    <div class="preview-metric">
                        <div class="pm-label">Matatus</div>
                        <div class="pm-val" id="animVal1">0</div>
                    </div>
                    <div class="preview-metric">
                        <div class="pm-label">Drivers</div>
                        <div class="pm-val" id="animVal2">0</div>
                    </div>
                    <div class="preview-metric">
                        <div class="pm-label">Routes</div>
                        <div class="pm-val" id="animVal3">0</div>
                    </div>
                    <div class="preview-metric">
                        <div class="pm-label">Maintenance</div>
                        <div class="pm-val" id="animVal4">0</div>
                    </div>
                </div>

                <!-- Mini route map -->
                <div class="preview-map">
                    <svg viewBox="0 0 400 140" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background -->
                        <rect width="400" height="140" fill="#eef5f3"/>
                        <!-- Road lines -->
                        <line x1="200" y1="70" x2="40"  y2="65"  stroke="#c5d8d2" stroke-width="1.5"/>
                        <line x1="200" y1="70" x2="360" y2="62"  stroke="#c5d8d2" stroke-width="1.5"/>
                        <line x1="200" y1="70" x2="195" y2="10"  stroke="#c5d8d2" stroke-width="1.5"/>
                        <line x1="200" y1="70" x2="170" y2="130" stroke="#c5d8d2" stroke-width="1.5"/>
                        <line x1="200" y1="70" x2="340" y2="120" stroke="#c5d8d2" stroke-width="1.5"/>
                        <!-- Animated route lines -->
                        <path class="prev-line" d="M200,70 C165,68 120,66 55,64"  fill="none" stroke="#e74c3c" stroke-width="2.5" stroke-dashoffset="0"/>
                        <path class="prev-line" d="M200,70 C230,67 270,63 355,60" fill="none" stroke="#3498db" stroke-width="2.5" stroke-dashoffset="-80"/>
                        <path class="prev-line" d="M200,70 C198,52 196,33 195,13" fill="none" stroke="#27ae60" stroke-width="2.5" stroke-dashoffset="-160"/>
                        <path class="prev-line" d="M200,70 C192,85 182,100 170,128" fill="none" stroke="#8e44ad" stroke-width="2.5" stroke-dashoffset="-40"/>
                        <path class="prev-line" d="M200,70 C245,82 285,98 338,118" fill="none" stroke="#f39c12" stroke-width="2.5" stroke-dashoffset="-120"/>
                        <!-- CBD dot -->
                        <circle cx="200" cy="70" r="6" fill="#0f766e"/>
                        <circle cx="200" cy="70" r="3" fill="#ffd700"/>
                        <!-- Animated bus dots -->
                        <circle r="4" fill="#e74c3c" stroke="#fff" stroke-width="1.5">
                            <animateMotion dur="3s" repeatCount="indefinite" path="M200,70 C165,68 120,66 55,64"/>
                        </circle>
                        <circle r="4" fill="#3498db" stroke="#fff" stroke-width="1.5">
                            <animateMotion dur="2.4s" repeatCount="indefinite" path="M200,70 C230,67 270,63 355,60"/>
                        </circle>
                        <circle r="4" fill="#27ae60" stroke="#fff" stroke-width="1.5">
                            <animateMotion dur="2.8s" repeatCount="indefinite" path="M200,70 C198,52 196,33 195,13"/>
                        </circle>
                        <circle r="3" fill="#8e44ad" stroke="#fff" stroke-width="1.5">
                            <animateMotion dur="3.2s" repeatCount="indefinite" path="M200,70 C192,85 182,100 170,128"/>
                        </circle>
                        <circle r="3" fill="#f39c12" stroke="#fff" stroke-width="1.5">
                            <animateMotion dur="2.6s" repeatCount="indefinite" path="M200,70 C245,82 285,98 338,118"/>
                        </circle>
                    </svg>
                </div>

                <!-- Mini table -->
                <div class="preview-table">
                    <div class="preview-row">
                        <span style="flex:1;font-weight:700;">KAA 001A</span>
                        <span style="color:var(--muted);">46K/Y Kawangware</span>
                        <span class="preview-chip chip-green">Active</span>
                    </div>
                    <div class="preview-row">
                        <span style="flex:1;font-weight:700;">KAA 002B</span>
                        <span style="color:var(--muted);">19C Komarocks</span>
                        <span class="preview-chip chip-green">Active</span>
                    </div>
                    <div class="preview-row">
                        <span style="flex:1;font-weight:700;">KAA 003C</span>
                        <span style="color:var(--muted);">111 Ngong</span>
                        <span class="preview-chip chip-yellow">Maintenance</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features">
    <div class="features-inner">
        <div class="feature">
            <div class="feature-icon"><i class='bx bx-map'></i></div>
            <h3>Digital Matatus Route Map</h3>
            <p>All 22 Nairobi matatu routes from the Digital Matatus reference map, visualised with animated live assignments and driver pairings.</p>
        </div>
        <div class="feature">
            <div class="feature-icon"><i class='bx bxs-group'></i></div>
            <h3>Crew & Driver Management</h3>
            <p>Assign drivers to routes and matatus in seconds. Track active crew, monitor license details, and manage the full 40-driver roster.</p>
        </div>
        <div class="feature">
            <div class="feature-icon"><i class='bx bxs-report'></i></div>
            <h3>Inspections & Compliance</h3>
            <p>Log vehicle inspections, track pass/fail results, manage insurance expiry, and keep on top of maintenance schedules — all in one place.</p>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    Built on the <a href="https://digitalmatatus.com" target="_blank" rel="noreferrer">Digital Matatus</a>
    Nairobi route dataset &mdash; Civic Data Design Lab, MIT &amp; University of Nairobi.
</footer>

<script>
// Animate metric counter on load
function countUp(id, target, duration) {
    const el = document.getElementById(id);
    const step = target / (duration / 16);
    let cur = 0;
    const timer = setInterval(() => {
        cur = Math.min(cur + step, target);
        el.textContent = Math.round(cur);
        if (cur >= target) clearInterval(timer);
    }, 16);
}

window.addEventListener('load', () => {
    setTimeout(() => countUp('animVal1', 40, 900),  200);
    setTimeout(() => countUp('animVal2', 40, 900),  350);
    setTimeout(() => countUp('animVal3', 22, 800),  500);
    setTimeout(() => countUp('animVal4',  2, 600),  650);
});
</script>
</body>
</html>
