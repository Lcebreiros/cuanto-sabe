<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuánto Sabe - Juego Interactivo</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #f59e0b;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --success: #10b981;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
        }
        
        .dark-mode {
            background-color: #0f172a;
            color: #f1f5f9;
        }
        
        .hero-section {
            position: relative;
            padding: 6rem 1.5rem;
            text-align: center;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .dark-mode .hero-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        
        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        
        .dark-mode .hero-title {
            color: #f1f5f9;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            max-width: 800px;
            margin: 0 auto 2rem;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .dark-mode .hero-subtitle {
            color: #cbd5e1;
        }
        
        .highlight {
            color: #fbbf24;
            font-weight: 700;
        }
        
        .dark-mode .highlight {
            color: #f59e0b;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .dark-mode .feature-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }
        
        .dark-mode .feature-title {
            color: #f1f5f9;
        }
        
        .feature-description {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
        }
        
        .dark-mode .feature-description {
            color: #cbd5e1;
        }
        
        .cta-container {
            margin-top: 3rem;
        }
        
        .cta-button {
            display: inline-block;
            background: #f59e0b;
            color: #1e293b;
            font-weight: 700;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
        }
        
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.6);
            background: #fbbf24;
        }
        
        .theme-toggle {
            position: absolute;
            top: 2rem;
            right: 2rem;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .decoration {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            z-index: 1;
        }
        
        .decoration-1 {
            width: 300px;
            height: 300px;
            background: #f59e0b;
            top: -100px;
            left: -100px;
        }
        
        .decoration-2 {
            width: 200px;
            height: 200px;
            background: #10b981;
            bottom: -50px;
            right: 10%;
        }
        
        .decoration-3 {
            width: 150px;
            height: 150px;
            background: #4f46e5;
            top: 20%;
            right: -50px;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <section class="hero-section">
        <div class="decoration decoration-1"></div>
        <div class="decoration decoration-2"></div>
        <div class="decoration decoration-3"></div>
        
        <button class="theme-toggle" id="themeToggle">🌙</button>
        
        <div class="hero-content">
            <h1 class="hero-title">¿Qué es <span class="highlight">Cuánto Sabe</span>?</h1>
            <p class="hero-subtitle">
                Un juego interactivo donde el conocimiento, el humor y la participación en vivo se combinan para crear experiencias únicas.
            </p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3 class="feature-title">Trivia Dinámica</h3>
                    <p class="feature-description">
                        Los invitados se enfrentan a preguntas desafiantes sobre diversos temas, con categorías definidas por una ruleta emocionante.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3 class="feature-title">Audiencia Participativa</h3>
                    <p class="feature-description">
                        El público vota en tiempo real desde cuantosabe.com.ar, influyendo directamente en el desarrollo del juego.
                    </p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🎮</div>
                    <h3 class="feature-title">Interacción en Vivo</h3>
                    <p class="feature-description">
                        Los invitados deciden si seguir la tendencia del público o arriesgarse con su propio conocimiento para alcanzar la meta.
                    </p>
                </div>
            </div>
            
            <div class="cta-container">
                <a href="#" class="cta-button">¡Participa Ahora!</a>
            </div>
        </div>
    </section>

    <script>
        // Toggle para modo oscuro/claro
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        
        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            themeToggle.textContent = body.classList.contains('dark-mode') ? '☀️' : '🌙';
        });
        
        // Efecto de aparición suave para las tarjetas
        const featureCards = document.querySelectorAll('.feature-card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        featureCards.forEach(card => {
            card.style.opacity = 0;
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>