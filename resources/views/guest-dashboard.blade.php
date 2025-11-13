<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuanto Sabe - Invitado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600&family=Montserrat:wght@300;400&display=swap" rel="stylesheet">
    <style>
        /* --- VARIABLES Y RESET --- */
        :root {
            --primary-color: #00f0ff;
            --secondary-color: #ff00ff;
            --success-color: #05ff9e;
            --error-color: #ff4444;
            --dark-bg: #0c0125;
            --card-bg: rgba(9, 10, 37, 0.92);
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
            min-height: 100dvh;
            width: 100vw;
            overflow-x: hidden;
            font-family: 'Montserrat', sans-serif;
            background: radial-gradient(circle at center, #1e0047 60%, var(--dark-bg) 100%);
            color: var(--text-light);
            position: relative;
        }
        
        @supports not (height: 100dvh) {
            html, body { height: 100vh; }
        }
        
        /* --- FONDO DE PARTÍCULAS --- */
        .particles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100dvh;
            z-index: 1;
            pointer-events: none;
            overflow: hidden;
        }
        
        @supports not (height: 100dvh) {
            .particles-container { height: 100vh; }
        }
        
        .particle {
            position: absolute;
            border-radius: 50%;
            filter: blur(12px);
            opacity: 0.4;
            animation: float 8s infinite ease-in-out alternate;
        }
        
        .particle:nth-child(1) {
            width: 170px; height: 170px;
            background: var(--primary-color);
            top: 12%; left: 9%;
            animation-duration: 9s;
        }
        
        .particle:nth-child(2) {
            width: 110px; height: 110px;
            background: var(--secondary-color);
            top: 70%; left: 82%;
            animation-delay: 2s;
            animation-duration: 11s;
        }
        
        .particle:nth-child(3) {
            width: 88px; height: 88px;
            background: #00ffd1;
            top: 60%; left: 25%;
            animation-delay: 3.7s;
        }
        
        @keyframes float {
            to { 
                transform: translateY(-54px) scale(1.11) rotate(16deg); 
                opacity: 0.6; 
            }
        }
        
        /* --- CONTENEDOR PRINCIPAL --- */
        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100dvh;
            padding: clamp(10px, 5dvh, 70px) 0 clamp(8px, 3dvh, 28px);
            position: relative;
            z-index: 2;
        }
        
        /* --- TARJETA DE CONTENIDO --- */
        .dashboard-card {
            width: min(96vw, 560px);
            background: var(--card-bg);
            border-radius: 22px;
            padding: clamp(16px, 4dvh, 40px) clamp(12px, 4vw, 45px) clamp(14px, 3dvh, 30px);
            box-shadow: 0 0 42px rgba(0, 240, 255, 0.3),
                        0 0 98px rgba(255, 0, 255, 0.15);
            border: 1px solid rgba(0, 240, 255, 0.3);
            backdrop-filter: blur(8px);
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: fadeInUp 0.9s cubic-bezier(0.3, 0, 0.2, 1);
            max-height: calc(100dvh - clamp(10px, 5dvh, 70px) - clamp(8px, 3dvh, 28px));
            overflow-y: auto;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(32px); }
            to { opacity: 1; transform: none; }
        }
        
        /* --- LOGO --- */
        .logo {
            width: clamp(110px, 18dvh, 200px);
            height: auto;
            margin-bottom: clamp(6px, 1.8dvh, 12px);
            filter: drop-shadow(0 0 14px var(--secondary-color)) 
                    drop-shadow(0 0 12px rgba(0, 240, 255, 0.6));
        }
        
        /* --- TÍTULOS --- */
        .title {
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(1.05rem, 2.6dvh, 1.65rem);
            color: var(--primary-color);
            text-shadow: 0 0 13px rgba(0, 240, 255, 0.8);
            margin-bottom: clamp(12px, 3dvh, 2.1rem);
            margin-top: clamp(2px, 1dvh, 0.4rem);
            font-weight: 600;
            text-align: center;
            letter-spacing: 1.5px;
        }
        
        .subtitle {
            color: #19ff8c;
            font-size: clamp(0.95rem, 2.2dvh, 1.02rem);
            text-align: center;
            margin-bottom: clamp(12px, 2.5dvh, 19px);
        }
        
        /* --- GRID DE BOTONES --- */
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: clamp(10px, 2dvh, 21px);
            width: 100%;
            margin-bottom: clamp(12px, 3dvh, 28px);
        }
        
        @media (max-width: 700px) {
            .actions-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* --- BOTONES --- */
        .action-btn {
            background-color: rgba(0, 0, 0, 0.4);
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: clamp(12px, 2.4dvh, 30px) 0;
            text-align: center;
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            font-weight: 600;
            border-radius: 13px;
            box-shadow: 0 0 16px rgba(0, 240, 255, 0.4),
                        0 0 10px rgba(255, 0, 255, 0.2);
            transition: var(--transition);
            user-select: none;
            height: clamp(60px, 12dvh, 108px);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: clamp(0.95rem, 2.2dvh, 1.15rem);
            position: relative;
            overflow: hidden;
            letter-spacing: 1px;
        }
        
        .action-btn:hover {
            background: var(--primary-color);
            color: #001f2f;
            transform: scale(1.03);
            box-shadow: 0 0 30px rgba(0, 240, 255, 0.8),
                        0 0 22px rgba(255, 0, 255, 0.6);
            border-color: #fff;
        }
        
        .action-btn .btn-icon {
            height: clamp(22px, 4dvh, 37px);
            margin-left: 12px;
            filter: drop-shadow(0 0 10px var(--secondary-color));
        }
        
        .double-column {
            grid-column: 1 / span 2;
        }
        
        @media (max-width: 700px) {
            .double-column {
                grid-column: auto;
            }
        }
        
        /* --- BOTÓN PRINCIPAL --- */
        .main-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: min(96%, 440px);
            padding: clamp(12px, 2.6dvh, 20px) 0;
            margin: clamp(12px, 3dvh, 32px) auto clamp(6px, 1.8dvh, 10px) auto;
            border-radius: 13px;
            border: 2px solid var(--success-color);
            background: linear-gradient(97deg, #079b2c 75%, #079b2c 100%);
            color: #122d22;
            font-family: 'Orbitron', sans-serif;
            font-size: clamp(1rem, 2.4dvh, 1.31rem);
            font-weight: 600;
            box-shadow: 0 0 14px rgba(0, 255, 180, 0.7);
            letter-spacing: 2px;
            text-align: center;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .main-btn:hover {
            background: linear-gradient(97deg, #2affb3 80%, #00fff2 100%);
            color: #00361e;
            border-color: #12ffcb;
            box-shadow: 0 0 28px rgba(5, 255, 158, 0.6),
                        0 0 4px rgba(255, 255, 255, 0.1);
        }
        
        .main-btn.disabled {
            background: #26314a !important;
            color: #aaa !important;
            border-color: #333 !important;
            box-shadow: none !important;
            cursor: not-allowed;
            pointer-events: none;
        }
        
        /* --- BOTÓN DE SALIR --- */
        .exit-btn {
            background: #350d18;
            color: #ff6060;
            border-color: var(--error-color);
            margin-top: 10px;
        }
        
        /* --- MODAL --- */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100dvh;
            background: rgba(12, 0, 36, 0.72);
            backdrop-filter: blur(2px);
            align-items: center;
            justify-content: center;
            z-index: 99;
        }
        
        .modal-content {
            background: rgba(17, 11, 42, 0.95);
            border-radius: 18px;
            max-width: 340px;
            padding: 32px 24px 24px;
            box-shadow: 0 0 30px rgba(0, 240, 255, 0.3),
                        0 0 50px rgba(255, 0, 255, 0.15);
            border: 2px solid rgba(255, 68, 68, 0.6);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .modal-icon {
            font-size: 2.5rem;
            color: #ffe27a;
            margin-bottom: 18px;
            filter: drop-shadow(0 0 6px rgba(255, 226, 122, 0.7));
        }
        
        .modal-text {
            color: #ffd966;
            font-size: 1.11rem;
            font-weight: 600;
            margin-bottom: 18px;
        }
        
        .modal-text span {
            color: #ff6060;
        }
        
        .modal-actions {
            display: flex;
            gap: 18px;
            justify-content: center;
        }
        
        .modal-btn {
            padding: 11px 25px;
            border-radius: 1.4em;
            border: none;
            font-weight: 600;
            font-size: 1.06rem;
            cursor: pointer;
            transition: background 0.17s;
        }
        
        .modal-btn-cancel {
            background: #222a37;
            color: #19ff8c;
            box-shadow: 0 0 8px rgba(0, 240, 255, 0.6);
        }
        
        .modal-btn-confirm {
            background: var(--error-color);
            color: #fff;
            box-shadow: 0 0 8px rgba(255, 68, 68, 0.5);
        }
        
        /* --- AJUSTES PARA PANTALLAS PEQUEÑAS --- */
        @media (max-height: 700px) {
            .dashboard-card {
                padding: clamp(12px, 3dvh, 28px) clamp(10px, 4vw, 28px) clamp(12px, 2.6dvh, 24px);
            }
            
            .action-btn {
                height: clamp(56px, 11dvh, 96px);
            }
        }
        
        @media (max-height: 560px) {
            .logo {
                width: clamp(95px, 16dvh, 150px);
            }
            
            .action-btn {
                height: clamp(52px, 10dvh, 88px);
            }
            
            .main-btn {
                padding: clamp(10px, 2.2dvh, 18px) 0;
            }
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <!-- Fondo de partículas -->
    <div class="particles-container">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    @php
        use App\Models\ParticipantSession;
        use App\Models\GameSession;

        $participantId = session('participant_session_id');
        $participant = $participantId ? ParticipantSession::find($participantId) : null;
        $activeSession = GameSession::where('status', 'active')->latest()->first();
    @endphp

    <!-- Contenido principal -->
    <div class="main-container">
        <div class="dashboard-card">
            <img src="/public/images/logo.png" alt="Logo Cuanto Sabe" class="logo" />

            @if($participant)
                <h1 class="title">¡Hola, {{ $participant->username }}!</h1>
                <p class="subtitle">Ya estás registrado en la sesión actual.</p>
            @else
                <h1 class="title">¡Modo invitado!</h1>
                <p class="subtitle">Elegí qué querés hacer:</p>
            @endif

            <div class="actions-grid">
                <a href="{{ route('about-us') }}" class="action-btn">
                    <span>Conocé</span>
                    <img src="/public/images/logo.png" alt="Logo" class="btn-icon">
                </a>
                <a href="#" class="action-btn">Repeticiones</a>
                <a href="{{ route('demo') }}" class="action-btn double-column">Jugar demo</a>
            </div>

            <div style="width:100%; display:flex; justify-content:center;">
                @if($activeSession)
                    <a href="{{ route('participar') }}" class="main-btn" style="pointer-events:auto;">
                        Participar
                    </a>
                @else
                    <a href="#" class="main-btn disabled" style="pointer-events:none;">
                        Participar
                    </a>
                @endif
            </div>

            @if($participant)
                <form id="salirForm" action="{{ route('salir.juego') }}" method="POST" style="margin-top:16px; width:100%; text-align:center;">
                    @csrf
                    <button type="button" onclick="abrirModalSalir()" class="main-btn exit-btn">
                        Salir del juego
                    </button>
                </form>
            @endif
        </div>
    </div>
        <div style="
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    text-align: center;
    padding: 5px;
    font-size: 0.9rem;
    color: #00f0ff80;
    background: transparent;
">
    © {{ date('Y') }} Cuanto Sabe. Todos los derechos reservados. Desarrollado por Leandro Cebreiros.
</div>

    <!-- Modal de confirmación -->
    <div id="modalSalir" class="modal">
        <div class="modal-content">
            <div class="modal-icon">⚠️</div>
            <div class="modal-text">
                ¿Seguro que deseas salir del juego?<br>
                <span>Se perderá la sesión y el puntaje alcanzado.</span>
            </div>
            <div class="modal-actions">
                <button onclick="cerrarModalSalir()" class="modal-btn modal-btn-cancel">
                    Cancelar
                </button>
                <button onclick="confirmarSalidaFinal()" class="modal-btn modal-btn-confirm">
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
        document.addEventListener('mousedown', function(e) {
            const modal = document.getElementById('modalSalir');
            const dialog = modal?.querySelector('.modal-content');
            
            if(modal && modal.style.display === 'flex' && !dialog.contains(e.target)) {
                cerrarModalSalir();
            }
        });
    </script>
</body>
</html>