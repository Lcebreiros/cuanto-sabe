<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuanto Sabe - Bienvenido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fuente estilo neon -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">

    <style>
body {
    margin: 0;
    font-family: 'Orbitron', sans-serif;
    background: radial-gradient(circle at center, #1e0047, #0c0125);
    color: white;
    min-height: 100vh;
    padding: 0;
    box-sizing: border-box;
    overflow-x: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100vw;
    position: relative;
}

/* Animaciones */
.fade-slide-out {
    opacity: 0;
    transform: translateY(-40px) scale(0.98);
    pointer-events: none;
    transition: opacity 0.6s cubic-bezier(.4,0,.2,1), transform 0.7s cubic-bezier(.4,0,.2,1);
}
.fade-slide-in {
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: auto;
    transition: opacity 0.7s cubic-bezier(.4,0,.2,1), transform 0.8s cubic-bezier(.4,0,.2,1);
}

/* Intro como overlay */
.intro-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    position: fixed;
    top: 0; left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 2;
    background: radial-gradient(circle at center, #1e0047 60%, #0c0125 100%);
    box-sizing: border-box;
}

/* CARD centrado */
.intro-card {
    padding: 32px 24px;
    max-width: 450px;
    width: 92vw;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 26px;
}

/* LOGO */
.intro-img-logo {
    width: 100%;
    max-width: 320px;
    height: auto;
    margin-bottom: 12px;
    filter: drop-shadow(0 0 18px #00f0ff88) drop-shadow(0 0 12px #ff00ff77);
    display: block;
}

/* BOTÓN grande y responsivo */
.intro-btn {
    width: 100%;
    max-width: 340px;
    min-width: 120px;
    margin: 0 auto 16px auto;
    padding: 1em 0;
    font-size: clamp(1.1rem, 3vw, 1.45rem);
    background: #000;
    border: 2.5px solid #00f0ff;
    color: white;
    font-weight: bold;
    border-radius: 34px;
    box-shadow: 0 0 28px #00f0ff99;
    cursor: pointer;
    text-shadow: 0 0 12px #00f0ff;
    letter-spacing: 1px;
    transition: all 0.3s cubic-bezier(.4,0,.2,1);
    display: block;
}
.intro-btn:hover {
    background: #001f2f;
    color: #00f0ff;
    transform: scale(1.04);
    box-shadow: 0 0 44px #00f0ff;
}

.intro-quees-title {
    margin-bottom: 2px;
    font-size: clamp(1rem, 2vw, 1.18rem);
    color: #00f0ff;
    text-shadow: 0 0 8px #00f0ff;
    margin-top: 0;
}
.intro-quees-desc {
    width: 100%;
    max-width: 280px;
    height: 24px;
    border: none;
    background: none;
    border-bottom: 2px dotted #ff00ff99;
    margin-bottom: 10px;
    color: #b7c7ffbb;
    font-size: clamp(0.95rem, 2vw, 1.05rem);
    outline: none;
    pointer-events: none;
}

.intro-equipo-title {
    font-size: clamp(1.02rem, 2vw, 1.13rem);
    color: #fff;
    margin-bottom: 7px;
    font-weight: bold;
    text-shadow: 0 0 7px #00f0ff;
    letter-spacing: 1px;
}
.intro-equipo {
    display: flex;
    gap: 4vw;
    margin-bottom: 0;
}
.intro-avatar {
    width: clamp(30px, 8vw, 60px);
    height: clamp(30px, 8vw, 60px);
    border-radius: 50%;
    background: linear-gradient(145deg, #1e0047 40%, #00f0ff 100%);
    border: 3px solid #ff00ff88;
    box-shadow: 0 0 11px #00f0ff66;
}

/* BIENVENIDA (card igual de responsivo) */
.content-container {
    background: rgba(5, 5, 20, 0.85);
    border-radius: 15px;
    padding: 40px 32px;
    box-shadow:
        0 0 21px rgba(0, 240, 255, 0.35),
        0 0 42px rgba(0, 240, 255, 0.25),
        0 0 63px rgba(0, 240, 255, 0.15);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    max-width: 500px;
    width: 92vw;
    box-sizing: border-box;
    opacity: 0;
    transform: translateY(30px) scale(0.98);
    pointer-events: none;
    transition: opacity 0.6s, transform 0.7s;
    margin: 0 auto;
    position: static;
}
.content-container.visible {
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: auto;
}

h1.welcome-title {
    font-size: clamp(1.1rem, 5vw, 2rem);
    color: #00f0ff;
    text-shadow: 0 0 8px #00f0ff;
    margin-bottom: 20px;
    text-align: center;
}

img.logo {
    width: 100%;
    max-width: 220px;
    height: auto;
    margin-bottom: 40px;
    filter: drop-shadow(0 0 10px #ff00ff);
    display: block;
    margin-left: auto;
    margin-right: auto;
}

.btn-group {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
}

.btn-glow {
    background-color: #000;
    border: 2px solid #00f0ff;
    color: white;
    padding: 1em 0;
    text-align: center;
    text-decoration: none;
    font-weight: bold;
    border-radius: 6px;
    box-shadow: 0 0 10px #00f0ff;
    transition: all 0.3s ease;
    box-sizing: border-box;
    font-size: clamp(1.02rem, 3vw, 1.22rem);
    width: 100%;
    max-width: 340px;
    margin: 0 auto;
    display: block;
}
.btn-glow:hover {
    background-color: #001f2f;
    transform: scale(1.05);
    box-shadow: 0 0 20px #00f0ff;
}

/* MOBILE */
@media (max-width: 600px) {
  .intro-card, .content-container {
    padding: 12px 4vw;
    max-width: 98vw;
  }
  .intro-img-logo {
    max-width: 92vw;
  }
  .intro-btn, .btn-glow {
    font-size: 1rem;
    padding: 0.7em 0;
    max-width: 98vw;
  }
}
@media (max-width: 400px) {
  .intro-card, .content-container {
    padding: 7px 1vw;
  }
  .intro-btn, .btn-glow {
    font-size: 0.93rem;
    padding: 0.5em 0;
  }
}
    </style>
</head>
<body>

<!-- Pantalla introductoria -->
<div id="intro" class="intro-container fade-slide-in">
  <div class="intro-card fade-slide-in" id="introCard">
    <img src="{{ asset('images/logo.png') }}" alt="Logo Cuanto Sabe" class="intro-img-logo" />
    <button class="intro-btn" id="entrarBtn">Entrar</button>
    <div class="intro-quees-title">¿Qué es?</div>
    <div class="intro-quees-desc"></div>
    <div class="intro-equipo-title">El equipo</div>
    <div class="intro-equipo">
      <div class="intro-avatar"></div>
      <div class="intro-avatar"></div>
      <div class="intro-avatar"></div>
    </div>
  </div>
</div>

<!-- Vista de bienvenida original -->
<div class="content-container" id="bienvenida">
    <h1 class="welcome-title">Bienvenido a</h1>
    <img src="{{ asset('images/logo.png') }}" alt="Logo Cuanto Sabe" class="logo" />
    <div class="btn-group">
        <a href="{{ route('participants.form') }}" class="btn-glow">Iniciar sesión</a>
        <a href="{{ route('guest.dashboard') }}" class="btn-glow">Entrar como invitado</a>
    </div>
</div>

<script>
    const intro = document.getElementById('intro');
    const introCard = document.getElementById('introCard');
    const bienvenida = document.getElementById('bienvenida');
    const entrarBtn = document.getElementById('entrarBtn');

    entrarBtn.addEventListener('click', () => {
        // Animar salida de la tarjeta, no del fondo overlay
        introCard.classList.remove('fade-slide-in');
        introCard.classList.add('fade-slide-out');
        // Al terminar la animación, ocultar intro-container y mostrar bienvenida
        setTimeout(() => {
            intro.style.display = 'none';
            bienvenida.classList.add('visible');
        }, 700); // Debe coincidir con los tiempos de animación
    });

    // Por defecto, bienvenida está oculta
    window.onload = () => {
        bienvenida.classList.remove('visible');
    };
</script>
</body>
</html>
