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
        .chat-wrapper {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100vh;
            height: 100dvh;
            background: #080818;
        }

        .chat-topbar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: rgba(10, 14, 35, 0.98);
            border-bottom: 1px solid rgba(0, 240, 255, 0.2);
            flex-shrink: 0;
            height: 44px;
        }

        .chat-back-btn {
            background: rgba(0, 240, 255, 0.1);
            color: #00f0ff;
            border: 1.5px solid rgba(0, 240, 255, 0.35);
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .chat-back-btn:hover {
            background: rgba(0, 240, 255, 0.22);
            border-color: #00f0ff;
            box-shadow: 0 0 12px rgba(0, 240, 255, 0.35);
        }

        .chat-back-btn svg {
            width: 13px;
            height: 13px;
            flex-shrink: 0;
        }

        .chat-title {
            color: #00f0ff;
            font-size: 0.95rem;
            font-weight: 700;
            text-shadow: 0 0 8px rgba(0, 240, 255, 0.6);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sd-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: clamp(4px, 1.2vmin, 12px);
            padding: clamp(5px, 1.5vmin, 14px);
            width: 100%;
            flex: 1;
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

        .fullscreen-btn-minimal {
    background: rgba(79, 70, 229, 0.15);
    color: #818cf8;
    border: 1.5px solid rgba(129, 140, 248, 0.3);
    border-radius: 8px;
    padding: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(8px);
    width: 36px;
    height: 36px;
    flex-shrink: 0;  /* Evita que se comprima */
}

.fullscreen-btn-minimal:hover {
    background: rgba(79, 70, 229, 0.25);
    border-color: rgba(129, 140, 248, 0.5);
    color: #c7d2fe;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.fullscreen-btn-minimal:active {
    transform: translateY(0);
}

.fullscreen-btn-minimal.active {
    background: rgba(16, 185, 129, 0.2);
    border-color: rgba(52, 211, 153, 0.5);
    color: #6ee7b7;
}

.fullscreen-btn-minimal.active:hover {
    background: rgba(16, 185, 129, 0.3);
    border-color: rgba(52, 211, 153, 0.7);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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

        /* ─── OPCIONES A/B/C/D ──────────────────────────────────── */
        .sd-options {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: clamp(4px, 1.2vmin, 12px);
            padding: 0 clamp(5px, 1.5vmin, 14px) clamp(5px, 1.5vmin, 14px);
            flex-shrink: 0;
        }

        .btn-opcion {
            border-color: rgba(0, 240, 255, 0.25);
            color: rgba(255,255,255,0.5);
            min-height: clamp(38px, 9vh, 72px);
        }

        .btn-opcion .opcion-letra {
            font-size: clamp(1rem, 4.5vmin, 2rem);
            font-weight: 900;
            line-height: 1;
        }

        .btn-opcion .opcion-texto {
            font-size: clamp(0.38rem, 1.3vmin, 0.58rem);
            font-weight: 600;
            opacity: 0.7;
            text-align: center;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            padding: 0 4px;
            text-transform: none;
            letter-spacing: 0;
        }

        /* Sin pregunta activa: muy tenue */
        .btn-opcion.no-question {
            opacity: 0.18;
            pointer-events: none;
        }

        /* Con pregunta activa */
        .btn-opcion.question-active {
            color: rgba(255,255,255,0.8);
            border-color: rgba(0, 240, 255, 0.45);
        }

        /* Seleccionado: amarillo */
        .btn-opcion.selected {
            background: #ffe47a;
            color: #1a1400;
            border-color: #ffe47a;
            box-shadow: 0 0 28px rgba(255, 228, 122, 0.75),
                        inset 0 0 10px rgba(255, 228, 122, 0.15);
        }

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

<div class="chat-wrapper">
    <div class="chat-topbar">
        <a href="{{ route('dashboard') }}" class="chat-back-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
        <span class="chat-title">Stream Deck</span>
        <button type="button"
            id="fullscreenBtn"
            class="fullscreen-btn-minimal"
            onclick="toggleFullscreen()"
            title="Pantalla completa (Ctrl+F)"
            style="margin-left: auto;">
            <svg class="fs-icon expand" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
            </svg>
            <svg class="fs-icon compress" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/>
            </svg>
        </button>
    </div>

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

</div>{{-- sd-grid --}}

{{-- Fila de opciones A / B / C / D --}}
<div class="sd-options" id="sdOptions">
    @foreach(['A','B','C','D'] as $op)
    <button class="sd-btn btn-opcion {{ !$activeSession ? 'no-session' : 'no-question' }}"
            id="btnOp{{ $op }}"
            onclick="handleOpcion('{{ $op }}')">
        <span class="opcion-letra">{{ $op }}</span>
        <span class="opcion-texto" id="opText{{ $op }}"></span>
    </button>
    @endforeach
</div>

</div>{{-- chat-wrapper --}}

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
let currentOpcion     = null;   // Opción seleccionada actualmente (A/B/C/D)
let isRevealed        = false;  // Bloquea revelar la misma pregunta dos veces

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

function syncOpcionUI() {
    ['A','B','C','D'].forEach(l => {
        const btn = document.getElementById('btnOp' + l);
        if (!btn) return;

        // Estado base según si hay pregunta activa
        if (!hasSession) {
            btn.className = 'sd-btn btn-opcion no-session';
            return;
        }

        if (!hasActiveQuestion) {
            btn.className = 'sd-btn btn-opcion no-question';
            return;
        }

        // Hay pregunta activa: mostrar en estado normal o seleccionado
        btn.className = 'sd-btn btn-opcion question-active' + (currentOpcion === l ? ' selected' : '');
    });
}

function clearOpcionUI() {
    currentOpcion = null;
    ['A','B','C','D'].forEach(l => {
        const txt = document.getElementById('opText' + l);
        if (txt) txt.textContent = '';
    });
    syncOpcionUI();
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

function syncRevelarBtn() {
    const btn = document.getElementById('btnRevelar');
    if (!btn) return;
    if (isRevealed) {
        btn.style.opacity       = '0.3';
        btn.style.pointerEvents = 'none';
        const span = btn.querySelector('.btn-label');
        if (span) span.textContent = 'REVELADO';
    } else {
        btn.style.opacity       = '';
        btn.style.pointerEvents = '';
        const span = btn.querySelector('.btn-label');
        if (span) span.textContent = 'REVELAR';
    }
}

function handleRevelar() {
    if (!hasSession || isRevealed) return;
    fetch('/game-session/reveal', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        // Incluir la opción seleccionada para evitar race conditions
        body: JSON.stringify({ selected_option: currentOpcion })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success || d.already_revealed) {
            isRevealed = true;
            syncRevelarBtn();
        }
        flash(document.getElementById('btnRevelar'), (d.success || d.already_revealed) ? 'ok' : 'err');
    })
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

function handleOpcion(label) {
    if (!hasSession || !hasActiveQuestion) return;
    const btn = document.getElementById('btnOp' + label);

    currentOpcion = label;
    syncOpcionUI();

    fetch('/game-session/select-option', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ opcion: label })
    })
    .then(r => r.json())
    .then(d => flash(btn, d.ok ? 'ok' : 'err'))
    .catch(() => flash(btn, 'err'));
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

    // Nueva pregunta → habilitar opciones + actualizar contador
    ch.listen('.nueva-pregunta', (e) => {
        const data    = e.data || e || {};
        const opciones = data.opciones || [];
        hasActiveQuestion = opciones.length > 0;

        // Resetear reveal y selección
        isRevealed = false;
        syncRevelarBtn();
        currentOpcion = null;
        ['A','B','C','D'].forEach(l => {
            const txt = document.getElementById('opText' + l);
            if (!txt) return;
            const op = opciones.find(o => o.label === l);
            txt.textContent = op ? op.texto : '';
        });
        syncOpcionUI();

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

    // Opción seleccionada (sincronizar entre panel y streamdeck)
    ch.listen('.opcion-seleccionada', (e) => {
        const opcion = e.opcion || (e.data && e.data.opcion);
        if (opcion && ['A','B','C','D'].includes(opcion.toUpperCase())) {
            currentOpcion = opcion.toUpperCase();
            syncOpcionUI();
        }
    });

    // Revelar respuesta → marcar como revelado + actualizar contador
    ch.listen('.revelar-respuesta', (e) => {
        const payload = e.data || e || {};
        if (typeof payload.question_count !== 'undefined') {
            questionCount = payload.question_count;
            syncQCount();
        }
        hasActiveQuestion = false;
        isRevealed = true;
        syncRevelarBtn();
        syncOpcionUI();
    });

    // Reset overlay
    ch.listen('.overlay-reset', () => {
        hasActiveQuestion = false;
        isRevealed = false;
        syncRevelarBtn();
        clearOpcionUI();
        if (isSpinning) { isSpinning = false; syncRuletaUI(); }
    });
}

// Restaurar estado al cargar/refrescar la página
document.addEventListener('DOMContentLoaded', () => {
    if (!hasSession) return;
    fetch('/overlay/api/pregunta')
        .then(r => r.json())
        .then(data => {
            if (!data || !data.pregunta_id || !data.opciones || !data.opciones.length) return;

            hasActiveQuestion = true;

            // Poblar textos de opciones
            const opciones = data.opciones || [];
            ['A','B','C','D'].forEach(l => {
                const txt = document.getElementById('opText' + l);
                if (!txt) return;
                const op = opciones.find(o => o.label === l);
                txt.textContent = op ? op.texto : '';
            });

            // Restaurar opción revelada
            if (data.revealed_option) {
                currentOpcion = data.revealed_option.toUpperCase();
            }

            // Restaurar estado de reveal
            if (data.is_revealed) {
                isRevealed = true;
                syncRevelarBtn();
            }

            syncOpcionUI();
        })
        .catch(e => console.warn('[SD] No se pudo restaurar estado:', e));
});

// Función para activar/desactivar pantalla completa
function toggleFullscreen() {
    const btn = document.getElementById('fullscreenBtn');
    const expandIcon = btn.querySelector('.expand');
    const compressIcon = btn.querySelector('.compress');
    
    if (!document.fullscreenElement) {
        // Entrar en pantalla completa
        document.documentElement.requestFullscreen().then(() => {
            btn.classList.add('active');
            btn.title = 'Salir de pantalla completa (ESC)';
            expandIcon.style.display = 'none';
            compressIcon.style.display = 'block';
        }).catch(err => {
            console.error('Error al activar pantalla completa:', err);
        });
    } else {
        // Salir de pantalla completa
        document.exitFullscreen().then(() => {
            btn.classList.remove('active');
            btn.title = 'Pantalla completa (Ctrl+F)';
            expandIcon.style.display = 'block';
            compressIcon.style.display = 'none';
        }).catch(err => {
            console.error('Error al salir de pantalla completa:', err);
        });
    }
}

// Listener para detectar cambios de pantalla completa (ESC)
document.addEventListener('fullscreenchange', () => {
    const btn = document.getElementById('fullscreenBtn');
    const expandIcon = btn.querySelector('.expand');
    const compressIcon = btn.querySelector('.compress');
    
    if (!document.fullscreenElement) {
        btn.classList.remove('active');
        btn.title = 'Pantalla completa (Ctrl+F)';
        expandIcon.style.display = 'block';
        compressIcon.style.display = 'none';
    }
});

// Atajo de teclado: Ctrl+F
document.addEventListener('keydown', (e) => {
    if (e.key === 'f' && e.ctrlKey) {
        e.preventDefault();
        toggleFullscreen();
    }
});

</script>
</body>
</html>
