<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuánto Sabe - Sobre Nosotros</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        /* --- RESET Y VARIABLES --- */
        :root {
            --primary-color: #00f0ff;
            --secondary-color: #ff00ff;
            --accent-color: #00ffd1;
            --dark-bg: #030015;
            --card-bg: rgba(15, 6, 43, 0.85);
            --text-light: #e6f7ff;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* --- BASE STYLES --- */
        html, body {
            min-height: 100vh;
            width: 100vw;
            overflow-x: hidden;
            font-family: 'Montserrat', sans-serif;
            background: radial-gradient(circle at center, #210054 60%, var(--dark-bg) 100%);
            color: var(--text-light);
            position: relative;
            scroll-behavior: smooth;
        }
        
        /* --- FONDO DE PARTÍCULAS --- */
        .particles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1;
            pointer-events: none;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            border-radius: 50%;
            filter: blur(12px);
            opacity: 0.4;
            animation: float 8s infinite ease-in-out alternate;
        }
        
        .particle:nth-child(1) {
            width: 160px; height: 160px;
            background: var(--primary-color);
            top: 10%; left: 13%;
            animation-duration: 9s;
        }
        
        .particle:nth-child(2) {
            width: 110px; height: 110px;
            background: var(--secondary-color);
            top: 70%; left: 80%;
            animation-delay: 2s;
            animation-duration: 11s;
        }
        
        .particle:nth-child(3) {
            width: 80px; height: 80px;
            background: var(--accent-color);
            top: 66%; left: 27%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            to { 
                transform: translateY(-60px) scale(1.1) rotate(16deg); 
                opacity: 0.6; 
            }
        }
        
        /* --- NAV ANCLAS --- */
        .nav-anchors {
            position: fixed;
            top: 6rem;
            left: 2rem;
            z-index: 50;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .nav-link {
            font-family: 'Orbitron', sans-serif;
            font-size: 0.9rem;
            color: var(--text-light);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border: 1px solid rgba(0, 240, 255, 0.3);
            border-radius: 20px;
            transition: var(--transition);
            background: rgba(15, 6, 43, 0.6);
            backdrop-filter: blur(5px);
        }
        
        .nav-link:hover {
            color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 0 10px rgba(0, 240, 255, 0.5);
            transform: translateX(5px);
        }
        
        /* --- SECCIONES --- */
        section {
            position: relative;
            z-index: 2;
            padding: 6rem 2rem;
        }
        
        .section-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            color: var(--primary-color);
            text-shadow: 0 0 10px rgba(0, 240, 255, 0.5);
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
        }
        
        /* --- HERO SECTION --- */
        #hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        .hero-content {
            max-width: 800px;
        }
        
        .hero-logo {
            width: 300px;
            height: auto;
            margin: 0 auto 2rem;
            filter: drop-shadow(0 0 20px var(--primary-color));
        }
        
        .hero-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-light);
            text-shadow: 0 0 15px rgba(0, 240, 255, 0.7);
        }
        
        .hero-subtitle {
            font-size: 1.4rem;
            margin-bottom: 2rem;
            color: var(--primary-color);
        }
        
        .hero-description {
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 3rem;
            opacity: 0.9;
        }
        
        /* --- EXPRESS SECTION --- */
        #express {
            background: rgba(5, 1, 20, 0.7);
        }
        
        .express-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2.5rem;
            border: 1px solid rgba(0, 240, 255, 0.2);
            backdrop-filter: blur(8px);
            box-shadow: 0 10px 30px rgba(0, 240, 255, 0.1),
                        0 0 60px rgba(133, 4, 236, 0.05);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, 
                      rgba(0, 240, 255, 0.05) 0%, 
                      transparent 70%);
            z-index: -1;
            animation: rotate 20s linear infinite;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 240, 255, 0.2),
                        0 0 80px rgba(133, 4, 236, 0.1);
        }
        
        .express-card {
            text-align: center;
            padding: 2rem;
        }
        
        .express-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: var(--accent-color);
            filter: drop-shadow(0 0 10px var(--accent-color));
        }
        
        .express-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: var(--accent-color);
        }
        
        .rule-description {
            line-height: 1.6;
            opacity: 0.9;
        }
        
        /* --- FOOTER --- */
        footer {
            background: rgba(3, 0, 21, 0.9);
            padding: 4rem 2rem;
            text-align: center;
            position: relative;
            z-index: 2;
            border-top: 1px solid rgba(0, 240, 255, 0.2);
        }
        
        .footer-content {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .footer-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }
        
        .footer-text {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .footer-highlight {
            color: var(--accent-color);
            font-weight: 500;
        }
        
        /* --- BOTÓN --- */
        .cta-button {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            font-family: 'Orbitron', sans-serif;
            font-size: 1.1rem;
            font-weight: 500;
            border-radius: 30px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                      transparent, 
                      rgba(0, 240, 255, 0.2), 
                      transparent);
            transition: all 0.6s ease;
            z-index: -1;
        }
        
        .cta-button:hover {
            background: var(--primary-color);
            color: #00122c;
            box-shadow: 0 0 20px var(--primary-color),
                       0 0 40px rgba(0, 240, 255, 0.3);
            transform: translateY(-3px);
        }
        
        .cta-button:hover::before {
            left: 100%;
        }
        
        /* --- ANIMACIONES --- */
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            .nav-anchors {
                top: 1rem;
                left: 1rem;
            }
            
            .nav-link {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-logo {
                width: 200px;
            }
            
            section {
                padding: 4rem 1rem;
            }
            
            .card {
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 480px) {
            .nav-anchors {
                display: none;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 1.7rem;
            }
        }
    </style>
</head>
<body>
    <!-- Fondo de partículas -->
    <div class="particles-container">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Navegación de anclas -->
    <nav class="nav-anchors">
        <a href="#hero" class="nav-link">Inicio</a>
        <a href="#rules" class="nav-link">Reglas</a>
        <a href="#express" class="nav-link">Express</a>
        <a href="#team" class="nav-link">Equipo</a>
    </nav>

    <!-- Hero Section -->
    <section id="hero">
        <div class="section-container">
            <div class="hero-content fade-in">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Cuánto Sabe" class="hero-logo">
                <h1 class="hero-title">¿QUÉ ES CUÁNTO SABE?</h1>
                <p class="hero-subtitle">El juego interactivo donde el conocimiento es tu mejor arma</p>
                <p class="hero-description">
                    Cuánto Sabe es una experiencia única que combina trivia, humor y participación en vivo. 
                    Los invitados demuestran su conocimiento enfrentándose a preguntas desafiantes mientras 
                    el público participa desde cuantosabe.com.ar votando en tiempo real por las respuestas correctas.
                </p>
                <a href="#rules" class="cta-button">Descubre Cómo Jugar</a>
            </div>
        </div>
    </section>

    <!-- Rules Section (Livewire) -->
    <section id="rules">
        <div class="section-container">
            <h2 class="section-title">REGLAS DEL JUEGO</h2>
            @livewire('rules-section')
        </div>
    </section>

    <!-- Express Section -->
    <section id="express">
        <div class="section-container">
            <h2 class="section-title">VERSIÓN EXPRESS</h2>
            <p class="hero-description text-center" style="max-width: 800px; margin: 0 auto 2rem;">
                Edición rápida: 5 preguntas, objetivo 10 puntos. Una ruleta = una pregunta; 
                el público vota desde la web y se muestra la tendencia en pantalla.
            </p>
            
            <div class="express-grid">
                <div class="card express-card fade-in">
                    <div class="express-icon">⚡</div>
                    <h3 class="express-title">RONDAS EXPRESS</h3>
                    <p class="rule-description">Cada ronda: 1 giro → 1 pregunta. Solo 5 rondas para alcanzar la victoria.</p>
                </div>
                
                <div class="card express-card fade-in">
                    <div class="express-icon">🎯</div>
                    <h3 class="express-title">META RÁPIDA</h3>
                    <p class="rule-description">Invitado: alcanzar 10 puntos en solo 5 preguntas. Cada acierto vale 2 puntos.</p>
                </div>
                
                <div class="card express-card fade-in">
                    <div class="express-icon">👥</div>
                    <h3 class="express-title">INTERACCIÓN INMEDIATA</h3>
                    <p class="rule-description">El público vota en tiempo real desde cuantosabe.com.ar con resultados instantáneos.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section (Livewire) -->
    <section id="team">
        <div class="section-container">
            <h2 class="section-title">NUESTRO EQUIPO</h2>
            @livewire('team-section')
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <h3 class="footer-title">¿QUERÉS PARTICIPAR?</h3>
            <p class="footer-text">
                Entrá a <span class="footer-highlight">cuantosabe.com.ar</span> y votá en tiempo real.
            </p>
            <a href="/" class="cta-button">Ir al sitio</a>
        </div>
    </footer>

    <script>
        // Animación de aparición al hacer scroll
        document.addEventListener('DOMContentLoaded', function() {
            const fadeElements = document.querySelectorAll('.fade-in');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });
            
            fadeElements.forEach(element => {
                observer.observe(element);
            });
        });
    </script>
</body>
</html>