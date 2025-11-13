@extends('layouts.app')
<!--@if(isset($participant))
<div class="participant-name-top">
    <span class="participant-label">Participante:</span>
    <span class="participant-value">{{ $participant->username }}</span>
</div>
@endif-->

@php
  $activeSession = \App\Models\GameSession::where('status', 'active')->latest()->first();
  $guestName = $activeSession->guest_name ?? 'Invitado';
  $motivoNombre = optional(\App\Models\Motivo::find($activeSession->motivo_id))->nombre ?? 'Motivo';
@endphp

@if($activeSession)
  <!-- Tarjeta Invitado + Motivo (centrada) -->
  <div class="guest-card-wrap w-full flex items-center justify-center mb-8">
    <div class="guest-card">
      <div class="guest-chip">INVITADO</div>
      <div class="guest-name" title="Invitado">{{ $guestName }}</div>
      <div class="guest-motivo"><span>Motivo:</span> {{ $motivoNombre }}</div>
    </div>
  </div>
@endif

{{-- Fila estado: participante + puntaje --}}
<div class="status-row">
  @if(isset($participant))
    <div class="participant-name-top">
      <span class="participant-label">Participante:</span>
      <span class="participant-value">{{ $participant->username }}</span>
    </div>
  @endif

  <div id="puntaje-container" class="puntaje-top-container">
    <div id="puntaje-card" class="puntaje-card">
      <x-puntaje-participante :puntaje="$puntaje" />
    </div>
  </div>
</div>


@section('content')
<div id="pantalla" class="participar-container">

  <!-- Contenedor pregunta -->
  <div id="main-question-box" class="participar-overlay-content" style="{{ (!isset($pregunta['pregunta']) || !$pregunta['pregunta']) ? 'display:none;' : '' }}">
    <div id="respuesta-msg" class="respuesta-msg-top" style="display:none;">
      <!-- Se muestra solo desde JS -->
    </div>

    <div class="question-bar">
      {{ $pregunta['pregunta'] ?? '' }}
    </div>

    <form id="participar-form" method="POST" autocomplete="off" style="{{ isset($sinPregunta) && $sinPregunta ? 'display:none;' : '' }}">
      @csrf
      <input type="hidden" name="question_id" value="{{ $pregunta['pregunta_id'] ?? '' }}">

      <div class="answers-row">
        @if(isset($pregunta['opciones']))
          @foreach($pregunta['opciones'] as $op)
            <button
              type="button"
              data-label="{{ $op['label'] }}"
              class="option-box"
            >
              <span class="opt-label">{{ $op['label'] }}</span>
              <span class="opt-text">{{ $op['texto'] }}</span>
            </button>
          @endforeach
        @endif
      </div>
    </form>
  </div>

  <!-- MENSAJE SIEMPRE EN EL DOM -->
  <div id="msg-no-question"
    class="bg-[#171c2e] rounded-[2.5rem] px-12 py-12 shadow-2xl border-4 border-[#01e3fd4d] text-2xl text-white font-semibold text-center max-w-2xl mx-auto tracking-wide neon-glow"
    style="{{ (!isset($sinPregunta) || !$sinPregunta) ? 'display:none;' : '' }};margin-top:50px;">
    No hay pregunta activa.<br><br>
    Esperá que el host envíe una nueva pregunta...
  </div>
  <form id="salir-form" method="POST" action="{{ route('salirDelJuego') }}">
    @csrf
    <button type="button" class="salir-btn" onclick="abrirModalSalir()">
        <img src="{{ asset('images/salir.png') }}" alt="Salir del juego">
    </button>
</form>
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
            <button type="button" onclick="cerrarModalSalir()"
                style="padding:11px 25px; border-radius:1.4em; border:none; background:#222a37; color:#19ff8c; font-weight:bold; font-size:1.06rem; box-shadow:0 0 8px #00f0ff99; cursor:pointer; transition:background .17s;">
                Cancelar
            </button>
            <button type="button" onclick="confirmarSalidaFinal()"
                style="padding:11px 25px; border-radius:1.4em; border:none; background:#ff4444; color:#fff; font-weight:bold; font-size:1.06rem; box-shadow:0 0 8px #ff444488; cursor:pointer; transition:background .17s;">
                Sí, salir
            </button>
        </div>
    </div>
</div>

@endsection

<style>

.guest-card-wrap { pointer-events: none; } /* no bloquea clics en las opciones */
.guest-card {
  pointer-events: auto; /* por si querés tooltip/selection */
  background: linear-gradient(135deg, rgba(4,38,78,0.82) 0%, rgba(0,52,94,0.82) 100%);
  border: 4px solid #00f0ff66;
  border-radius: 1.6rem;
  box-shadow: 0 8px 40px #012b4970, 0 0 18px #00f0ff33;
  padding: 20px 28px;
  min-width: 280px;
  max-width: min(92vw, 640px);
  text-align: center;
  backdrop-filter: blur(3px);
}

.guest-chip {
  display: inline-block;
  font-family: 'Orbitron', Arial, sans-serif;
  font-weight: 900;
  letter-spacing: .12em;
  font-size: .80rem;
  color: #002640;
  background: #00f0ff;
  border-radius: 999px;
  padding: 6px 12px;
  box-shadow: 0 0 12px #00e8fcaa, inset 0 0 0 2px #00264033;
  margin-bottom: 10px;
}

.guest-name {
  font-family: 'Orbitron', Arial, sans-serif;
  font-size: clamp(1.25rem, 2.8vw, 1.8rem);
  font-weight: 900;
  color: #fff;
  text-shadow: 0 0 16px #00e8fc, 0 0 6px #fff3;
  line-height: 1.15;
  margin-bottom: 6px;
  word-break: break-word;
}

.guest-motivo {
  font-weight: 700;
  color: #36d1ff;
  font-size: clamp(0.95rem, 2.2vw, 1.05rem);
  letter-spacing: .02em;
}
.guest-motivo span { color: #19ff8c; font-weight: 900; margin-right: 6px; }

/* Mobile: ajusta paddings para que no tape nada */
@media (max-width: 640px) {
  .guest-card { padding: 14px 16px; border-width: 3px; }
}

.participant-name-top {
    position: fixed;
    top: 15px;
    left: 20px;
    z-index: 9999;
    background: linear-gradient(90deg, #161936ea 80%, #172047cc 100%);
    color: #36d1ff;
    border-radius: 16px;
    box-shadow: 0 0 12px #009acdaa, 0 0 2px #00f0ff33;
    padding: 10px 28px 10px 18px;
    font-family: 'Orbitron', Arial, sans-serif;
    font-size: 1.19rem;
    font-weight: 700;
    letter-spacing: 0.6px;
    display: flex;
    align-items: center;
    min-width: 180px;
    max-width: 80vw;
}
.participant-label {
    color: #19ff8c;
    margin-right: 8px;
}
.participant-value {
    color: #fff;
    font-weight: 900;
    text-shadow: 0 0 5px #19ff8c77, 0 0 1px #fff5;
}
@media (max-width: 640px) {
    .participant-name-top {
        top: 9px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 1rem;
        padding: 8px 16px;
        min-width: 110px;
    }
}

.puntaje-anim-bounce {
    animation: puntaje-bounce 0.55s cubic-bezier(.23,1.38,.55,.98);
}
.puntaje-anim-shake {
    animation: puntaje-shake 0.45s cubic-bezier(.45,1.38,.55,.98);
}

body {
  background:
    radial-gradient(circle at 52% 44%, #1b0362 0%, #030015 95%) !important,
    url('/images/CS.png') center center no-repeat;
  background-size: auto 80vh, cover;
  color: #00f0ff;
  position: relative;
  min-height: 100vh !important;
  height: 100% !important;
  width: 100vw !important;
  overflow-x: hidden;
}
html, body {
  height: 100vh !important;          /* ocupar exactamente el alto de la pantalla */
  min-height: 100vh !important;
  width: 100vw !important;
  overflow: hidden !important;        /* sin scroll en Y ni X */
  overscroll-behavior: none;          /* evita "rebote" */
  touch-action: manipulation;

  /* tus fondos tal cual */
  background:
    radial-gradient(circle at 52% 44%, #1b0362 0%, #030015 95%) 0 0/100vw 100vh no-repeat,
    url('/images/CS.png') center center/auto 80vh no-repeat;
  background-color: #1b0362;
  color: #00f0ff;
}
body::before { display: none !important; }

/* === ESTILOS OVERLAY PARTICIPAR === */
.participar-container {
  position: fixed;
  width: 100vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-end;
  pointer-events: none;
}

/* Desktop: ocupa todo el ancho y centrado verticalmente */
@media (min-width: 641px) {
  .participar-container {
    height: 100vh;
    bottom: 0;
    left: 0;
    justify-content: center;
    padding: 20px;
  }
}

/* Mobile: ocupa mitad inferior */
@media (max-width: 640px) {
  .participar-container {
    height: 50vh;
    bottom: 0;
    left: 0;
    padding: 15px;
  }
}

.participar-overlay-content {
  width: 100%;
  max-width: 1200px;
  display: flex;
  flex-direction: column;
  align-items: center;
  pointer-events: auto;
}

.question-bar {
  width: 100%;
  max-width: 1200px;
  min-height: 52px;
  margin: 0 auto 2vh auto;
  background: linear-gradient(90deg, #111b2b 80%, #004563 100%);
  border-radius: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.74rem;
  font-weight: 700;
  text-align: center;
  color: #fff;
  text-shadow: 0 0 10px #00f0ff;
  box-shadow: 0 0 15px #19faffb9, 0 0 1px #fff6;
  border: none;
  letter-spacing: 1.2px;
  font-family: 'Orbitron', Arial, sans-serif;
  padding: 12px 20px;
}

.answers-row {
  width: 100%;
  max-width: 1200px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px 28px;
  margin-bottom: 1.3vh;
}

.option-box {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  font-size: 1.32rem;
  background: linear-gradient(90deg, #0b1530 75%, #12375c 100%);
  color: #fff;
  padding: 16px 28px;
  border-radius: 23px;
  box-shadow: 0 0 14px #19faffaa, 0 0 1px #fff8;
  font-family: 'Orbitron', Arial, sans-serif;
  font-weight: 600;
  position: relative;
  border: none;
  min-height: 46px;
  transition: background 0.12s, box-shadow 0.14s, color 0.15s, transform 0.16s;
  letter-spacing: 1px;
  cursor: pointer;
}

.option-box .opt-label {
  font-size: 1.62rem;
  color: #36ffd0;
  margin-right: 19px;
  font-weight: 900;
  text-shadow: 0 0 8px #1affd2b5;
}

.option-box .opt-text {
  flex: 1;
  font-size: 1.04em;
}

.option-box.selected,
.option-box:hover {
  background: linear-gradient(90deg, #ffe47a 80%, #e6be2f 100%);
  color: #333;
  transform: scale(1.07);
  box-shadow: 0 0 22px #ffe47a99, 0 0 5px #e6be2f77;
  z-index: 1;
}

@keyframes flash-green {
  0%, 100% { background: linear-gradient(90deg, #0b1530 75%, #12375c 100%); color: #fff; }
  25%, 75% { background: linear-gradient(90deg, #13ff79 70%, #07ce5e 110%); color: #fff; }
  50% { background: linear-gradient(90deg, #0b1530 75%, #12375c 100%); color: #fff; }
}

.option-box.correct-flash {
  animation: flash-green 0.65s 2;
}

.option-box.correct-final {
  background: linear-gradient(90deg, #13ff79 80%, #07ce5e 100%);
  color: #003e18;
  box-shadow: 0 0 33px #15ff99c9, 0 0 5px #07ce5e99;
}

@keyframes flash-red {
  0%, 100% { background: linear-gradient(90deg, #0b1530 75%, #12375c 100%); color: #fff; }
  25%, 75% { background: linear-gradient(90deg, #ff3f34 80%, #d00015 100%); color: #fff; }
  50% { background: linear-gradient(90deg, #0b1530 75%, #12375c 100%); color: #fff; }
}

.option-box.incorrect-flash {
  animation: flash-red 0.65s 2;
}

.option-box.incorrect-final {
  background: linear-gradient(90deg, #ff3f34 80%, #d00015 100%);
  color: #fff;
  box-shadow: 0 0 26px #ff4a4a99;
}

/* Mensaje de respuesta profesional */
.respuesta-msg-top {
  text-align: center;
  margin-bottom: 15px;
  padding: 10px 20px;
  border-radius: 12px;
  font-size: 1.1rem;
  font-weight: 700;
  font-family: 'Orbitron', Arial, sans-serif;
  letter-spacing: 0.5px;
  box-shadow: 0 0 12px rgba(0, 240, 255, 0.3);
}

.respuesta-msg-top.success {
  background: rgba(19, 255, 121, 0.15);
  border: 2px solid #13ff79;
  color: #13ff79;
  text-shadow: 0 0 8px rgba(19, 255, 121, 0.5);
}

.respuesta-msg-top.error {
  background: rgba(255, 63, 52, 0.15);
  border: 2px solid #ff3f34;
  color: #ff3f34;
  text-shadow: 0 0 8px rgba(255, 63, 52, 0.5);
}

.respuesta-msg-top.warning {
  background: rgba(255, 228, 122, 0.15);
  border: 2px solid #ffe47a;
  color: #ffe47a;
  text-shadow: 0 0 8px rgba(255, 228, 122, 0.5);
}

/* Opciones bloqueadas (Solo yo) */
.option-box.locked {
  opacity: 0.35 !important;
  filter: grayscale(1) !important;
  pointer-events: none !important;
  cursor: not-allowed !important;
  background: rgba(11, 21, 48, 0.5) !important;
  border-color: #555 !important;
}

.option-box.locked:hover {
  transform: none !important;
  box-shadow: 0 0 14px #19faffaa, 0 0 1px #fff8 !important;
}

/* --- MOBILE Ajustes --- */
@media (max-width: 640px) {
  .question-bar {
    font-size: 1.2rem;
    padding: 10px 15px;
    min-height: 46px;
  }

  .answers-row {
    gap: 12px 16px;
  }

  .option-box {
    font-size: 1rem;
    padding: 12px 16px;
    min-height: 60px;
  }

  .option-box .opt-label {
    font-size: 1.3rem;
    margin-right: 12px;
  }

  .option-box .opt-text {
    font-size: 0.95em;
  }

  .respuesta-msg-top {
    font-size: 0.95rem;
    padding: 8px 15px;
  }
}

@media (max-width: 420px) {
  .question-bar {
    font-size: 1rem;
    padding: 8px 12px;
    min-height: 40px;
  }

  .option-box {
    font-size: 0.9rem;
    padding: 10px 12px;
    min-height: 54px;
  }

  .option-box .opt-label {
    font-size: 1.1rem;
    margin-right: 10px;
  }

  .option-box .opt-text {
    font-size: 0.85em;
  }
}

.bg-[#171c2e] {
  font-size: 1.15rem;
  padding: 2.5rem 1.2rem;
  border-radius: 1.1rem;
  max-width: 96vw;
}
.puntaje-top-container{
  position: fixed;
  top: max(env(safe-area-inset-top, 15px), 15px);
  right: max(env(safe-area-inset-right, 15px), 15px);
  z-index: 9999;
  width: auto;              /* ya no 340px fijos aquí */
  max-width: 98vw;          /* nunca más que el viewport */
  display: flex;
  justify-content: flex-end;
  box-sizing: border-box;
}


/* Card interior animable y contenida */
.puntaje-card{
  width: 340px;
  max-width: min(340px, 92vw);
  box-sizing: border-box;
  transform-origin: center; /* que escale desde el centro */
  will-change: transform;
}
.salir-btn{
  position: static;         /* deja de ser fixed */
  background: transparent;
  border: none;
  padding: 0;
  cursor: pointer;
  display: block;
  margin: 16px auto 0;      /* centrado debajo del contenido */
}
.salir-btn img {
    display: block;
    width: 60px; /* ajusta tamaño según necesites */
    height: auto;
    transition: transform 0.18s ease;
}
.salir-btn:hover img,
.salir-btn:focus img{
  transform: scale(1.05);  /* leve zoom a la imagen (opcional) */
  opacity: .95;            /* opcional */
}

.salir-btn:hover,
.salir-btn:focus{
  background: transparent !important;   /* SIN rojo */
  box-shadow: none !important;
  transform: none;                       /* no mueve el botón contenedor */
}
@media (max-width: 640px) {
    .salir-btn {
        left: 50%;
        bottom: 15px;
        transform: translateX(-50%);
    }
    .salir-btn img {
        width: 48px;
    }
}

@media (max-width: 640px){
  .puntaje-top-container{
    left: 50%;
    right: auto;
    transform: translateX(-50%);
    width: calc(100vw - 24px);   /* margen de 12px por lado */
    justify-content: center;
    padding: 0;
  }
  .puntaje-card{
    width: 100%;
    max-width: calc(100vw - 24px);
  }
}

/* Ajuste de animaciones: en desktop puede “rebotar” más, en mobile menos */
@keyframes puntaje-bounce{
  0%   { transform: scale(1); }
  25%  { transform: scale(1.12); }   /* un poco menos que 1.18 */
  60%  { transform: scale(0.99); }
  80%  { transform: scale(1.05); }
  100% { transform: scale(1); }
}
@keyframes puntaje-shake{
  0% { transform: translateX(0); }
  15% { transform: translateX(-6px); }
  30% { transform: translateX(5px); }
  45% { transform: translateX(-4px); }
  60% { transform: translateX(3px); }
  75% { transform: translateX(-2px); }
  90% { transform: translateX(1px); }
  100% { transform: translateX(0); }
}
/* Reutilizo tus clases, pero ahora se aplicarán a .puntaje-card */
.puntaje-anim-bounce{ animation: puntaje-bounce .55s cubic-bezier(.23,1.38,.55,.98); }
.puntaje-anim-shake{ animation: puntaje-shake .45s cubic-bezier(.45,1.38,.55,.98); }

/* Mobile: escala aún más conservadora para no “salirse” nunca */
@media (max-width: 640px){
  @keyframes puntaje-bounce{
    0%   { transform: scale(1); }
    25%  { transform: scale(1.05); }
    60%  { transform: scale(1); }
    80%  { transform: scale(1.03); }
    100% { transform: scale(1); }
  }
}
/* Fila bajo la tarjeta (mobile por defecto) */
.status-row{
  width: 100%;
  max-width: 980px;
  margin: 6px auto 12px;
  padding: 0 8px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}

/* MOBILE: anula los fixed y compáctalo */
@media (max-width: 640px){
  .status-row .participant-name-top,
  .status-row .puntaje-top-container{
    position: static !important;
    top: auto !important;
    left: auto !important;
    right: auto !important;
    transform: none !important;
    z-index: auto !important;
  }
  .participant-name-top{
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    font-size: .95rem;
    border-radius: 12px;
    max-width: 60vw;         /* evita que se coma el espacio del puntaje */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .participant-label{ margin-right: 6px; }
  .puntaje-top-container{ max-width: 40vw; }
  .puntaje-card{ width: auto; max-width: 100%; }
}

/* DESKTOP: tus posiciones fixed como las tenías */
@media (min-width: 641px){
  .participant-name-top{
    position: fixed !important;
    top: 15px !important;
    left: 20px !important;
    z-index: 9999 !important;
  }
  .puntaje-top-container{
    position: fixed !important;
    top: max(env(safe-area-inset-top, 15px), 15px) !important;
    right: max(env(safe-area-inset-right, 15px), 15px) !important;
    z-index: 9999 !important;
    display: flex;
    justify-content: flex-end;
  }
  .puntaje-card{
    width: 340px;
    max-width: min(340px, 92vw);
  }
  /* La fila no ocupa alto en desktop (porque vuelven a fixed) */
  .status-row{ height: 0; margin: 0; padding: 0; }
}
@media (max-width: 640px) {
  .status-row {
    flex-direction: column;  /* uno debajo del otro */
    align-items: center;     /* centrados horizontalmente */
    justify-content: center;
    gap: 6px;                 /* espacio mínimo entre ellos */
  }
@media (max-width: 640px) {
  .status-row {
    flex-direction: column;  /* uno debajo del otro */
    align-items: center;     /* centrado horizontal */
    justify-content: center;
    gap: 6px;
  }

  .puntaje-top-container {
    margin: 0 auto !important;  /* fuerza centrado horizontal */
    display: flex;
    justify-content: center;
    width: 100%;                 /* que ocupe todo el ancho disponible */
  }
}

}
/* ==== FIX CENTRADO PUNTAJE (solo móvil) ==== */
@media (max-width: 640px){
  .status-row{
    flex-direction: column;
    align-items: center;      /* centra ambos bloques */
    justify-content: center;
    gap: 6px;                 /* padding mínimo entre ellos */
    padding: 0;
  }

  .status-row .participant-name-top,
  .status-row .puntaje-top-container{
    position: static !important;
    left: auto !important;
    right: auto !important;
    transform: none !important;
    width: auto;              /* no forzar 100% */
    margin: 0 auto;           /* centrado */
  }

  /* >>> El fix clave: centrar el contenido del contenedor de puntaje */
  .puntaje-top-container{
    display: flex;
    justify-content: center !important;   /* sobrescribe el flex-end base */
    align-items: center;
    max-width: none !important;           /* anula el 40vw */
    width: 100%;
    padding: 0;
    margin: 0 auto !important;
  }

  .puntaje-card{
    width: auto;
    max-width: 92vw;                      /* contenido contenido y centrado */
    margin: 0 auto;
  }
}
@media (max-width: 640px){
  #main-question-box{
    margin-top: clamp(16px, 20vh, 22vh) !important; /* empuja hacia abajo */
  }
}
/* ===== Mensajes arriba del contenedor de pregunta (solo móvil) ===== */
@media (max-width: 640px){
  /* El contenedor pasa a ser el ancla para posicionar el msg */
  #main-question-box{
    position: relative;
  }

  /* Mueve el mensaje por encima, centrado y contenido */
  #respuesta-msg{
    position: absolute;
    top: 0;                          /* anclado al borde superior del contenedor */
    left: 50%;
    transform: translate(-50%, calc(-100% - 10px)); /* justo encima con 10px de separación */
    width: min(560px, 92vw);         /* nunca se corta a la derecha */
    margin: 0;                       /* sin márgenes extra */
    z-index: 2000;                   /* por encima de la caja de pregunta */
    text-align: center;
  }

  /* Si tenés otras alertas similares, opcional: */
  .alert{
    position: absolute;
    top: 0;
    left: 50%;
    transform: translate(-50%, calc(-100% - 10px));
    width: min(560px, 92vw);
    margin: 0;
    z-index: 2000;
    text-align: center;
  }
}
  </style>


<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>
<script>
window.Pusher = Pusher;
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: '{{ env('PUSHER_APP_KEY') }}',
  cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
  forceTLS: true
});

// ============================================
// SCRIPT CONSOLIDADO PARA participar.blade.php
// Reemplaza AMBOS scripts @if(session('participant_session_id'))
// ============================================

document.addEventListener('DOMContentLoaded', function() {
  // ===== CONFIGURACIÓN INICIAL =====
  const form = document.getElementById('participar-form');
  const msg = document.getElementById('respuesta-msg');
  const main = document.getElementById('main-question-box');
  let enviado = false;
  let yaRespondio = null;
  let lastQuestionId = form ? form.querySelector('input[name="question_id"]').value : null;

  // ===== FUNCIONES DE UI =====
  function limpiarSeleccionUI() {
    document.querySelectorAll('.option-box').forEach(btn => {
      btn.classList.remove('selected', 'correct-flash', 'correct-final', 'incorrect-flash', 'incorrect-final', 'locked');
    });
    if (msg) {
      msg.style.display = 'none';
      msg.className = 'respuesta-msg-top';
    }
  }

  function marcarSeleccionUI(label) {
    document.querySelectorAll('.option-box').forEach(btn => {
      if (btn.getAttribute('data-label') === label) {
        btn.classList.add('selected');
      } else {
        btn.classList.remove('selected');
      }
    });
    if (msg) {
      msg.style.display = 'block';
      msg.className = 'respuesta-msg-top success';
      msg.innerHTML = 'Respuesta enviada. Esperando resultado...';
    }
  }

  function marcarCorrecta(label) {
    const btn = document.querySelector(`.option-box[data-label="${label}"]`);
    if (btn) {
      btn.classList.add('correct-flash');
      setTimeout(() => {
        btn.classList.remove('correct-flash');
        btn.classList.add('correct-final');
      }, 1300);
    }
    if (msg) {
      msg.style.display = 'block';
      msg.className = 'respuesta-msg-top success';
      msg.innerHTML = 'RESPUESTA CORRECTA';
    }
  }

  function marcarIncorrecta(label) {
    const btn = document.querySelector(`.option-box[data-label="${label}"]`);
    if (btn) {
      btn.classList.add('incorrect-flash');
      setTimeout(() => {
        btn.classList.remove('incorrect-flash');
        btn.classList.add('incorrect-final');
      }, 1300);
    }
    if (msg) {
      msg.style.display = 'block';
      msg.className = 'respuesta-msg-top error';
      msg.innerHTML = 'RESPUESTA INCORRECTA';
    }
  }

  // ===== MANEJO DE RESPUESTAS =====
  function handleOptionClick() {
    if (enviado) return;
    const selectedLabel = this.getAttribute('data-label');
    const questionId = form.querySelector('input[name="question_id"]').value;
    
    limpiarSeleccionUI();
    marcarSeleccionUI(selectedLabel);
    
    fetch("{{ route('participar.enviar') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
        },
        body: new URLSearchParams({
            option_label: selectedLabel,
            question_id: questionId,
            _token: form.querySelector('input[name="_token"]').value
        })
    })
    .then(response => response.ok ? response.text() : Promise.reject(response))
    .then(data => {
        enviado = true;
        yaRespondio = selectedLabel;
        console.log('[RESPUESTA] Enviada correctamente:', selectedLabel);
    })
    .catch(err => {
        enviado = false;
        console.error('[ERROR] Al enviar respuesta:', err);
        if (msg) {
            msg.style.display = 'block';
            msg.className = 'respuesta-msg-top error';
            msg.innerHTML = 'Error al enviar la respuesta';
        }
        limpiarSeleccionUI();
    });
  }

  // ===== RENDERIZADO DE PREGUNTAS =====
function renderPregunta(data) {
    console.log('[PREGUNTA] Renderizando:', data);

    const mainBox = document.getElementById('main-question-box');
    if (mainBox) mainBox.style.display = 'block';

    if (form) {
      form.querySelector('input[name="question_id"]').value = data.pregunta_id || '';
    }

    // ✅ DETECTAR SI ES "AHORA YO" (Solo invitado)
    const isAhoraYo = data.disable_public_answers === true
        || (data.special_indicator && data.special_indicator.toLowerCase().includes('solo yo'))
        || (data.special_indicator && data.special_indicator.toLowerCase().includes('ahora yo'));

    const answersRow = form ? form.querySelector('.answers-row') : null;

    // ✅ RENDERIZAR OPCIONES (SIEMPRE, pero deshabilitadas si es Ahora Yo)
    if (answersRow) {
      answersRow.innerHTML = '';
      (data.opciones || []).forEach(op => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.setAttribute('data-label', op.label);
        btn.className = 'option-box';

        if (isAhoraYo) {
            btn.classList.add('locked');
            btn.disabled = true;
        }

        btn.innerHTML = `
            <span class="opt-label">${op.label}</span>
            <span class="opt-text">${op.texto}</span>
        `;

        if (!isAhoraYo) {
            btn.addEventListener('click', handleOptionClick);
        }

        answersRow.appendChild(btn);
      });
    }

    const questionBar = document.querySelector('.question-bar');
    if (questionBar) questionBar.textContent = data.pregunta || '';

    enviado = false;
    yaRespondio = null;
    lastQuestionId = data.pregunta_id;
    limpiarSeleccionUI();

    // ✅ MOSTRAR MENSAJE SUTIL ARRIBA DE LAS OPCIONES
    if (isAhoraYo) {
        if (msg) {
            msg.style.display = 'block';
            msg.className = 'respuesta-msg-top warning';
            msg.innerHTML = 'Solo el invitado puede responder';
        }
        console.log('[AHORA YO] Opciones deshabilitadas para el público');
    } else {
        if (msg) msg.style.display = 'none';
    }

    if (form) form.style.display = 'block';

    const msgNoQuestion = document.getElementById('msg-no-question');
    if (msgNoQuestion) msgNoQuestion.style.display = 'none';
}

  // ===== INICIALIZACIÓN DEL FORMULARIO =====
  limpiarSeleccionUI();
  if (form) {
    form.querySelectorAll('.option-box').forEach(btn => {
      btn.addEventListener('click', handleOptionClick);
    });
    form.addEventListener('submit', function(e) { e.preventDefault(); });
  }

  // ===== PUSHER/ECHO PARA PREGUNTAS =====
  if (window.Echo) {
    console.log('[ECHO] Inicializando canales...');

    window.Echo.channel('cuanto-sabe-overlay')
      .listen('.nueva-pregunta', function(e) {
        console.log('[PUSHER] Nueva pregunta recibida:', e);
        const data = e.data || e;
        if (data.opciones && data.opciones.length > 0) {
          renderPregunta(data);
        }
      })
      .listen('.revelar-respuesta', function(e) {
        console.log('[PUSHER] Revelar respuesta:', e);
        let data = e.data || e;
        let qid = form ? form.querySelector('input[name="question_id"]').value : null;
        if (String(data.pregunta_id) !== String(qid)) return;
        
        let respondida = yaRespondio;
        if (!respondida) {
          marcarCorrecta(data.label_correcto);
          return;
        }
        
        if (respondida === data.label_correcto) {
          marcarCorrecta(data.label_correcto);
          return;
        }
        
        marcarIncorrecta(respondida);

        setTimeout(() => {
          marcarCorrecta(data.label_correcto);
          if (msg) {
            msg.className = 'respuesta-msg-top success';
            msg.innerHTML = 'La respuesta correcta era: ' + data.label_correcto;
          }
        }, 5000);
      })
      .listen('.overlay-reset', function() {
        console.log('[PUSHER] Reset del overlay');
        const mainBox = document.getElementById('main-question-box');
        if (mainBox) mainBox.style.display = 'none';
        if (form) {
          form.style.display = 'none';
          form.querySelector('input[name="question_id"]').value = '';
          const answersRow = form.querySelector('.answers-row');
          if (answersRow) answersRow.innerHTML = '';
        }

        const questionBar = document.querySelector('.question-bar');
        if (questionBar) questionBar.textContent = '';
        if (msg) msg.style.display = 'none';

        let msgNoQuestion = document.getElementById('msg-no-question');
        if (!msgNoQuestion && document.getElementById('pantalla')) {
          msgNoQuestion = document.createElement('div');
          msgNoQuestion.id = 'msg-no-question';
          msgNoQuestion.className = 'bg-[#171c2e] rounded-[2.5rem] px-12 py-12 shadow-2xl border-4 border-[#01e3fd4d] text-2xl text-white font-semibold text-center max-w-2xl mx-auto tracking-wide neon-glow';
          document.getElementById('pantalla').appendChild(msgNoQuestion);
        }
        if (msgNoQuestion) {
          msgNoQuestion.innerHTML = `
            No hay pregunta activa.<br><br>
            Esperá que el host envíe una nueva pregunta...
          `;
          msgNoQuestion.style.display = 'block';
        }
      });
  }

  // ===== PUSHER/ECHO PARA PUNTAJE (SOLO SI HAY SESIÓN) =====
  @if(session('participant_session_id'))
  (function() {
    window.PARTICIPANT_SESSION_ID = '{{ session('participant_session_id') }}';
    console.log('[PUNTAJE] Participant session ID:', window.PARTICIPANT_SESSION_ID);

    if (!window.Echo) {
      console.error('[PUNTAJE] Echo no está disponible');
      return;
    }

    if (!window.PARTICIPANT_SESSION_ID) {
      console.error('[PUNTAJE] PARTICIPANT_SESSION_ID no está disponible');
      return;
    }

    const canal = 'puntaje.' + window.PARTICIPANT_SESSION_ID;
    console.log('[PUNTAJE] Suscribiendo a canal:', canal);

    window.Echo.channel(canal)
      .listen('.PuntajeActualizado', function(e) {
        console.log('[PUNTAJE] Evento recibido:', e);

        const nuevoPuntaje = (typeof e.puntaje === 'object') ? e.puntaje.total : e.puntaje;
        const el = document.getElementById('puntaje-num');
        const container = document.getElementById('puntaje-card'); // ✅ CAMBIADO: Ahora apunta al card interno

        if (!el) {
          console.error('[PUNTAJE] Elemento #puntaje-num no encontrado');
          return;
        }

        const puntajePrevio = parseInt(el.textContent) || 0;
        el.textContent = nuevoPuntaje;
        console.log('[PUNTAJE] Actualizado de', puntajePrevio, 'a', nuevoPuntaje);

        // ✅ ANIMACIÓN CORREGIDA: Aplicada al card que existe
        if (container) {
          container.classList.remove('puntaje-anim-bounce', 'puntaje-anim-shake');
          
          // Forzar reflow para reiniciar animación
          void container.offsetWidth;
          
          setTimeout(function() {
            if (nuevoPuntaje > puntajePrevio) {
              container.classList.add('puntaje-anim-bounce');
              console.log('[PUNTAJE] Animación bounce aplicada');
              
              // Cleanup simple con setTimeout
              setTimeout(function() {
                container.classList.remove('puntaje-anim-bounce');
              }, 550); // Duración de la animación
              
            } else if (nuevoPuntaje < puntajePrevio) {
              container.classList.add('puntaje-anim-shake');
              console.log('[PUNTAJE] Animación shake aplicada');
              
              // Cleanup simple con setTimeout
              setTimeout(function() {
                container.classList.remove('puntaje-anim-shake');
              }, 450); // Duración de la animación
            }
          }, 15);
        } else {
          console.warn('[PUNTAJE] Container #puntaje-card no encontrado para animación');
        }
      });
  })();
  @endif
});

// ===== MODAL DE SALIDA =====
function abrirModalSalir() {
    document.getElementById('modalSalir').style.display = 'flex';
}

function cerrarModalSalir() {
    document.getElementById('modalSalir').style.display = 'none';
}

function confirmarSalidaFinal() {
    cerrarModalSalir();
    document.getElementById('salir-form').submit();
}

window.addEventListener('keydown', function(e){
    if(e.key === 'Escape') cerrarModalSalir();
});

const modalSalir = document.getElementById('modalSalir');
if (modalSalir) {
  modalSalir.addEventListener('click', function(e) {
      if (e.target === this) cerrarModalSalir();
  });
}
</script>

@endif
