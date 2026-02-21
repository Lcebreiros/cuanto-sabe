<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cuanto Sabe — Sobre el juego</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Variables ─────────────────────────────────────── */
        :root {
            --cyan:    #00f0ff;
            --magenta: #ff00ff;
            --green:   #19ff8c;
            --gold:    #ffe47a;
            --bg:      #030015;
            --card-bg: rgba(8, 4, 28, 0.80);
            --border:  rgba(0, 240, 255, 0.15);
            --text:    #e6f7ff;
            --muted:   rgba(230, 247, 255, 0.55);
            --ease:    cubic-bezier(0.25, 0.8, 0.25, 1);
            --nav-h:   60px;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ── Background ────────────────────────────────────── */
        .bg-gradient {
            position: fixed; inset: 0; z-index: 0;
            background: radial-gradient(circle at center, #1e0047 60%, #0c0125 100%);
        }

        .particles { position: fixed; inset: 0; z-index: 2; pointer-events: none; }

        .star {
            position: absolute; border-radius: 50%;
            animation: twinkle var(--dur, 4s) var(--delay, 0s) infinite ease-in-out alternate;
        }
        @keyframes twinkle {
            0%   { opacity: var(--op-from, 0.1); transform: scale(1); }
            100% { opacity: var(--op-to,   0.6); transform: scale(1.4); }
        }

        .orb {
            position: absolute; border-radius: 50%; filter: blur(80px);
            animation: drift var(--dur, 12s) var(--delay, 0s) infinite ease-in-out alternate;
        }
        @keyframes drift {
            to { transform: translate(var(--dx, 30px), var(--dy, -40px)) scale(1.08); }
        }

        /* ── Top nav ───────────────────────────────────────── */
        .top-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--nav-h);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            background: rgba(3, 0, 21, 0.75);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            color: var(--cyan);
            text-decoration: none;
            padding: 7px 14px;
            border: 1px solid rgba(0,240,255,0.3);
            border-radius: 999px;
            background: rgba(0,240,255,0.05);
            transition: background 0.2s, border-color 0.2s, box-shadow 0.2s;
            white-space: nowrap;
        }
        .back-btn:hover {
            background: rgba(0,240,255,0.12);
            border-color: var(--cyan);
            box-shadow: 0 0 10px rgba(0,240,255,0.3);
        }
        .back-btn svg { flex-shrink: 0; }

        .nav-pills {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .nav-pill {
            font-family: 'Orbitron', sans-serif;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            color: rgba(230,247,255,0.6);
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid transparent;
            transition: color 0.2s, border-color 0.2s, background 0.2s;
        }
        .nav-pill:hover, .nav-pill.active {
            color: var(--cyan);
            border-color: rgba(0,240,255,0.3);
            background: rgba(0,240,255,0.07);
        }

        @media (max-width: 480px) {
            .nav-pills { display: none; }
            .top-nav { padding: 0 14px; }
        }

        /* ── Page layout ───────────────────────────────────── */
        .page { position: relative; z-index: 3; padding-top: var(--nav-h); }

        /* ── Shared section layout ─────────────────────────── */
        section {
            padding: 80px 20px;
            max-width: 960px;
            margin: 0 auto;
        }

        section.full-bg {
            max-width: 100%;
            padding-left: 0;
            padding-right: 0;
        }
        section.full-bg .section-inner {
            max-width: 960px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ── Section header ────────────────────────────────── */
        .section-header {
            text-align: center;
            margin-bottom: 56px;
        }

        .section-label {
            display: inline-block;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--cyan);
            margin-bottom: 12px;
            opacity: 0.8;
        }

        .section-title {
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(1.5rem, 4vw, 2.2rem);
            font-weight: 700;
            color: var(--text);
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .section-title span {
            background: linear-gradient(90deg, var(--cyan), var(--magenta));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-rule {
            width: 60px; height: 2px;
            background: linear-gradient(90deg, var(--cyan), var(--magenta));
            margin: 0 auto;
            border-radius: 2px;
        }

        /* ── Hero ──────────────────────────────────────────── */
        #inicio {
            min-height: calc(100vh - var(--nav-h));
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .hero-content { max-width: 720px; }

        .hero-logo {
            width: clamp(140px, 25vw, 220px);
            height: auto;
            margin-bottom: 28px;
            filter:
                drop-shadow(0 0 22px rgba(0,240,255,0.7))
                drop-shadow(0 0 8px rgba(255,0,255,0.3));
            animation: logo-pulse 3.5s ease-in-out infinite;
        }
        @keyframes logo-pulse {
            0%, 100% { filter: drop-shadow(0 0 22px rgba(0,240,255,0.7)) drop-shadow(0 0 8px rgba(255,0,255,0.3)); }
            50%       { filter: drop-shadow(0 0 36px rgba(0,240,255,1))   drop-shadow(0 0 14px rgba(255,0,255,0.5)); }
        }

        .hero-tag {
            display: inline-block;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--cyan);
            background: rgba(0,240,255,0.08);
            border: 1px solid rgba(0,240,255,0.25);
            border-radius: 999px;
            padding: 5px 16px;
            margin-bottom: 20px;
        }

        .hero-title {
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(1.6rem, 5vw, 2.8rem);
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff 30%, var(--cyan) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-description {
            font-size: clamp(0.9rem, 2vw, 1.05rem);
            line-height: 1.75;
            color: var(--muted);
            margin-bottom: 36px;
        }

        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #001c2e;
            background: var(--cyan);
            text-decoration: none;
            padding: 13px 28px;
            border-radius: 999px;
            box-shadow: 0 0 20px rgba(0,240,255,0.4), 0 0 40px rgba(0,240,255,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .hero-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 32px rgba(0,240,255,0.7), 0 0 60px rgba(0,240,255,0.2);
        }

        /* ── Scroll indicator ──────────────────────────────── */
        .scroll-hint {
            position: absolute;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            font-size: 0.68rem;
            letter-spacing: 0.12em;
            color: var(--muted);
            animation: bounce-hint 2s ease-in-out infinite;
        }
        @keyframes bounce-hint {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50%       { transform: translateX(-50%) translateY(6px); }
        }

        /* ── Card ──────────────────────────────────────────── */
        .glass-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px 28px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            position: relative;
            overflow: hidden;
            transition: transform 0.25s var(--ease), box-shadow 0.25s var(--ease);
        }
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0,240,255,0.4), transparent);
        }
        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,240,255,0.1), 0 0 60px rgba(133,4,236,0.06);
        }

        /* ── Rules grid ────────────────────────────────────── */
        .rules-wrap {
            /* Livewire component wrapper */
        }

        /* ── Express section ───────────────────────────────── */
        #express.full-bg {
            background: rgba(5, 1, 20, 0.6);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .express-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .express-card {
            text-align: center;
        }

        .express-icon {
            font-size: 2.4rem;
            margin-bottom: 14px;
            display: block;
            filter: drop-shadow(0 0 8px rgba(0,240,255,0.5));
        }

        .express-card-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: var(--cyan);
            margin-bottom: 10px;
        }

        .express-card-text {
            font-size: 0.88rem;
            line-height: 1.65;
            color: var(--muted);
        }

        /* ── Footer ────────────────────────────────────────── */
        .site-footer {
            position: relative;
            z-index: 3;
            text-align: center;
            padding: 40px 20px 32px;
            border-top: 1px solid var(--border);
            background: rgba(3,0,21,0.85);
        }

        .footer-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--cyan);
            margin-bottom: 10px;
            letter-spacing: 0.05em;
        }

        .footer-sub {
            font-size: 0.82rem;
            color: var(--muted);
            margin-bottom: 20px;
        }

        .footer-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: var(--cyan);
            border: 1px solid rgba(0,240,255,0.35);
            border-radius: 999px;
            padding: 9px 22px;
            text-decoration: none;
            transition: background 0.2s, box-shadow 0.2s;
            margin-bottom: 24px;
        }
        .footer-cta:hover {
            background: rgba(0,240,255,0.1);
            box-shadow: 0 0 12px rgba(0,240,255,0.3);
        }

        .footer-copy {
            font-size: 0.7rem;
            color: rgba(0,240,255,0.28);
            letter-spacing: 0.04em;
        }

        /* ── Fade-in on scroll ─────────────────────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.7s var(--ease), transform 0.7s var(--ease);
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }

        /* ── Active nav pill ───────────────────────────────── */
        .nav-pill.active {
            color: var(--cyan);
            border-color: rgba(0,240,255,0.35);
            background: rgba(0,240,255,0.08);
        }
    </style>
</head>
<body>

    <!-- Background -->
    <div class="bg-gradient"></div>
    <div class="particles" id="particles"></div>

    <!-- Top nav -->
    <nav class="top-nav" id="topNav">
        <a href="{{ route('guest-dashboard') }}" class="back-btn">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M9 2L4 7l5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Menú
        </a>
        <div class="nav-pills" id="navPills">
            <a href="#inicio"   class="nav-pill active" data-section="inicio">Inicio</a>
            <a href="#reglas"   class="nav-pill"        data-section="reglas">Reglas</a>
            <a href="#express"  class="nav-pill"        data-section="express">Express</a>
            <a href="#equipo"   class="nav-pill"        data-section="equipo">Equipo</a>
        </div>
    </nav>

    <div class="page">

        <!-- ── Hero ─────────────────────────────────────────── -->
        <section id="inicio" style="position:relative;">
            <div class="hero-content">
                <img src="{{ asset('images/logo.png') }}" alt="Cuanto Sabe" class="hero-logo">
                <p class="hero-tag">El juego de conocimiento en vivo</p>
                <h1 class="hero-title">¿Cuánto sabés realmente?</h1>
                <p class="hero-description">
                    Una experiencia única que combina trivia, interacción y competencia en tiempo real.
                    El invitado responde preguntas desafiantes mientras el público vota desde
                    cuantosabe.com.ar y sus tendencias influyen en el resultado.
                </p>
                <a href="#reglas" class="hero-cta">
                    Ver cómo se juega <span>↓</span>
                </a>
            </div>
            <div class="scroll-hint">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path d="M4 7l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                SCROLL
            </div>
        </section>

        <!-- ── Reglas ────────────────────────────────────────── -->
        <section id="reglas">
            <div class="section-header reveal">
                <p class="section-label">Cómo jugar</p>
                <h2 class="section-title">Reglas del <span>juego</span></h2>
                <div class="section-rule"></div>
            </div>
            <div class="rules-wrap reveal reveal-delay-1">
                @livewire('rules-section')
            </div>
        </section>

        <!-- ── Express ──────────────────────────────────────── -->
        <section id="express" class="full-bg">
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-label">Edición rápida</p>
                    <h2 class="section-title">Versión <span>Express</span></h2>
                    <div class="section-rule"></div>
                </div>

                <p class="reveal" style="text-align:center; font-size:0.92rem; color:var(--muted); max-width:600px; margin:0 auto 40px; line-height:1.75;">
                    Edición rápida de 5 preguntas con objetivo de 10 puntos.
                    Cada giro de ruleta es una pregunta; el público vota en tiempo real
                    y la tendencia se muestra en pantalla.
                </p>

                <div class="express-grid">
                    <div class="glass-card express-card reveal reveal-delay-1">
                        <span class="express-icon">⚡</span>
                        <h3 class="express-card-title">Rondas Express</h3>
                        <p class="express-card-text">1 giro → 1 pregunta. Solo 5 rondas para alcanzar la victoria.</p>
                    </div>
                    <div class="glass-card express-card reveal reveal-delay-2">
                        <span class="express-icon">🎯</span>
                        <h3 class="express-card-title">Meta Rápida</h3>
                        <p class="express-card-text">Invitado: alcanzar 10 puntos en 5 preguntas. Cada acierto vale 2 puntos.</p>
                    </div>
                    <div class="glass-card express-card reveal reveal-delay-3">
                        <span class="express-icon">👥</span>
                        <h3 class="express-card-title">Interacción Inmediata</h3>
                        <p class="express-card-text">El público vota en tiempo real desde cuantosabe.com.ar con resultados instantáneos.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ── Equipo ────────────────────────────────────────── -->
        <section id="equipo">
            <div class="section-header reveal">
                <p class="section-label">Quiénes somos</p>
                <h2 class="section-title">Nuestro <span>equipo</span></h2>
                <div class="section-rule"></div>
            </div>
            <div class="reveal reveal-delay-1">
                @livewire('team-section')
            </div>
        </section>

    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <h3 class="footer-title">¿Querés participar?</h3>
        <p class="footer-sub">Entrá y votá en tiempo real desde tu celular.</p>
        <a href="{{ route('guest-dashboard') }}" class="footer-cta">Ir al dashboard →</a>
        <p class="footer-copy">© {{ date('Y') }} Cuanto Sabe &nbsp;·&nbsp; Desarrollado por Leandro Cebreiros</p>
    </footer>

    <script>
    (() => {
        'use strict';

        /* ── Particles ──────────────────────────────────────── */
        const container = document.getElementById('particles');

        const orbs = [
            { w:300, h:240, top:'5%',  left:'2%',  color:'rgba(0,50,110,0.5)',  dur:'14s', delay:'0s',  dx:'35px',  dy:'-45px' },
            { w:220, h:180, top:'58%', left:'72%', color:'rgba(70,0,110,0.45)', dur:'17s', delay:'3s',  dx:'-25px', dy:'38px'  },
            { w:160, h:160, top:'40%', left:'18%', color:'rgba(0,200,255,0.05)',dur:'10s', delay:'5s',  dx:'18px',  dy:'-28px' },
            { w:180, h:140, top:'82%', left:'5%',  color:'rgba(0,80,180,0.28)', dur:'12s', delay:'1.5s',dx:'28px',  dy:'-18px' },
        ];
        orbs.forEach(cfg => {
            const el = document.createElement('div');
            el.className = 'orb';
            Object.assign(el.style, {
                width: cfg.w+'px', height: cfg.h+'px',
                top: cfg.top, left: cfg.left,
                background: cfg.color,
                '--dur': cfg.dur, '--delay': cfg.delay,
                '--dx': cfg.dx, '--dy': cfg.dy,
            });
            container.appendChild(el);
        });

        const colors = ['#00f0ff','#ffffff','#ff00ff','#19ff8c','#b8d4ff'];
        for (let i = 0; i < 50; i++) {
            const el = document.createElement('div');
            el.className = 'star';
            const s = Math.random() * 2.2 + 0.6;
            Object.assign(el.style, {
                width: s+'px', height: s+'px',
                top:  Math.random()*100+'%',
                left: Math.random()*100+'%',
                background: colors[Math.floor(Math.random()*colors.length)],
                '--dur':     (Math.random()*4+2).toFixed(1)+'s',
                '--delay':   (Math.random()*6).toFixed(1)+'s',
                '--op-from': (Math.random()*0.1+0.04).toFixed(2),
                '--op-to':   (Math.random()*0.45+0.25).toFixed(2),
            });
            container.appendChild(el);
        }

        /* ── Reveal on scroll ───────────────────────────────── */
        const revealEls = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.1 });
        revealEls.forEach(el => revealObserver.observe(el));

        /* ── Active nav pill on scroll ──────────────────────── */
        const sections  = ['inicio', 'reglas', 'express', 'equipo'];
        const navPills  = document.querySelectorAll('.nav-pill[data-section]');
        const navHeight = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-h'));

        function setActivePill() {
            let current = sections[0];
            sections.forEach(id => {
                const el = document.getElementById(id);
                if (el && el.getBoundingClientRect().top <= navHeight + 40) current = id;
            });
            navPills.forEach(p => p.classList.toggle('active', p.dataset.section === current));
        }

        window.addEventListener('scroll', setActivePill, { passive: true });
        setActivePill();
    })();
    </script>

</body>
</html>
