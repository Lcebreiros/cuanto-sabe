<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cuanto Sabe — El juego de conocimiento en vivo</title>
    <meta name="description" content="Competí contra el público en tiempo real. ¿Cuánto sabés realmente?">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        /* ── Variables ─────────────────────────────────────── */
        :root {
            --cyan:    #00f0ff;
            --magenta: #ff00ff;
            --green:   #19ff8c;
            --bg:      #030015;
            --card-bg: rgba(8, 4, 28, 0.82);
            --border:  rgba(0, 240, 255, 0.18);
            --text:    #e6f7ff;
            --muted:   rgba(230, 247, 255, 0.55);
            --ease:    cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* ── Reset ─────────────────────────────────────────── */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        /* ── Base ──────────────────────────────────────────── */
        html, body {
            min-height: 100vh;
            width: 100vw;
            overflow-x: hidden;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        /* ── Background layers ─────────────────────────────── */
        .bg-gradient {
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at center, #1e0047 60%, #0c0125 100%);
            z-index: 0;
        }

        /* ── Particles ─────────────────────────────────────── */
        .particles {
            position: fixed;
            inset: 0;
            z-index: 2;
            pointer-events: none;
        }

        .star {
            position: absolute;
            border-radius: 50%;
            animation: twinkle var(--dur, 4s) var(--delay, 0s) infinite ease-in-out alternate;
        }

        @keyframes twinkle {
            0%   { opacity: var(--op-from, 0.1); transform: scale(1); }
            100% { opacity: var(--op-to,   0.7); transform: scale(1.4); }
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: drift var(--dur, 12s) var(--delay, 0s) infinite ease-in-out alternate;
        }

        @keyframes drift {
            to { transform: translate(var(--dx, 30px), var(--dy, -40px)) scale(1.08); }
        }

        /* ── Page layout ───────────────────────────────────── */
        .page {
            position: relative;
            z-index: 3;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 20px 24px;
            gap: 0;
        }

        /* ── Card wrapper (animated border) ────────────────── */
        .card-wrap {
            position: relative;
            width: 100%;
            max-width: 460px;
            border-radius: 28px;
            padding: 2px; /* border thickness */
            background: conic-gradient(
                from var(--angle, 0deg),
                var(--cyan) 0%,
                var(--magenta) 25%,
                transparent 40%,
                transparent 60%,
                var(--cyan) 75%,
                var(--magenta) 90%,
                var(--cyan) 100%
            );
            animation: spin-border 6s linear infinite;
        }

        @property --angle {
            syntax: '<angle>';
            initial-value: 0deg;
            inherits: false;
        }

        @keyframes spin-border {
            to { --angle: 360deg; }
        }

        /* ── Card ──────────────────────────────────────────── */
        .card {
            background: var(--card-bg);
            border-radius: 26px;
            padding: 44px 36px 36px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            text-align: center;
            position: relative;
            overflow: hidden;

            /* Entrance animation */
            opacity: 0;
            transform: translateY(24px);
            animation: card-in 0.7s 0.1s var(--ease) forwards;
        }

        @keyframes card-in {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Noise texture overlay */
        .card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 26px;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
            opacity: 0.4;
            pointer-events: none;
            z-index: 0;
        }

        /* Top glow */
        .card::after {
            content: '';
            position: absolute;
            top: -1px;
            left: 15%;
            right: 15%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            z-index: 1;
        }

        .card > * { position: relative; z-index: 2; }

        /* ── Logo ──────────────────────────────────────────── */
        .logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 8px;
            opacity: 0;
            animation: fade-up 0.5s 0.5s var(--ease) forwards;
        }

        .logo {
            width: 160px;
            height: auto;
            filter:
                drop-shadow(0 0 18px rgba(0,240,255,0.7))
                drop-shadow(0 0 6px rgba(0,240,255,0.4));
            animation: logo-pulse 3.5s ease-in-out infinite;
        }

        @keyframes logo-pulse {
            0%, 100% { filter: drop-shadow(0 0 18px rgba(0,240,255,0.7)) drop-shadow(0 0 6px rgba(0,240,255,0.4)); }
            50%       { filter: drop-shadow(0 0 30px rgba(0,240,255,0.95)) drop-shadow(0 0 12px rgba(255,0,255,0.3)); }
        }

        /* ── Tagline ───────────────────────────────────────── */
        .tagline {
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(0.7rem, 2.5vw, 0.8rem);
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            background: linear-gradient(90deg, var(--cyan), var(--magenta), var(--cyan));
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer-text 4s linear infinite, fade-up 0.5s 0.65s var(--ease) both;
            margin-bottom: 20px;
        }

        @keyframes shimmer-text {
            to { background-position: 200% center; }
        }

        /* ── Feature badges ────────────────────────────────── */
        .badges {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 22px;
            opacity: 0;
            animation: fade-up 0.5s 0.8s var(--ease) forwards;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            color: var(--text);
            background: rgba(0, 240, 255, 0.07);
            border: 1px solid rgba(0, 240, 255, 0.2);
            border-radius: 999px;
            padding: 5px 12px;
            white-space: nowrap;
        }

        .badge-icon { font-size: 0.85rem; }

        /* ── Divider ───────────────────────────────────────── */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border) 30%, var(--border) 70%, transparent);
            margin: 0 0 20px;
            opacity: 0;
            animation: fade-up 0.5s 0.9s var(--ease) forwards;
        }

        /* ── Description ───────────────────────────────────── */
        .description {
            font-size: 0.88rem;
            line-height: 1.7;
            color: var(--muted);
            margin-bottom: 28px;
            opacity: 0;
            animation: fade-up 0.5s 1s var(--ease) forwards;
        }

        .description strong {
            color: var(--text);
            font-weight: 500;
        }

        /* ── CTA Button ────────────────────────────────────── */
        .cta-wrap {
            opacity: 0;
            animation: fade-up 0.5s 1.1s var(--ease) forwards;
        }

        .cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            max-width: 280px;
            padding: 15px 24px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            color: #001c2e;
            background: var(--cyan);
            border: none;
            border-radius: 999px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s var(--ease), box-shadow 0.2s var(--ease);
            box-shadow:
                0 0 20px rgba(0,240,255,0.45),
                0 0 40px rgba(0,240,255,0.15),
                inset 0 1px 0 rgba(255,255,255,0.25);
            animation: cta-pulse 2.5s 1.6s ease-in-out infinite;
        }

        @keyframes cta-pulse {
            0%, 100% { box-shadow: 0 0 20px rgba(0,240,255,0.45), 0 0 40px rgba(0,240,255,0.15), inset 0 1px 0 rgba(255,255,255,0.25); }
            50%       { box-shadow: 0 0 32px rgba(0,240,255,0.8),  0 0 64px rgba(0,240,255,0.3),  inset 0 1px 0 rgba(255,255,255,0.25); }
        }

        /* Shimmer sweep */
        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: cta-shimmer 3s 2s ease-in-out infinite;
        }

        @keyframes cta-shimmer {
            0%   { left: -60%; }
            40%, 100% { left: 140%; }
        }

        .cta:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow:
                0 0 36px rgba(0,240,255,0.9),
                0 0 72px rgba(0,240,255,0.3),
                inset 0 1px 0 rgba(255,255,255,0.25);
        }

        .cta:active { transform: translateY(0) scale(0.99); }

        .cta-arrow {
            font-size: 1.1rem;
            transition: transform 0.2s var(--ease);
        }
        .cta:hover .cta-arrow { transform: translateX(4px); }

        /* ── Footer ────────────────────────────────────────── */
        .footer {
            margin-top: 28px;
            font-size: 0.72rem;
            color: rgba(0,240,255,0.3);
            letter-spacing: 0.03em;
            text-align: center;
            opacity: 0;
            animation: fade-up 0.5s 1.3s var(--ease) forwards;
        }

        /* ── Shared entrance ───────────────────────────────── */
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Ripple ────────────────────────────────────────── */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.35);
            transform: scale(0);
            pointer-events: none;
            animation: ripple-out 0.6s ease-out forwards;
        }

        @keyframes ripple-out {
            to { transform: scale(4); opacity: 0; }
        }

        /* ── Fallback: no @property support ────────────────── */
        @supports not (background: conic-gradient(from 0deg, red, blue)) {
            .card-wrap {
                background: linear-gradient(135deg, rgba(0,240,255,0.4), rgba(255,0,255,0.3), rgba(0,240,255,0.4));
            }
        }

        /* ── Responsive ────────────────────────────────────── */
        @media (max-width: 500px) {
            .card { padding: 36px 24px 28px; }
            .logo { width: 130px; }
            .cta  { font-size: 0.88rem; padding: 13px 20px; }
        }

        @media (max-width: 360px) {
            .card { padding: 28px 18px 24px; }
            .badges { gap: 6px; }
            .badge { font-size: 0.67rem; padding: 4px 10px; }
        }
    </style>
</head>
<body>

    <!-- Background layers -->
    <div class="bg-gradient"></div>

    <!-- Particles -->
    <div class="particles" id="particles"></div>

    <!-- Page -->
    <main class="page">

        <div class="card-wrap">
            <div class="card" id="card">

                <!-- Logo -->
                <div class="logo-wrap">
                    <img src="{{ asset('images/logo.png') }}" alt="Cuanto Sabe" class="logo">
                </div>

                <!-- Tagline -->
                <p class="tagline">El juego de conocimiento en vivo</p>

                <!-- Feature badges -->
                <div class="badges">
                    <span class="badge"><span class="badge-icon">🎯</span> En vivo</span>
                    <span class="badge"><span class="badge-icon">⚡</span> Por puntos</span>
                    <span class="badge"><span class="badge-icon">👥</span> Multijugador</span>
                </div>

                <!-- Divider -->
                <div class="divider"></div>

                <!-- Description -->
                <p class="description">
                    Competí contra el público en tiempo real.<br>
                    <strong>¿Cuánto sabés realmente?</strong>
                </p>

                <!-- CTA -->
                <div class="cta-wrap">
                    <a href="{{ route('guest-dashboard') }}" class="cta" id="cta">
                        Ingresar al juego
                        <span class="cta-arrow">→</span>
                    </a>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <p class="footer">
            © {{ date('Y') }} Cuanto Sabe &nbsp;·&nbsp; Desarrollado por Leandro Cebreiros
        </p>

    </main>

    <script>
    (() => {
        'use strict';

        /* ── Particles ──────────────────────────────────────── */
        const container = document.getElementById('particles');

        // Orbs (big blurred blobs)
        const orbConfig = [
            { w: 340, h: 260, top: '8%',  left: '5%',  color: 'rgba(0,60,120,0.55)',  dur: '14s', delay: '0s',   dx: '40px',  dy: '-50px'  },
            { w: 260, h: 200, top: '62%', left: '68%', color: 'rgba(80,0,120,0.50)',   dur: '17s', delay: '2s',   dx: '-30px', dy: '40px'   },
            { w: 180, h: 180, top: '45%', left: '20%', color: 'rgba(0,240,255,0.06)',  dur: '10s', delay: '4s',   dx: '20px',  dy: '-30px'  },
            { w: 200, h: 160, top: '80%', left: '5%',  color: 'rgba(0,100,200,0.30)',  dur: '13s', delay: '1s',   dx: '30px',  dy: '-20px'  },
        ];

        orbConfig.forEach(cfg => {
            const el = document.createElement('div');
            el.className = 'orb';
            Object.assign(el.style, {
                width: cfg.w + 'px', height: cfg.h + 'px',
                top: cfg.top, left: cfg.left,
                background: cfg.color,
                '--dur': cfg.dur, '--delay': cfg.delay,
                '--dx': cfg.dx,   '--dy': cfg.dy,
            });
            container.appendChild(el);
        });

        // Small twinkling stars
        const starCount = 55;
        for (let i = 0; i < starCount; i++) {
            const el = document.createElement('div');
            el.className = 'star';
            const size = Math.random() * 2.5 + 0.8;
            const colors = ['#00f0ff', '#ffffff', '#ff00ff', '#19ff8c', '#b8d4ff'];
            const color = colors[Math.floor(Math.random() * colors.length)];
            Object.assign(el.style, {
                width:  size + 'px',
                height: size + 'px',
                top:    Math.random() * 100 + '%',
                left:   Math.random() * 100 + '%',
                background: color,
                '--dur':     (Math.random() * 4 + 2).toFixed(1) + 's',
                '--delay':   (Math.random() * 6).toFixed(1) + 's',
                '--op-from': (Math.random() * 0.1 + 0.05).toFixed(2),
                '--op-to':   (Math.random() * 0.5 + 0.3).toFixed(2),
            });
            container.appendChild(el);
        }

        /* ── CTA ripple + nav ───────────────────────────────── */
        const card = document.getElementById('card');
        const cta  = document.getElementById('cta');

        cta.addEventListener('click', (e) => {
            e.preventDefault();
            const href = cta.href;

            // Ripple
            const r = document.createElement('div');
            r.className = 'ripple';
            const rect = cta.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height) * 1.5;
            Object.assign(r.style, {
                width: size + 'px', height: size + 'px',
                left: (e.clientX - rect.left - size / 2) + 'px',
                top:  (e.clientY - rect.top  - size / 2) + 'px',
            });
            cta.appendChild(r);
            r.addEventListener('animationend', () => r.remove());

            // Fade out card, then navigate
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity    = '0';
            card.style.transform  = 'translateY(-10px) scale(1.02)';
            setTimeout(() => { window.location.href = href; }, 480);
        });
    })();
    </script>

</body>
</html>
