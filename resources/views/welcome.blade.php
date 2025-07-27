<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuanto Sabe - Bienvenido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            width: 100vw;
            box-sizing: border-box;
            overflow-x: hidden;
        }
        body {
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at center, #210054 60%, #030015 100%);
            color: #fff;
            position: relative;
        }
        /* --- FONDO DE PARTÍCULAS NEÓN --- */
        .neon-bg-particles {
            pointer-events: none;
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            z-index: 1;
            overflow: hidden;
        }
        .neon-particle {
            position: absolute;
            border-radius: 50%;
            filter: blur(10px);
            opacity: 0.55;
            animation: float 7s infinite ease-in-out alternate;
        }
        .neon-particle:nth-child(1) { width: 160px; height: 160px; background: #00f0ffb0; top: 10%; left: 13%; animation-duration: 9s;}
        .neon-particle:nth-child(2) { width: 110px; height: 110px; background: #ff00ff99; top: 70%; left: 80%; animation-delay: 2s; animation-duration: 11s;}
        .neon-particle:nth-child(3) { width: 80px; height: 80px; background: #00ffd1bb; top: 66%; left: 27%; animation-delay: 4s;}
        .neon-particle:nth-child(4) { width: 60px; height: 60px; background: #00eaffbb; top: 18%; left: 74%; animation-delay: 1.7s;}
        .neon-particle:nth-child(5) { width: 120px; height: 120px; background: #14ffb080; top: 82%; left: 45%; animation-delay: 3.5s; animation-duration: 13s;}
        @keyframes float { to { transform: translateY(-60px) scale(1.1) rotate(16deg); opacity: 0.75; }}

        /* --- INTRO CONTAINER --- */
        .intro-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            z-index: 2;
            background: linear-gradient(135deg, #1e0047cc 72%, #0c0125f9 100%);
        }
        .intro-card {
            padding: 38px 28px 30px 28px;
            max-width: 430px;
            width: 93vw;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 26px;
            background: rgba(15,6,43,0.82);
            border-radius: 24px;
            box-shadow: 0 0 38px #00f0ff70, 0 0 78px #8504ec22, 0 8px 42px #00f0ff33;
            border: 2.4px solid #00f0ff44;
            position: relative;
            overflow: hidden;
        }
        .intro-card::before {
            content: "";
            position: absolute;
            top: 70px; left: 18%;
            width: 67%;
            height: 33%;
            background: radial-gradient(circle, #00f0ff33 0%, #fff0 100%);
            filter: blur(23px);
            z-index: 0;
            opacity: 0.43;
            pointer-events: none;
        }
        .intro-img-logo {
            width: 100%;
            max-width: 210px;
            height: auto;
            margin-bottom: 10px;
            filter: drop-shadow(0 0 22px #00f0ff) drop-shadow(0 0 16px #ff00ff99);
            animation: floatLogo 3.4s ease-in-out infinite alternate;
            z-index: 2;
            position: relative;
        }
        @keyframes floatLogo {
            from { transform: translateY(0) scale(1) rotate(-4deg);}
            to { transform: translateY(-12px) scale(1.04) rotate(8deg);}
        }
        .intro-btn {
            width: 100%;
            max-width: 330px;
            min-width: 120px;
            margin: 0 auto 10px auto;
            padding: 1em 0;
            font-size: clamp(1.11rem, 3vw, 1.55rem);
            background: #000;
            border: 2.5px solid #00f0ff;
            color: #00f0ff;
            font-weight: bold;
            border-radius: 34px;
            box-shadow: 0 0 38px #00f0ff99, 0 0 11px #ff00ff77;
            cursor: pointer;
            text-shadow: 0 0 13px #00f0ffb6;
            letter-spacing: 1px;
            transition: all 0.28s cubic-bezier(.4,0,.2,1);
            display: block;
            position: relative;
            z-index: 1;
            animation: pulseBtn 1.2s infinite alternate cubic-bezier(.4,0,.2,1);
        }
        @keyframes pulseBtn {
            to { box-shadow: 0 0 70px #00f0ffcc, 0 0 30px #ff00ffbb; }
        }
        .intro-btn:hover {
            background: #00f0ff;
            color: #00122c;
            transform: scale(1.048);
            box-shadow: 0 0 52px #00f0ffcc, 0 0 22px #ff00ffbb;
            border-color: #fff;
        }
        .intro-quees-title {
            margin-bottom: 0; font-size: clamp(1.07rem, 2vw, 1.19rem);
            color: #fff; text-shadow: 0 0 13px #00f0ff; margin-top: 0; z-index: 1;
        }
        .intro-quees-desc {
            width: 100%; max-width: 340px; min-height: 25px; border: none; background: none;
            border-bottom: 2px dotted #ff00ff80; margin-bottom: 12px; color: #b7c7ffcc;
            font-size: clamp(1rem, 2vw, 1.08rem); outline: none; pointer-events: none; text-align: center;
        }
        .intro-equipo-title {
            font-size: clamp(1.06rem, 2vw, 1.15rem); color: #fff; margin-bottom: 7px; font-weight: bold;
            text-shadow: 0 0 12px #00f0ffb1; letter-spacing: 1px; z-index: 1;
        }
        .intro-equipo {
            display: flex; gap: 20px; margin-bottom: 0; z-index: 2;
        }
        .intro-avatar {
            width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(145deg, #10022d 40%, #00f0ff 120%);
            border: 2.2px solid #ff00ff88; box-shadow: 0 0 17px #00f0ff96, 0 0 9px #fff0; position: relative; overflow: hidden;
        }
        .intro-avatar::after {
            content: '';
            position: absolute;
            width: 42%;
            height: 20%;
            left: 24%;
            top: 14%;
            background: linear-gradient(93deg, #fff6, #fff0 80%);
            opacity: 0.35;
            border-radius: 44%;
            pointer-events: none;
        }
        .intro-avatar:nth-child(1) { background-image: url('/images/ava1.png'); background-size: cover;}
        .intro-avatar:nth-child(2) { background-image: url('/images/ava2.png'); background-size: cover;}
        .intro-avatar:nth-child(3) { background-image: url('/images/ava3.png'); background-size: cover;}

        /* --- EFECTO EXPANSIÓN ACUOSA --- */
        .intro-card.aqua-fade {
            animation: aquaFadeCard 1.08s cubic-bezier(.42,.13,.54,1.04) forwards;
        }
        @keyframes aquaFadeCard {
            0%   { opacity: 1; filter: none; }
            40%  { filter: blur(1.5px);}
            70%  { opacity: 0.7; filter: blur(7px);}
            85%  { opacity: 0.3; filter: blur(16px);}
            100% { opacity: 0; filter: blur(36px);}
        }
        .ripple-aqua {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle, rgba(200,220,255,0.24) 16%, rgba(180,220,255,0.09) 56%, transparent 90%);
            mix-blend-mode: lighten;
            opacity: 0.55;
            z-index: 2;
            animation: rippleAquaExpand 1s cubic-bezier(.28,0,.79,1.05) forwards;
        }
        @keyframes rippleAquaExpand {
            0% { transform: scale(0.1); opacity: 0.82;}
            60% { opacity: 0.26;}
            100% { transform: scale(8); opacity: 0;}
        }

        /* --- SEGUNDO CUADRO: CONTENT-CONTAINER --- */
        .content-container {
            background: rgba(10, 10, 32, 0.95);
            border-radius: 30px;
            padding: 54px 32px 36px 32px;
            box-shadow:
                0 0 38px #00f0ff60,
                0 0 92px #ff00ff19,
                0 0 120px #00f0ff23;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            max-width: 410px;
            width: 94vw;
            min-width: 220px;
            min-height: 340px;
            box-sizing: border-box;
            opacity: 0;
            pointer-events: none;
            position: fixed;
            top: 50%;
            left: 50%;
            z-index: 2;
            transform: translate(-50%, -50%) scale(1);
            filter: none;
            transition: none;
        }
        .content-container.visible {
            animation: appearSoft 1.08s cubic-bezier(.56,.14,.39,.97) forwards;
            pointer-events: auto; /* <-- CORRECCIÓN: ahora los botones funcionan */
        }
        @keyframes appearSoft {
            0%   { opacity: 0; filter: blur(20px);}
            70%  { opacity: 0.7; filter: blur(4px);}
            95%  { opacity: 1; filter: blur(1px);}
            100% { opacity: 1; filter: none;}
        }
        /* --- LOGO DEL SEGUNDO CUADRO --- */
        img.logo {
            width: 100%;
            max-width: 180px;
            min-width: 90px;
            height: auto;
            margin-bottom: 26px;
            filter: drop-shadow(0 0 16px #ff00ff99) drop-shadow(0 0 8px #00f0ff88);
            display: block;
            margin-left: auto;
            margin-right: auto;
            animation: floatLogo 3.8s ease-in-out infinite alternate;
            transition: max-width 0.3s;
        }
        .welcome-title {
            font-size: clamp(1.17rem, 4vw, 2.2rem);
            color: #00f0ff;
            text-shadow: 0 0 11px #00f0ffcc;
            margin-bottom: 14px;
            text-align: center;
            letter-spacing: 1px;
            font-weight: 700;
            animation: fadeInTitle 1.1s cubic-bezier(.5,0,.2,1);
        }
        @keyframes fadeInTitle {
            from { opacity:0; transform: translateY(-16px) scale(1.04);}
            to { opacity:1; transform: none; }
        }
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 19px;
            width: 100%;
            margin-top: 4px;
        }
        .btn-glow {
            background-color: #000;
            border: 2.5px solid #00f0ff;
            color: #00f0ff;
            padding: 1em 0;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            border-radius: 19px;
            box-shadow: 0 0 18px #00f0ff88, 0 0 9px #ff00ff44;
            transition: all 0.25s;
            font-size: clamp(1.06rem, 3vw, 1.23rem);
            width: 100%;
            max-width: 320px;
            margin: 0 auto;
            display: block;
            outline: none;
            animation: pulseBtn 1.4s infinite alternate cubic-bezier(.4,0,.2,1);
        }
        .btn-glow:hover {
            background: #00f0ff;
            color: #00112c;
            transform: scale(1.048);
            box-shadow: 0 0 38px #00f0ffcc, 0 0 16px #ff00ff99;
            border-color: #fff;
            text-shadow: 0 0 9px #00f0ffcc;
        }
        /* --- RESPONSIVE TWEAKS --- */
        @media (max-width: 600px) {
            .intro-card { padding: 15px 4vw; }
            .intro-img-logo { max-width: 75vw; }
            .content-container { padding: 14px 2vw 18px 2vw; min-height: 220px; }
            img.logo { max-width: 66vw; }
        }
        @media (max-width: 400px) {
            .intro-card { padding: 7px 1vw; }
            .intro-btn { font-size: 0.93rem; padding: 0.5em 0; }
            .intro-avatar { width: 38px; height: 38px;}
            .content-container { padding: 7px 1vw; min-width: 0; }
            img.logo { max-width: 84vw; }
        }
    </style>
</head>
<body>
    <!-- FONDO DE PARTÍCULAS -->
    <div class="neon-bg-particles">
        <div class="neon-particle"></div>
        <div class="neon-particle"></div>
        <div class="neon-particle"></div>
        <div class="neon-particle"></div>
        <div class="neon-particle"></div>
    </div>

    <!-- Pantalla introductoria -->
    <div id="intro" class="intro-container fade-slide-in">
      <div class="intro-card fade-slide-in" id="introCard">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Cuanto Sabe" class="intro-img-logo" />
        <button class="intro-btn" id="entrarBtn">Entrar</button>
        <div class="intro-quees-title">¿Qué es?</div>
        <div class="intro-quees-desc">
            Un juego donde solo los que más saben llegan al podio.<br>
            ¡Probá tus conocimientos en vivo!
        </div>
        <div class="intro-equipo-title">El equipo</div>
        <div class="intro-equipo">
          <div class="intro-avatar"></div>
          <div class="intro-avatar"></div>
          <div class="intro-avatar"></div>
        </div>
      </div>
    </div>

    <!-- Vista de bienvenida -->
    <div class="content-container" id="bienvenida">
        <h1 class="welcome-title">Bienvenido a</h1>
        <img src="{{ asset('images/logo.png') }}" alt="Logo Cuanto Sabe" class="logo" />
        <div class="btn-group">
            <a href="{{ route('participants.form', ['redirect' => 'guest-dashboard']) }}" class="btn-glow">Iniciar sesión</a>
            <a href="{{ route('guest-dashboard') }}" class="btn-glow">Entrar como invitado</a>
        </div>
    </div>
    <script>
        const intro = document.getElementById('intro');
        const introCard = document.getElementById('introCard');
        const bienvenida = document.getElementById('bienvenida');
        const entrarBtn = document.getElementById('entrarBtn');

        entrarBtn.addEventListener('click', (e) => {
            // Coordenadas click relativas al card
            const rect = introCard.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            // Ripple circular
            const ripple = document.createElement('div');
            ripple.className = 'ripple-aqua';
            ripple.style.left = `${x - 200}px`;
            ripple.style.top = `${y - 200}px`;
            ripple.style.width = ripple.style.height = `400px`;
            introCard.appendChild(ripple);

            // Fade-out borroso en todo el card
            introCard.classList.remove('fade-slide-in');
            introCard.classList.add('aqua-fade');

            setTimeout(() => {
                intro.style.display = 'none';
                bienvenida.classList.add('visible');
                ripple.remove();
                introCard.classList.remove('aqua-fade');
            }, 1100);
        });

        window.onload = () => {
            bienvenida.classList.remove('visible');
        };
    </script>
</body>
</html>
