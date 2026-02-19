@php
    $apuestaDisponibles = $activeSession ? max(0, ($activeSession->isExpress() ? 1 : 2) - (int)$activeSession->apuesta_x2_usadas) : 0;
    $apuestaActive      = $activeSession && $activeSession->apuesta_x2_active;
    $descarteUsados     = $activeSession ? (int)$activeSession->descarte_usados : 0;
    $descarteDisponible = $activeSession ? ($descarteUsados < 1) : false;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stream Deck — Cuanto Sabe</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            width: 100%;
            height: 100%;
            background: #060610;
            font-family: 'Orbitron', 'Segoe UI', sans-serif;
            overflow: hidden;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }

        /* ─── GRID ─────────────────────────────────────────── */
        .sd-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: clamp(4px, 1.2vmin, 12px);
            padding: clamp(5px, 1.5vmin, 14px);
            width: 100vw;
            height: 100vh;
            height: 100dvh;
        }

        /* ─── BOTÓN BASE ────────────────────────────────────── */
        .sd-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: clamp(4px, 1.5vmin, 12px);
            border-radius: clamp(8px, 2.5vmin, 20px);
            border: 2px solid rgba(255,255,255,0.12);
            cursor: pointer;
            transition: transform 0.12s ease, box-shadow 0.2s ease, background 0.25s ease, border-color 0.25s ease;
            background: rgba(10, 12, 30, 0.96);
            color: rgba(255,255,255,0.5);
            font-family: inherit;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: clamp(0.5rem, 2.3vmin, 1rem);
            user-select: none;
            position: relative;
            overflow: hidden;
            -webkit-tap-highlight-color: transparent;
            outline: none;
        }

        .sd-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0);
            transition: background 0.1s ease;
            pointer-events: none;
        }

        .sd-btn:active::after {
            background: rgba(255,255,255,0.08);
        }

        .sd-btn:active {
            transform: scale(0.93);
        }

        .sd-btn svg {
            width: clamp(20px, 7vmin, 52px);
            height: clamp(20px, 7vmin, 52px);
            stroke: currentColor;
            flex-shrink: 0;
        }

        .sd-btn .btn-label {
            line-height: 1.1;
            text-align: center;
            color: inherit;
        }

        .sd-btn .btn-sub {
            font-size: clamp(0.45rem, 1.6vmin, 0.72rem);
            opacity: 0.75;
            font-weight: 700;
            margin-top: -2px;
        }

        /* Deshabilitado cuando no hay sesión */
        .sd-btn.no-session {
            opacity: 0.22;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* ─── RULETA ────────────────────────────────────────── */
        .btn-ruleta {
            border-color: rgba(0, 240, 255, 0.4);
            color: #00f0ff;
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.15);
        }
        .btn-ruleta:hover {
            box-shadow: 0 0 25px rgba(0, 240, 255, 0.35);
        }
        .btn-ruleta.spinning {
            border-color: #ff00ff;
            color: #ff00ff;
            background: rgba(25, 0, 40, 0.97);
            box-shadow: 0 0 30px rgba(255, 0, 255, 0.55),
                        inset 0 0 15px rgba(255, 0, 255, 0.1);
            animation: sd-pulse 1s ease-in-out infinite;
        }

        /* ─── REVELAR ───────────────────────────────────────── */
        .btn-revelar {
            border-color: rgba(25, 255, 140, 0.4);
            color: #19ff8c;
            box-shadow: 0 0 15px rgba(25, 255, 140, 0.12);
        }
        .btn-revelar:hover {
            box-shadow: 0 0 25px rgba(25, 255, 140, 0.35);
        }
        .btn-revelar:active {
            background: rgba(0, 30, 15, 0.97);
        }

        /* ─── REFRESCAR ─────────────────────────────────────── */
        .btn-refrescar {
            border-color: rgba(255, 204, 0, 0.4);
            color: #ffcc00;
            box-shadow: 0 0 15px rgba(255, 204, 0, 0.1);
        }
        .btn-refrescar:hover {
            box-shadow: 0 0 25px rgba(255, 204, 0, 0.3);
        }
        .btn-refrescar:active {
            background: rgba(30, 22, 0, 0.97);
        }

        /* ─── APUESTA ───────────────────────────────────────── */
        .btn-apuesta {
            border-color: rgba(30, 144, 255, 0.4);
            color: #5aabff;
            box-shadow: 0 0 15px rgba(30, 144, 255, 0.1);
        }
        .btn-apuesta.on {
            border-color: #00bfff;
            color: #fff;
            background: linear-gradient(145deg, #0a2a50, #0e3a6a);
            box-shadow: 0 0 30px rgba(0, 191, 255, 0.6),
                        inset 0 0 15px rgba(0, 191, 255, 0.1);
        }
        .btn-apuesta.exhausted {
            opacity: 0.25;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* ─── DESCARTE ──────────────────────────────────────── */
        .btn-descarte {
            border-color: rgba(255, 99, 71, 0.4);
            color: #ff8c75;
            box-shadow: 0 0 15px rgba(255, 99, 71, 0.1);
        }
        .btn-descarte.on {
            border-color: #ff6347;
            color: #fff;
            background: linear-gradient(145deg, #3a0e06, #550f09);
            box-shadow: 0 0 30px rgba(255, 99, 71, 0.55),
                        inset 0 0 15px rgba(255, 99, 71, 0.1);
        }
        .btn-descarte.exhausted {
            opacity: 0.25;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* ─── PANEL INFO ────────────────────────────────────── */
        .sd-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: clamp(2px, 1vmin, 8px);
            border-radius: clamp(8px, 2.5vmin, 20px);
            border: 2px solid rgba(255,255,255,0.08);
            background: rgba(5, 6, 18, 0.97);
            text-align: center;
            padding: 6px;
            overflow: hidden;
        }

        .sd-info .info-dot {
            width: clamp(6px, 2vmin, 14px);
            height: clamp(6px, 2vmin, 14px);
            border-radius: 50%;
            background: #ff2d3b;
            box-shadow: 0 0 10px #ff2d3b88;
            flex-shrink: 0;
        }
        .sd-info.active .info-dot {
            background: #10ff62;
            box-shadow: 0 0 12px #10ff6288;
            animation: blink 2s ease-in-out infinite;
        }

        .sd-info .info-session-label {
            font-size: clamp(0.45rem, 1.8vmin, 0.7rem);
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sd-info .info-session-name {
            font-size: clamp(0.5rem, 2vmin, 0.85rem);
            font-weight: 700;
            color: #00f0ff;
            text-shadow: 0 0 8px rgba(0, 240, 255, 0.6);
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            padding: 0 4px;
        }

        .sd-info .info-q-value {
            font-size: clamp(1rem, 5vmin, 2.5rem);
            font-weight: 900;
            color: #00f0ff;
            text-shadow: 0 0 12px rgba(0, 240, 255, 0.8);
            line-height: 1;
        }

        .sd-info .info-q-label {
            font-size: clamp(0.4rem, 1.5vmin, 0.6rem);
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
        }

        .sd-info.limit-reached .info-q-value {
            color: #ff6666;
            text-shadow: 0 0 12px rgba(255, 102, 102, 0.8);
        }

        /* ─── FEEDBACK FLASH ─────────────────────────────────── */
        @keyframes flash-ok {
            0%   { box-shadow: 0 0 0px rgba(25,255,140,0); }
            40%  { box-shadow: 0 0 40px rgba(25,255,140,0.8); }
            100% { box-shadow: 0 0 0px rgba(25,255,140,0); }
        }
        @keyframes flash-err {
            0%   { box-shadow: 0 0 0px rgba(255,68,68,0); }
            40%  { box-shadow: 0 0 40px rgba(255,68,68,0.8); }
            100% { box-shadow: 0 0 0px rgba(255,68,68,0); }
        }
        .flash-ok  { animation: flash-ok  0.5s ease forwards; }
        .flash-err { animation: flash-err 0.5s ease forwards; }

        /* ─── ANIMACIONES ────────────────────────────────────── */
        @keyframes sd-pulse {
            0%, 100% { transform: scale(1); }
            50%       { transform: scale(1.025); }
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.45; }
        }
    </style>
</head>
<body>

<div class="sd-grid" id="sdGrid">

    {{-- ① GIRAR / PARAR RULETA --}}
    <button class="sd-btn btn-ruleta {{ !$activeSession ? 'no-session' : '' }}"
            id="btnRuleta"
            onclick="handleRuleta()">
        {{-- Ícono PLAY --}}
        <svg id="iconPlay" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <polygon points="10 8 16 12 10 16 10 8"/>
        </svg>
        {{-- Ícono STOP (oculto) --}}
        <svg id="iconStop" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
            <rect x="6" y="4" width="4" height="16"/>
            <rect x="14" y="4" width="4" height="16"/>
        </svg>
        <span class="btn-label" id="labelRuleta">GIRAR<br><span class="btn-sub">RULETA</span></span>
    </button>

    {{-- ② REVELAR --}}
    <button class="sd-btn btn-revelar {{ !$activeSession ? 'no-session' : '' }}"
            id="btnRevelar"
            onclick="handleRevelar()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>
        <span class="btn-label">REVELAR</span>
    </button>

    {{-- ③ REFRESCAR --}}
    <button class="sd-btn btn-refrescar {{ !$activeSession ? 'no-session' : '' }}"
            id="btnRefrescar"
            onclick="handleRefrescar()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
        </svg>
        <span class="btn-label">REFRESCAR</span>
    </button>

    {{-- ④ APUESTA x2 --}}
    <button class="sd-btn btn-apuesta {{ $apuestaActive ? 'on' : '' }} {{ !$activeSession || $apuestaDisponibles <= 0 && !$apuestaActive ? 'exhausted' : '' }}"
            id="btnApuesta"
            onclick="handleApuesta()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="17 1 21 5 17 9"/>
            <path d="M3 11V9a4 4 0 0 1 4-4h14"/>
            <polyline points="7 23 3 19 7 15"/>
            <path d="M21 13v2a4 4 0 0 1-4 4H3"/>
        </svg>
        <span class="btn-label">APUESTA<br><span class="btn-sub" id="apuestaSub">x{{ $apuestaDisponibles }} disponible{{ $apuestaDisponibles !== 1 ? 's' : '' }}</span></span>
    </button>

    {{-- ⑤ DESCARTE --}}
    <button class="sd-btn btn-descarte {{ $descarteDisponible ? 'on' : 'exhausted' }}"
            id="btnDescarte"
            onclick="handleDescarte()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
            <path d="M10 11v6M14 11v6"/>
            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
        </svg>
        <span class="btn-label">DESCARTE<br><span class="btn-sub" id="descarteSub">{{ $descarteDisponible ? 'disponible' : 'agotado' }}</span></span>
    </button>

    {{-- ⑥ INFO: sesión + contador de preguntas --}}
    <div class="sd-info {{ $activeSession ? 'active' : '' }}" id="sdInfo">
        <div class="info-dot"></div>
        @if($activeSession)
            <div class="info-session-name">{{ $activeSession->guest_name }}</div>
            <div class="info-q-value" id="qCount">{{ $questionCount }}</div>
            <div class="info-q-label">/ 15 preguntas</div>
        @else
            <div class="info-session-label" style="font-size:clamp(0.6rem,2.2vmin,0.9rem); color:rgba(255,255,255,0.3);">SIN SESIÓN</div>
        @endif
    </div>

</div>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>
<script>
// ─── ESTADO ──────────────────────────────────────────────────────────────
let isSpinning        = false;
let apuestaActive     = {{ $apuestaActive ? 'true' : 'false' }};
let apuestaDisp       = {{ $apuestaDisponibles }};
let descarteDisp      = {{ $descarteDisponible ? '1' : '0' }};
let hasActiveQuestion = false;
let questionCount     = {{ $questionCount }};
const hasSession      = {{ $activeSession ? 'true' : 'false' }};

const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ─── PUSHER ──────────────────────────────────────────────────────────────
window.Pusher = Pusher;
window.Echo   = new Echo({
    broadcaster: 'pusher',
    key:         '{{ env('PUSHER_APP_KEY') }}',
    cluster:     '{{ env('PUSHER_APP_CLUSTER') }}',
    forceTLS:    true,
});

// ─── HELPERS UI ──────────────────────────────────────────────────────────
function flash(el, type) {
    el.classList.remove('flash-ok', 'flash-err');
    void el.offsetWidth; // force reflow
    el.classList.add(type === 'ok' ? 'flash-ok' : 'flash-err');
    setTimeout(() => el.classList.remove('flash-ok', 'flash-err'), 600);
}

function syncApuestaUI() {
    const btn = document.getElementById('btnApuesta');
    const sub = document.getElementById('apuestaSub');
    if (!btn) return;

    btn.classList.toggle('on', apuestaActive);
    if (apuestaDisp <= 0 && !apuestaActive) {
        btn.classList.add('exhausted');
    } else {
        btn.classList.remove('exhausted');
    }
    if (sub) sub.textContent = `x${apuestaDisp} disponible${apuestaDisp !== 1 ? 's' : ''}`;
}

function syncDescarteUI() {
    const btn = document.getElementById('btnDescarte');
    const sub = document.getElementById('descarteSub');
    if (!btn) return;

    if (descarteDisp > 0) {
        btn.classList.add('on');
        btn.classList.remove('exhausted');
    } else {
        btn.classList.remove('on');
        btn.classList.add('exhausted');
    }
    if (sub) sub.textContent = descarteDisp > 0 ? 'disponible' : 'agotado';
}

function syncRuletaUI() {
    const btn    = document.getElementById('btnRuleta');
    const play   = document.getElementById('iconPlay');
    const stop   = document.getElementById('iconStop');
    const label  = document.getElementById('labelRuleta');
    if (!btn) return;

    if (isSpinning) {
        btn.classList.add('spinning');
        if (play)  play.style.display  = 'none';
        if (stop)  stop.style.display  = '';
        if (label) label.innerHTML     = 'PARAR<br><span class="btn-sub">RULETA</span>';
    } else {
        btn.classList.remove('spinning');
        if (play)  play.style.display  = '';
        if (stop)  stop.style.display  = 'none';
        if (label) label.innerHTML     = 'GIRAR<br><span class="btn-sub">RULETA</span>';
    }
}

function syncQCount() {
    const el = document.getElementById('qCount');
    if (!el) return;
    el.textContent = questionCount;
    const info = document.getElementById('sdInfo');
    if (info) info.classList.toggle('limit-reached', questionCount >= 15);
}

// ─── HANDLERS ────────────────────────────────────────────────────────────
function handleRuleta() {
    if (!hasSession) return;
    isSpinning = !isSpinning;
    syncRuletaUI();

    fetch('/game-session/girar-ruleta', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ action: isSpinning ? 'start' : 'stop' })
    })
    .then(r => r.json())
    .then(d => {
        flash(document.getElementById('btnRuleta'), d.error ? 'err' : 'ok');
        if (d.error) { isSpinning = !isSpinning; syncRuletaUI(); }
    })
    .catch(() => { isSpinning = !isSpinning; syncRuletaUI(); });
}

function handleRevelar() {
    if (!hasSession) return;
    fetch('/game-session/reveal', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(d => flash(document.getElementById('btnRevelar'), d.success ? 'ok' : 'err'))
    .catch(() => flash(document.getElementById('btnRevelar'), 'err'));
}

function handleRefrescar() {
    if (!hasSession) return;
    // Resetear estado de ruleta local también
    isSpinning = false;
    syncRuletaUI();

    fetch('/game-session/overlay-reset', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(d => flash(document.getElementById('btnRefrescar'), 'ok'))
    .catch(() => flash(document.getElementById('btnRefrescar'), 'err'));
}

function handleApuesta() {
    if (!hasSession) return;
    const btn = document.getElementById('btnApuesta');
    btn.style.pointerEvents = 'none';

    fetch('/game/apuesta-x2/toggle', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({})
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            apuestaActive = !!d.apuesta_x2_active;
            apuestaDisp   = Number(d.apuesta_x2_disponibles ?? 0);
            syncApuestaUI();
            flash(btn, 'ok');
        } else {
            flash(btn, 'err');
        }
    })
    .catch(() => flash(btn, 'err'))
    .finally(() => { btn.style.pointerEvents = ''; });
}

function handleDescarte() {
    if (!hasSession || descarteDisp <= 0) return;
    const btn = document.getElementById('btnDescarte');
    btn.style.pointerEvents = 'none';

    fetch('/game/descarte/toggle', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({})
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            descarteDisp = d.descarte_disponible ? 1 : 0;
            syncDescarteUI();
            flash(btn, 'ok');
        } else {
            flash(btn, 'err');
        }
    })
    .catch(() => flash(btn, 'err'))
    .finally(() => { btn.style.pointerEvents = ''; });
}

// ─── PUSHER LISTENERS ────────────────────────────────────────────────────
if (window.Echo && hasSession) {
    const ch = window.Echo.channel('cuanto-sabe-overlay');

    // Bonus (apuesta / descarte)
    ch.listen('.GameBonusUpdated', (e) => {
        apuestaActive = !!e.apuesta_x2_active;
        const modoLimite = (e.modo_juego === 'express') ? 1 : 2;
        apuestaDisp   = Math.max(0, modoLimite - Number(e.apuesta_x2_usadas || 0));
        descarteDisp  = (Number(e.descarte_usados || 0) < 1) ? 1 : 0;
        syncApuestaUI();
        syncDescarteUI();
    });

    // Nueva pregunta → habilitar revelar + actualizar contador
    ch.listen('.nueva-pregunta', (e) => {
        const data    = e.data || e || {};
        const opciones = data.opciones || [];
        hasActiveQuestion = opciones.length > 0;

        if (opciones.length > 0) {
            questionCount++;
            syncQCount();
        }

        // Si la ruleta estaba girando, se detiene al llegar pregunta
        if (isSpinning) {
            isSpinning = false;
            syncRuletaUI();
        }
    });

    // Revelar respuesta → actualizar contador real desde backend
    ch.listen('.revelar-respuesta', (e) => {
        const payload = e.data || e || {};
        if (typeof payload.question_count !== 'undefined') {
            questionCount = payload.question_count;
            syncQCount();
        }
        hasActiveQuestion = false;
    });

    // Reset overlay
    ch.listen('.overlay-reset', () => {
        hasActiveQuestion = false;
        if (isSpinning) { isSpinning = false; syncRuletaUI(); }
    });
}
</script>
</body>
</html>
