@extends('layouts.app')

@section('content')
@php
  $hideFooter = true;
@endphp

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


<div id="pantalla" class="flex flex-col items-center justify-end px-3 py-4">

  <!-- Contenedor pregunta -->
  <div id="main-question-box" class="w-full max-w-2xl" style="{{ (!isset($pregunta['pregunta']) || !$pregunta['pregunta']) ? 'display:none;' : '' }}">
    <div id="respuesta-msg" class="text-center mb-4 font-extrabold text-lg respuesta-msg" style="display:none;">
      <!-- Se muestra solo desde JS -->
    </div>

    <div class="question-box bg-gradient-to-r from-[#001a35ee] to-[#072954ea] border-4 border-[#01e3fd66] rounded-2xl shadow-[0_4px_32px_#020d2455] mb-7 px-7 py-6 flex items-center justify-center">
      <h2 class="question-title text-2xl md:text-3xl font-extrabold text-white text-center tracking-wide m-0 p-0 leading-tight">
        {{ $pregunta['pregunta'] ?? '' }}
      </h2>
    </div>

    <form id="participar-form" method="POST" class="space-y-0" autocomplete="off" style="{{ isset($sinPregunta) && $sinPregunta ? 'display:none;' : '' }}">
      @csrf
      <input type="hidden" name="question_id" value="{{ $pregunta['pregunta_id'] ?? '' }}">

      <div class="options-grid grid grid-cols-2 grid-rows-2 gap-6 w-full max-w-[520px] mx-auto" style="max-height:70vh;">
        @if(isset($pregunta['opciones']))
          @foreach($pregunta['opciones'] as $op)
            <button
              type="button"
              data-label="{{ $op['label'] }}"
              class="option-card group flex flex-col items-center justify-center h-[92px] md:h-[110px] w-full bg-[#051e38fa] border-[3px] border-[#00f0ff44] text-[#d7f6ff] font-bold text-xl md:text-2xl rounded-2xl transition-all duration-200 ease-out shadow-lg hover:bg-[#00f0ff] hover:text-[#002640] hover:scale-105 focus:ring-4 focus:ring-[#00f0ff77] select-none outline-none tracking-wide neon-glow-btn"
            >
              <span class="block text-3xl md:text-4xl font-black mb-2 group-hover:text-[#ff1fff] transition">{{ $op['label'] }}</span>
              <span class="block text-center w-full font-bold text-lg md:text-2xl">{{ $op['texto'] }}</span>
              <span class="selected-animation absolute inset-0 opacity-0 pointer-events-none"></span>
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

.guest-card-wrap {
  pointer-events: none;
  width: 100%;
  max-width: 100vw;
  overflow: visible;
  box-sizing: border-box;
}

.guest-card {
  pointer-events: auto;
  background: linear-gradient(135deg, rgba(4,38,78,0.82) 0%, rgba(0,52,94,0.82) 100%);
  border: 4px solid #00f0ff66;
  border-radius: 1.6rem;
  box-shadow: 0 8px 40px #012b4970, 0 0 18px #00f0ff33;
  padding: 20px 28px;
  min-width: 280px;
  max-width: min(92vw, 640px);
  width: 100%;
  text-align: center;
  backdrop-filter: blur(3px);
  box-sizing: border-box;
  overflow: visible;
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
  .guest-card {
    padding: 14px 16px;
    border-width: 3px;
    max-width: calc(100vw - 20px);
    min-width: unset;
  }
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
    max-width: calc(100vw - 40px);
    box-sizing: border-box;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
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
  height: 100vh !important;
  min-height: 100vh !important;
  width: 100vw !important;
  overflow: hidden !important;
  overscroll-behavior: none;
  touch-action: manipulation;
  box-sizing: border-box;
  margin: 0;
  padding: 0;

  background:
    radial-gradient(circle at 52% 44%, #1b0362 0%, #030015 95%) 0 0/100vw 100vh no-repeat,
    url('/images/CS.png') center center/auto 80vh no-repeat;
  background-color: #1b0362;
  color: #00f0ff;
}

*, *::before, *::after {
  box-sizing: border-box;
}

body::before { display: none !important; }

/* === CONTENEDOR PRINCIPAL CENTRADO EN MITAD INFERIOR === */
#pantalla {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  width: 100%;
  max-height: 100vh;
  max-width: 100vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-end;
  padding: 15px;
  padding-bottom: 20px;
  overflow: visible;
  box-sizing: border-box;
  pointer-events: none;
}

#pantalla > * {
  pointer-events: auto;
}

.neon-glow { text-shadow: 0 0 16px #00e8fc, 0 0 6px #fff3; }
.drop-shadow-neon { text-shadow: 0 0 12px #19ff8cbb, 0 0 3px #fff3; }
.neon-glow-btn { text-shadow: 0 0 6px #00e8fc99; letter-spacing: .03em; }

#main-question-box {
  width: 100%;
  max-width: min(672px, 95vw);
  box-sizing: border-box;
  overflow: visible;
}

.question-box {
  border-radius: 1.7rem;
  margin-bottom: 2.5rem;
  box-shadow: 0 2px 22px #012b4955, 0 0 2px #00f0ff22;
  background: linear-gradient(120deg, rgba(4,38,78,0.81) 77%, rgba(0,52,94,0.81) 100%);
  border-width: 4px;
  border-color: #00f0ff66;
  backdrop-filter: blur(2px);
  width: 100%;
  box-sizing: border-box;
}

/* GRILLA 2x2 */
.options-grid {
  width: 100%;
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-template-rows: 1fr 1fr;
  gap: 1.3rem;
  max-width: min(520px, 100%);
  margin-left: auto;
  margin-right: auto;
  box-sizing: border-box;
  overflow: visible;
}

/* OPCIONES */
.option-card {
  min-height: 90px;
  height: 100%;
  width: 100%;
  max-width: 100%;
  border-radius: 1.1rem !important;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: rgba(5, 30, 56, 0.64) !important;
  border: 3px solid #00f0ff44;
  box-shadow: 0 3px 15px #012b497a, 0 0 2px #00f0ff22;
  color: #d7f6ff;
  font-family: 'Orbitron', Arial, sans-serif;
  transition: all .19s cubic-bezier(.44,0,.61,1.15);
  opacity: 1;
  backdrop-filter: blur(2px);
  padding: 1.1rem 0.4rem !important;
  text-align: center;
  box-sizing: border-box;
  overflow: visible;
  word-wrap: break-word;
}

.option-card span {
  display: block;
  width: 100%;
  padding-top: 0.15em;
  padding-bottom: 0.15em;
  line-height: 1.2;
}

.option-card:hover, .option-card:focus, .option-card.selected {
  background: #00f0ff !important;
  color: #002640 !important;
  border-color: #00f0ff !important;
  box-shadow: 0 0 30px #00e8fcaa, 0 0 16px #00f0ff;
  z-index: 2;
  opacity: 1 !important;
}

.option-card.selected .selected-animation { opacity: 1; }
.option-card .selected-animation {
  background: radial-gradient(circle, #00e8fc60 40%, transparent 80%);
  transition: opacity 0.23s;
  z-index: 1;
  border-radius: 1.2em;
}

.option-card.disabled {
  pointer-events: none;
  opacity: 0.60;
  filter: grayscale(0.85);
  transition: opacity 0.2s ease;
}

.option-card.correct {
  background: #19ff8c !important;
  color: #000 !important;
}

.option-card.incorrect {
  background: #ff4444 !important;
  color: #fff !important;
  animation: blink 0.8s steps(2, start) 2;
}

@keyframes blink { to { opacity: 0.5; } }

/* Opciones bloqueadas (Solo yo) */
.option-card.locked {
  opacity: 0.35 !important;
  filter: grayscale(1) !important;
  pointer-events: none !important;
  cursor: not-allowed !important;
  background-color: rgba(5, 30, 56, 0.3) !important;
  border-color: #555 !important;
}

.option-card.locked:hover {
  transform: none !important;
  box-shadow: none !important;
}

/* Mensajes profesionales */
.respuesta-msg {
  padding: 10px 20px;
  border-radius: 12px;
  font-family: 'Orbitron', Arial, sans-serif;
  letter-spacing: 0.5px;
  box-shadow: 0 0 12px rgba(0, 240, 255, 0.3);
  max-width: 100%;
  box-sizing: border-box;
  word-wrap: break-word;
  overflow-wrap: break-word;
}

.respuesta-msg.success {
  background: rgba(19, 255, 121, 0.15);
  border: 2px solid #13ff79;
  color: #13ff79 !important;
  text-shadow: 0 0 8px rgba(19, 255, 121, 0.5);
}

.respuesta-msg.error {
  background: rgba(255, 63, 52, 0.15);
  border: 2px solid #ff3f34;
  color: #ff3f34 !important;
  text-shadow: 0 0 8px rgba(255, 63, 52, 0.5);
}

.respuesta-msg.warning {
  background: rgba(255, 228, 122, 0.15);
  border: 2px solid #ffe47a;
  color: #ffe47a !important;
  text-shadow: 0 0 8px rgba(255, 228, 122, 0.5);
}

.question-title {
  font-size: 2rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 0;
  padding: 0;
  color: white !important;
}

/* --- MOBILE Ajustes --- */
@media (max-width: 640px) {
  #pantalla {
    padding: 10px;
    padding-bottom: 15px;
    max-height: none;
  }

  #main-question-box {
    max-width: calc(100vw - 20px);
    width: 100%;
  }

  .question-box {
    padding: 1rem 0.5rem !important;
    margin-bottom: 1.5rem !important;
    border-radius: 1rem !important;
    max-width: 100%;
  }

  .question-title {
    font-size: 1.12rem !important;
    padding: 0 0.3rem !important;
    word-break: break-word;
  }

  .options-grid {
    grid-template-columns: 1fr 1fr !important;
    grid-template-rows: 1fr 1fr !important;
    gap: 0.8rem !important;
    max-width: 100% !important;
    padding: 0 !important;
  }

  .option-card {
    min-height: 75px !important;
    height: 75px !important;
    font-size: 1.05rem !important;
    border-radius: 0.85rem !important;
    padding: 0.5rem 0.3rem !important;
    max-width: 100%;
  }

  .option-card span.block.text-3xl,
  .option-card span.block.text-4xl,
  .option-card span.block.text-center {
    font-size: 1.05rem !important;
  }

  .option-card span.block.text-lg,
  .option-card span.block.text-2xl {
    font-size: 0.93rem !important;
    word-break: break-word;
  }
}

@media (max-width: 420px) {
  #pantalla {
    padding: 8px;
    padding-bottom: 12px;
    max-height: none;
  }

  #main-question-box {
    max-width: calc(100vw - 16px);
  }

  .question-box {
    padding: 0.8rem 0.4rem !important;
  }

  .question-title {
    font-size: 0.98rem !important;
    line-height: 1.3 !important;
    padding: 0 0.2rem !important;
  }

  .options-grid {
    gap: 0.6rem !important;
  }

  .option-card {
    min-height: 54px !important;
    height: 54px !important;
    font-size: 0.93rem !important;
    border-radius: 0.65rem !important;
    padding: 0.4rem 0.2rem !important;
  }

  .option-card span {
    padding-top: 0.1em;
    padding-bottom: 0.1em;
  }

  .option-card span.block.text-3xl,
  .option-card span.block.text-4xl,
  .option-card span.block.text-center {
    font-size: 0.93rem !important;
  }

  .option-card span.block.text-lg,
  .option-card span.block.text-2xl {
    font-size: 0.80rem !important;
  }
}

.bg-[#171c2e] {
  font-size: 1.15rem;
  padding: 2.5rem 1.2rem;
  border-radius: 1.1rem;
  max-width: min(672px, 95vw);
  width: 100%;
  box-sizing: border-box;
}
.puntaje-top-container{
  position: fixed;
  top: max(env(safe-area-inset-top, 15px), 15px);
  right: max(env(safe-area-inset-right, 15px), 15px);
  z-index: 9999;
  width: auto;
  max-width: calc(100vw - 30px);
  display: flex;
  justify-content: flex-end;
  box-sizing: border-box;
  overflow: visible;
}

/* Card interior animable y contenida */
.puntaje-card{
  width: 340px;
  max-width: min(340px, calc(100vw - 30px));
  box-sizing: border-box;
  transform-origin: center;
  will-change: transform;
  overflow: visible;
}
.salir-btn{
  position: static;
  background: transparent;
  border: none;
  padding: 0;
  cursor: pointer;
  display: block;
  margin: 10px auto 0;
}

.salir-btn img {
    display: block;
    width: 40px;
    height: auto;
    transition: transform 0.18s ease;
}

.salir-btn:hover img,
.salir-btn:focus img{
  transform: scale(1.08);
  opacity: .95;
}

.salir-btn:hover,
.salir-btn:focus{
  background: transparent !important;
  box-shadow: none !important;
  transform: none;
}

@media (max-width: 640px) {
    .salir-btn img {
        width: 35px;
    }
}

@media (max-width: 640px){
  .puntaje-top-container{
    left: 50%;
    right: auto;
    transform: translateX(-50%);
    width: calc(100vw - 24px);
    justify-content: center;
    padding: 0;
    overflow: visible !important;
  }
  .puntaje-card{
    width: 100%;
    max-width: calc(100vw - 24px);
    overflow: visible !important;
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
  max-width: min(980px, 100vw);
  margin: 6px auto 12px;
  padding: 0 8px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  box-sizing: border-box;
  overflow: visible;
}

/* MOBILE: anula los fixed y compáctalo */
@media (max-width: 640px){
  .status-row {
    max-width: calc(100vw - 16px);
    padding: 0 4px;
    overflow: visible;
  }

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
    max-width: calc(55vw - 20px);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    box-sizing: border-box;
  }
  .participant-label{ margin-right: 6px; }
  .puntaje-top-container{
    max-width: calc(40vw - 10px);
    overflow: visible !important;
  }
  .puntaje-card{
    width: auto;
    max-width: 100%;
    overflow: visible !important;
  }
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
  .status-row{
    height: 0;
    margin: 0;
    padding: 0;
    overflow: visible;
  }
}

@media (max-width: 640px) {
  .status-row {
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    max-width: 100vw;
    overflow: visible;
  }

  .puntaje-top-container {
    margin: 0 auto !important;
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
    max-width: 92vw;
    margin: 0 auto;
    overflow: visible !important;
  }

  #respuesta-msg{
    position: static;
    width: 100%;
    max-width: min(560px, 92vw);
    margin: 0 auto 10px auto;
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
    document.querySelectorAll('.option-card').forEach(btn => {
      btn.classList.remove('selected', 'disabled', 'correct', 'incorrect', 'blinking', 'locked');
      btn.style.opacity = '1';
      btn.style.filter = 'none';
    });
    if (msg) {
      msg.style.display = 'none';
      msg.className = 'text-center mb-4 font-extrabold text-lg respuesta-msg';
    }
  }

  function marcarSeleccionUI(label) {
    document.querySelectorAll('.option-card').forEach(btn => {
      if (btn.getAttribute('data-label') === label) {
        btn.classList.add('selected');
        btn.classList.remove('disabled');
        btn.style.opacity = '1';
        btn.style.filter = 'none';
      } else {
        btn.classList.remove('selected');
        btn.classList.add('disabled');
        btn.style.opacity = '0.6';
        btn.style.filter = 'grayscale(0.85)';
      }
    });
    if (msg) {
      msg.style.display = 'block';
      msg.className = 'text-center mb-4 font-extrabold text-lg respuesta-msg success';
      msg.innerHTML = 'Respuesta enviada. Esperando resultado...';
    }
  }

  function marcarCorrecta(label) {
    document.querySelectorAll('.option-card').forEach(btn => {
      if (btn.getAttribute('data-label') === label) {
        btn.classList.add('correct');
        btn.classList.remove('incorrect', 'disabled');
        btn.style.opacity = '1';
        btn.style.filter = 'none';
      } else {
        btn.classList.remove('correct');
      }
    });
    if (msg) {
      msg.style.display = 'block';
      msg.className = 'text-center mb-4 font-extrabold text-lg respuesta-msg success';
      msg.innerHTML = 'RESPUESTA CORRECTA';
    }
  }

  function marcarIncorrecta(label) {
    document.querySelectorAll('.option-card').forEach(btn => {
      if (btn.getAttribute('data-label') === label) {
        btn.classList.add('incorrect', 'blinking');
        btn.classList.remove('correct');
      }
    });
    if (msg) {
      msg.style.display = 'block';
      msg.className = 'text-center mb-4 font-extrabold text-lg respuesta-msg error';
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
            msg.className = 'text-center mb-4 font-extrabold text-lg respuesta-msg error';
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

    const questionTitle = document.querySelector('.question-title');
    const optionsGrid = form ? form.querySelector('.options-grid') : null;

    // ✅ PRIMERO: Mostrar solo la categoría
    const categoria = data.categoria_nombre ? data.categoria_nombre.toUpperCase() : 'CATEGORÍA';
    if (questionTitle) questionTitle.textContent = categoria;

    // Ocultar opciones inicialmente
    if (optionsGrid) optionsGrid.style.display = 'none';

    enviado = false;
    yaRespondio = null;
    lastQuestionId = data.pregunta_id;
    limpiarSeleccionUI();

    // ✅ DESPUÉS DE 10 SEGUNDOS: Mostrar pregunta y opciones
    setTimeout(() => {
        // Mostrar la pregunta real
        if (questionTitle) questionTitle.textContent = data.pregunta || '';

        // Mostrar y renderizar opciones
        if (optionsGrid) {
          optionsGrid.style.display = 'grid';
          optionsGrid.innerHTML = '';
          (data.opciones || []).forEach(op => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.setAttribute('data-label', op.label);

            let classes = "option-card group relative flex flex-col items-center justify-center min-h-[108px] md:min-h-[138px] h-full bg-[#051e38fa] border-[3px] border-[#00f0ff44] text-[#d7f6ff] font-bold text-xl md:text-2xl rounded-2xl transition-all duration-200 ease-out shadow-lg select-none outline-none tracking-wide neon-glow-btn";

            if (isAhoraYo) {
                classes += " locked";
                btn.disabled = true;
            } else {
                classes += " hover:bg-[#00f0ff] hover:text-[#002640] hover:scale-105 focus:ring-4 focus:ring-[#00f0ff77]";
            }

            btn.className = classes;
            btn.innerHTML = `
                <span class="block text-3xl md:text-4xl font-black mb-2 group-hover:text-[#ff1fff] transition">${op.label}</span>
                <span class="block text-center w-full font-bold text-lg md:text-2xl">${op.texto}</span>
                <span class="selected-animation absolute inset-0 opacity-0 pointer-events-none"></span>
            `;

            if (!isAhoraYo) {
                btn.addEventListener('click', handleOptionClick);
            }

            optionsGrid.appendChild(btn);
          });
        }
    }, 10000); // 10 segundos

    // ✅ MOSTRAR MENSAJE SUTIL ARRIBA DE LAS OPCIONES
    if (isAhoraYo) {
        if (msg) {
            msg.style.display = 'block';
            msg.className = 'text-center mb-4 font-extrabold text-lg respuesta-msg warning';
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
    form.querySelectorAll('.option-card').forEach(btn => {
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
            msg.className = 'text-center mb-4 font-extrabold text-lg respuesta-msg success';
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
          const optionsGrid = form.querySelector('.options-grid');
          if (optionsGrid) optionsGrid.innerHTML = '';
        }

        const questionTitle = document.querySelector('.question-title');
        if (questionTitle) questionTitle.textContent = '';
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

@endsection
