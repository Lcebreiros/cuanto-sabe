<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuanto Sabe - Invitado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at center, #1e0047 60%, #0c0125 100%);
            color: #fff;
            min-height: 100vh;
            padding: 0;
            box-sizing: border-box;
            width: 100vw;
            height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Fondo partículas glow */
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
            opacity: 0.47;
            animation: float 7s infinite ease-in-out alternate;
        }
        .neon-particle:nth-child(1) { width: 170px; height: 170px; background: #00f0ffb0; top: 12%; left: 9%; animation-duration: 9s;}
        .neon-particle:nth-child(2) { width: 110px; height: 110px; background: #ff00ff99; top: 70%; left: 82%; animation-delay: 2s; animation-duration: 11s;}
        .neon-particle:nth-child(3) { width: 88px; height: 88px; background: #00ffd1bb; top: 60%; left: 25%; animation-delay: 3.7s;}
        .neon-particle:nth-child(4) { width: 60px; height: 60px; background: #00eaffbb; top: 18%; left: 75%; animation-delay: 1.3s;}
        .neon-particle:nth-child(5) { width: 140px; height: 140px; background: #14ffb080; top: 84%; left: 44%; animation-delay: 3.5s; animation-duration: 13s;}
        @keyframes float { to { transform: translateY(-54px) scale(1.11) rotate(16deg); opacity: 0.77; }}

        .content-container {
            background: rgba(9, 10, 37, 0.91);
            border-radius: 22px;
            padding: 40px 45px 30px 45px;
            box-shadow:
                0 0 42px #00f0ff4f,
                0 0 98px #ff00ff22;
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 540px;
            width: 96vw;
            margin: 70px auto 0 auto;
            position: relative;
            z-index: 2;
            border: 2.2px solid #00f0ff55;
            animation: fadeInUp .9s cubic-bezier(.3,0,.2,1);
        }
        @keyframes fadeInUp { from { opacity:0; transform: translateY(32px);} to { opacity:1; transform: none;} }

        img.logo {
            width: 200px;
            height: auto;
            margin-bottom: 12px;
            filter: drop-shadow(0 0 14px #ff00ff) drop-shadow(0 0 12px #00f0ff99);
            display: block;
        }

        h3.center-title {
            font-size: 1.65rem;
            color: #00f0ff;
            text-shadow: 0 0 13px #00f0ffcc;
            margin-bottom: 2.1rem;
            margin-top: 0.4rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 1.5px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 21px;
            width: 100%;
            margin-bottom: 28px;
        }

        .btn-glow {
            background-color: #000;
            border: 2px solid #00f0ff;
            color: #00f0ff;
            padding: 30px 0;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            border-radius: 13px;
            box-shadow: 0 0 16px #00f0ff66, 0 0 10px #ff00ff36;
            transition: all 0.25s cubic-bezier(.45,0,.4,1);
            user-select: none;
            height: 108px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.15rem;
            box-sizing: border-box;
            outline: none;
            position: relative;
            letter-spacing: 1px;
        }
        .btn-glow:hover {
            background: #00f0ff;
            color: #001f2f;
            transform: scale(1.049);
            box-shadow: 0 0 30px #00f0ffcc, 0 0 22px #ff00ff99;
            border-color: #fff;
            text-shadow: 0 0 7px #00f0ffcc;
        }
        .btn-logo img {
            height: 37px;
            margin-left: 12px;
            filter: drop-shadow(0 0 10px #ff00ff);
            max-width: none;
        }
        /* Botón "Jugar" ocupa dos columnas pero con mismo estilo */
        .doble-columna {
            grid-column: 1 / span 2;
        }
        @media (max-width: 580px) {
            .doble-columna { grid-column: auto; }
        }
        @media (max-width: 700px) {
            .content-container {
                padding: 23px 5vw;
                max-width: 99vw;
            }
            .grid { grid-template-columns: 1fr; gap: 13px; }
            .doble-columna { grid-column: auto; }
            img.logo { width: 150px; }
        }
        @media (max-width: 400px) {
            .content-container { padding: 9px 2vw; }
            img.logo { width: 110px; }
            h3.center-title { font-size: 1.17rem; }
            .btn-glow { padding: 17px 0; font-size: 0.99rem; height: 70px;}
            .btn-logo img { height: 22px; }
        }

        .participar-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 96%;
            max-width: 440px;
            min-width: 130px;
            margin: 32px auto 10px auto;
            padding: 20px 0;
            border-radius: 13px;
            border: 2.5px solid #05ff9e;
            background: linear-gradient(97deg,rgb(7, 155, 44) 75%,rgb(7, 155, 44) 100%);
            color: #122d22;
            font-size: 1.31rem;
            font-family: 'Orbitron', sans-serif;
            font-weight: bold;
            box-shadow: 0 0 14px #00ffb4b2;
            letter-spacing: 2px;
            text-align: center;
            text-decoration: none;
            transition: background 0.18s, color 0.15s, border-color 0.18s, box-shadow 0.18s;
            user-select: none;
            outline: none;
        }
        .participar-btn:hover,
        .participar-btn:focus {
            background: linear-gradient(97deg, #2affb3 80%, #00fff2 100%);
            color: #00361e;
            border-color: #12ffcb;
            box-shadow: 0 0 28px #05ff9e99, 0 0 4px #fff2;
        }
        .participar-btn.disabled,
        .participar-btn[disabled] {
            background: #26314a !important;
            color: #aaa !important;
            border-color: #333 !important;
            box-shadow: none !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

@php
    use App\Models\ParticipantSession;
    use App\Models\GameSession;

    $participantId = session('participant_session_id');
    $participant = $participantId ? ParticipantSession::find($participantId) : null;
    $activeSession = GameSession::where('status', 'active')->latest()->first();
@endphp

<div class="content-container">
    <img src="/images/logo.png" alt="Logo Cuanto Sabe" class="logo" />

    @if($participant)
        <h3 class="center-title">¡Hola, {{ $participant->username }}!</h3>
        <div style="color:#19ff8c; font-size:1.02rem; text-align:center; margin-bottom:19px;">
            Ya estás registrado en la sesión actual.
        </div>
    @else
        <h3 class="center-title">¡Modo invitado! Elegí qué querés hacer:</h3>
    @endif

        <div class="grid">
            <a href="#" class="btn-glow btn-logo">
                <span>Conocé</span>
                <img src="/images/logo.png" alt="Logo">
            </a>
            <a href="#" class="btn-glow">Repeticiones</a>
            <a href="#" class="btn-glow doble-columna">Jugar demo</a>
        </div>
        <div style="width:100%; display:flex; justify-content:center;">
            @if($activeSession)
                <a href="{{ route('participar') }}"
                   class="participar-btn"
                   style="pointer-events:auto;">
                    Participar
                </a>
            @else
                <a href="#"
                   class="participar-btn disabled"
                   style="pointer-events:none;">
                    Participar
                </a>
            @endif
        </div>
@if($participant)
    <form id="salirForm" action="{{ route('salir.juego') }}" method="POST" style="margin-top:16px; width:100%; text-align:center;">
        @csrf
        <button type="button"
            onclick="abrirModalSalir()"
            class="participar-btn"
            style="background:#350d18; color:#ff6060; border-color:#ff4444; margin-top:10px;">
            Salir del juego
        </button>
    </form>
@endif


    </div>
<div id="modalSalir" style="display:none; position:fixed; z-index:99; top:0; left:0; width:100vw; height:100vh; background:rgba(12,0,36,0.72); backdrop-filter: blur(2px); align-items:center; justify-content:center;">
    <div style="background:rgba(17,11,42,0.95); border-radius:18px; max-width:340px; margin:auto; padding:32px 24px 24px 24px; box-shadow:0 0 30px #00f0ff55,0 0 50px #ff00ff22; border:2px solid #ff444499; text-align:center; position:relative; display:flex; flex-direction:column; align-items:center;">
        <div style="font-size:2.3rem; color:#ffe27a; margin-bottom:18px; filter:drop-shadow(0 0 6px #ffe27aaa);">
            <span style="font-size:2.5rem; vertical-align:middle;">&#9888;</span>
        </div>
        <div style="color:#ffd966; font-size:1.11rem; font-weight:bold; margin-bottom:18px;">
            ¿Seguro que deseas salir del juego?<br>
            <span style="color:#ff6060;">Se perderá la sesión y el puntaje alcanzado.</span>
        </div>
        <div style="display:flex; gap:18px; justify-content:center;">
            <button onclick="cerrarModalSalir()"
                style="padding:11px 25px; border-radius:1.4em; border:none; background:#222a37; color:#19ff8c; font-weight:bold; font-size:1.06rem; box-shadow:0 0 8px #00f0ff99; cursor:pointer; transition:background .17s;">
                Cancelar
            </button>
            <button onclick="confirmarSalidaFinal()"
                style="padding:11px 25px; border-radius:1.4em; border:none; background:#ff4444; color:#fff; font-weight:bold; font-size:1.06rem; box-shadow:0 0 8px #ff444488; cursor:pointer; transition:background .17s;">
                Sí, salir
            </button>
        </div>
    </div>
</div>
<script>
function abrirModalSalir() {
    document.getElementById('modalSalir').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function cerrarModalSalir() {
    document.getElementById('modalSalir').style.display = 'none';
    document.body.style.overflow = '';
}
function confirmarSalidaFinal() {
    cerrarModalSalir();
    document.getElementById('salirForm').submit();
}
// Cierra el modal si clickeás fuera del cuadro:
document.addEventListener('mousedown', function(e){
    const modal = document.getElementById('modalSalir');
    const dialog = modal?.querySelector('div[style*="background"]');
    if(modal && modal.style.display === 'flex' && !dialog.contains(e.target)){
        cerrarModalSalir();
    }
});
</script>

</body>
</html>
