<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Jugar Demo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Rajdhani:wght@700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; }

    :root{
      --bg1:#1b0362; --bg2:#030015;
      --neon:#00f0ff; --neon-soft:#00e8fcaa;
      --ok:#19ff8c; --bad:#ff4444; --ink:#d7f6ff;
      --panel:#051e38fa; --border:#01e3fd66;
      --blue-base:#153364; --blue-vibe:#2d60a6; --blue-shadow:#193a68;
      --green-neon:#22fa68; --green-text:#eaffdb; --winner:#2987d8; --drop:#0e1528ee;
      --z-top: 10000; --page-pad: clamp(12px, 3vw, 28px); --maxw: 1400px;
    }
    html, body {
      margin:0; padding:0; width:100vw; height:100vh; overflow:hidden;
      background: radial-gradient(circle at 52% 44%, var(--bg1) 0%, var(--bg2) 95%) 0 0/100vw 100vh no-repeat;
      color:#fff; font-family:'Orbitron', Arial, sans-serif;
    }
    body { display:grid; place-items:center; }

    @keyframes fadeUpIn { from{opacity:0; transform:translateY(20px) scale(.98);} to{opacity:1; transform:translateY(0) scale(1);} }
    @keyframes popIn { from{opacity:0; transform:scale(.92);} to{opacity:1; transform:scale(1);} }
    .anim-in-up { animation: fadeUpIn .45s cubic-bezier(.39,.58,.57,1.02) both; }
    .anim-pop { animation: popIn .28s ease-out both; }

    .overlay-content, #ruleta-holder {
      opacity:1; transform:translateY(0);
      transition:.55s cubic-bezier(.39,.58,.57,1.02);
      will-change: opacity, transform;
    }
    .overlay-content.hide-down { opacity:0; transform:translateY(80px); pointer-events:none; }
    .overlay-content.show-up   { opacity:1; transform:translateY(0); }
    #ruleta-holder.hide-up     { opacity:0; transform:translateY(-90px); pointer-events:none; }
    #ruleta-holder.show-down   { opacity:1; transform:translateY(0); }

    /* Puntaje compacto */
/* Posición del bloque */
.puntaje-top-container{
  position: fixed;
  top: max(env(safe-area-inset-top, 15px), 15px);
  right: max(env(safe-area-inset-right, 15px), 15px);
  z-index: 9999;
  display: flex;
  justify-content: flex-end;
  width: auto;
}

/* Tarjeta */
.puntaje-top-container .puntaje-card{
  background: linear-gradient(90deg, #001a35ee 0%, #072954ea 100%);
  border: 4px solid #01e3fd66;
  border-radius: 1.5rem;
  box-shadow: 0 4px 32px #020d2455;
  padding: 0.8rem 1.3rem 0.8rem 1.1rem;
  min-width: 120px;
  max-width: 340px;
  width: fit-content;
  display: flex;
  align-items: center;
  gap: 1.1rem;
  text-align: left;

  transform-origin: center;   /* para las animaciones de rebote/temblor */
  will-change: transform;
}

/* Etiqueta "Puntaje:" */
.puntaje-top-container .puntaje-card > span:first-child{
  color: #00f0ff;
  display: flex;
  align-items: center;
  font-size: 1.28rem;
  font-weight: bold;
  text-shadow: 0 0 10px #00e8fc, 0 0 4px #fff2;
  letter-spacing: 0.02em;
  white-space: nowrap;
}

/* Número (sirve tanto si usas #score-num como #puntaje-num) */
#score-num, #puntaje-num{
  color: #19ff8c;
  font-size: 2.25rem;
  font-weight: 900;
  line-height: 1;
  text-shadow: 0 0 12px #19ff8caa, 0 0 3px #fff3;
  margin-left: 0.2em;
  letter-spacing: 0.01em;
  white-space: nowrap;
}

/* Pastilla "ORO" (opcional) */
#gold-pill{
  background: #132742;
  border: 2px solid #01e3fd66;
  border-radius: 999px;
  padding: 4px 10px;
  color: #eaffdb;
  font-weight: 800;
  display: none; /* la mostrás con JS cuando corresponda */
}

/* Animaciones que activa tu JS */
@keyframes puntaje-bounce{
  0%{transform:scale(1)}25%{transform:scale(1.12)}60%{transform:scale(0.99)}
  80%{transform:scale(1.05)}100%{transform:scale(1)}
}
@keyframes puntaje-shake{
  0%{transform:translateX(0)}15%{transform:translateX(-6px)}30%{transform:translateX(5px)}
  45%{transform:translateX(-4px)}60%{transform:translateX(3px)}75%{transform:translateX(-2px)}
  90%{transform:translateX(1px)}100%{transform:translateX(0)}
}
.puntaje-anim-bounce{ animation: puntaje-bounce .55s cubic-bezier(.23,1.38,.55,.98); }
.puntaje-anim-shake { animation: puntaje-shake  .45s cubic-bezier(.45,1.38,.55,.98); }

/* Centrado en mobile (opcional) */
@media (max-width:640px){
  .puntaje-top-container{
    left: 50%;
    right: auto;
    transform: translateX(-50%);
  }
  .puntaje-top-container .puntaje-card{
    max-width: calc(100vw - 24px);
  }
}

    #gold-pill{ background:#132742; border:2px solid var(--border); border-radius:999px; padding:4px 10px; display:none;}

    /* ===== HUD: sonido + opciones (arriba-izquierda) ===== */
    .hud-left{
      position:fixed; top:max(env(safe-area-inset-top, 15px), 15px);
      left:max(env(safe-area-inset-left, 15px), 15px);
      display:flex; gap:12px; z-index: calc(var(--z-top) + 1);
      align-items:flex-start;
    }
    .hud-btn{
      width:48px; height:48px; border-radius:999px;
      background: radial-gradient(120px 120px at 55% 40%, #163056 0%, #0a1c33 70%);
      border:2px solid #00f0ff55;
      box-shadow: 0 0 18px #00e8fc55, inset 0 0 0 1px #012b49;
      display:grid; place-items:center; cursor:pointer;
      transition: transform .15s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease;
      color:#d7f6ff;
    }
    .hud-btn:hover{ transform: translateY(-1px) scale(1.03); box-shadow:0 0 22px #00e8fc88; border-color:#00f0ff88; }
    .hud-btn svg{ width:24px; height:24px; }
    .hud-back-btn{ color:#36d1ff; text-decoration:none; }
    .hud-back-btn:hover{ color:#00f0ff; }

    /* altavoz animado */
    #sound-btn .slash{ opacity:0; transform: scale(.6) rotate(0); transform-origin:center; transition: opacity .18s ease, transform .18s ease; }
    #sound-btn.muted .waves{ opacity:0; transition: opacity .18s ease; }
    #sound-btn.muted .slash{ opacity:1; transform: scale(1) rotate(6deg); }

    /* opciones (rueda + dropdown) */
    .opts-wrap{ position:relative; }
    #opts-btn svg{ transition: transform .33s ease; transform-origin:50% 50%; }
    #opts-btn.open svg{ transform: rotate(135deg); }
    .opts-menu{
      position:absolute; top:56px; left:0;
      background: linear-gradient(135deg, rgba(4,38,78,.95) 0%, rgba(0,52,94,.95) 100%);
      border:2px solid #00f0ff66; border-radius:14px;
      box-shadow:0 12px 40px #012b4966, 0 0 18px #00f0ff33;
      padding:8px; min-width:210px; opacity:0; transform: translateY(-6px) scale(.98);
      pointer-events:none; transition: opacity .18s ease, transform .18s ease;
      backdrop-filter: blur(2px);
    }
    .opts-menu.open{ opacity:1; transform: translateY(0) scale(1); pointer-events:auto; }
    .opts-menu button{
      width:100%; background:transparent; border:none;
      color:#e9f9ff; font-weight:900; letter-spacing:.02em;
      display:flex; align-items:center; gap:10px; cursor:pointer;
      padding:10px 10px; border-radius:10px;
      transition: background .12s ease, transform .1s ease;
      text-align:left;
    }
    .opts-menu button:hover{ background:#00f0ff22; transform: translateX(2px); }
    .opts-menu svg{ width:18px; height:18px; flex:0 0 18px; }

    /* Pantallas */
    .screen { position:fixed; inset:0; display:grid; place-items:center;
      background: radial-gradient(circle at 52% 44%, #05001a 0%, #020012 95%);
      z-index: calc(var(--z-top) + 2);
    }
    .hidden { display:none !important; }
    .intro-card{
      width:min(92vw, 560px);
      background: linear-gradient(135deg, rgba(4,38,78,0.82) 0%, rgba(0,52,94,0.82) 100%);
      border: 4px solid #00f0ff66; border-radius: 1.6rem;
      box-shadow: 0 8px 40px #012b4970, 0 0 18px #00f0ff33;
      padding: 28px 26px; text-align:center; animation: fadeUpIn .45s cubic-bezier(.39,.58,.57,1.02) both;
    }
    .intro-title{ font-size:1.6rem; font-weight:900; margin:0 0 14px; color:#36d1ff; text-shadow: 0 0 16px #00e8fc; }
    .intro-form{ width:100%; display:grid; grid-template-columns: 1fr; justify-items:center; gap:14px; }
    .intro-input{
      display:block; width: min(420px, 100%); margin:0 auto; padding:14px 16px;
      border-radius:12px; border:2px solid #00f0ff66; background:#0a1c33; color:#fff; font-size:1.1rem; font-weight:800; text-align:center;
      outline:none; box-shadow: inset 0 0 0 1px #012b49;
    }
    .intro-input:focus{ border-color:#00f0ff; box-shadow: 0 0 0 3px #00f0ff33, inset 0 0 0 1px #012b49; }
    .intro-btn{
      width: min(220px, 80%); padding:12px 18px; border-radius:12px; border:2px solid var(--border);
      background:#132742; color:#fff; font-weight:900; cursor:pointer; box-shadow:0 0 10px var(--neon-soft);
      transition: transform .15s ease, background .15s ease, box-shadow .15s ease, border-color .15s ease;
    }
    .intro-btn:hover{ transform: translateY(-1px); background:#1b3963; border-color:#00f0ff; box-shadow:0 0 14px var(--neon-soft); }

    .countdown-card{ text-align:center; animation: popIn .35s ease both; }
    .cd-title{ font-size: clamp(1.4rem, 3vw, 2rem); font-weight:900; color:#ffe47a; text-shadow:0 0 16px #ffe47a99, 0 0 6px #fff3; margin-bottom:12px; }
    .cd-num{ font-size: clamp(3rem, 10vw, 8rem); font-weight:900; color:#36d1ff; text-shadow:0 0 16px #19faffaa, 0 0 6px #fff3; animation: popIn .35s ease both; }

    /* Escena */
    .stage { position:relative; width:100vw; height:100vh; display:grid; place-items:center; }

    /* Tips arriba de la ruleta */
    .tips-top{ width:100vw; max-width:var(--maxw);
      padding: 8px var(--page-pad) 0 var(--page-pad); box-sizing:border-box;
      display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin-bottom:12px;
    }
    .tips-top span{ background:#0a233e; border:2px solid var(--border); color:#fff; border-radius:999px; padding:6px 12px; font-weight:900; }

    /* Ruleta */
    #ruleta-holder { display:flex; flex-direction:column; align-items:center; justify-content:flex-start; gap:14px; width:100%; }
    #ruleta-container { position:relative; width:440px; height:440px; pointer-events:auto; }
    #ruleta-svg { width:440px; height:440px; display:block; filter: drop-shadow(0 0 18px var(--drop)); }
    #flecha-roja { position:absolute; left:50%; top:-10px; transform:translateX(-50%); z-index:20; width:81.6px; height:57.8px; pointer-events:none; }
    #spin-btn{
      position:absolute; left:50%; top:50%; transform:translate(-50%,-50%);
      width:85px; height:85px; border-radius:50%;
      border:2.7px solid var(--drop); background:rgb(12,42,74);
      z-index:21; padding:0; display:flex; align-items:center; justify-content:center;
      box-shadow:0 0 26px 9px #2d60a666, 0 7px 20px #181b3f88, 0 1px 3px #181b3f66, inset 0 0 0 1.2px #153364;
      cursor:pointer; transition: box-shadow .28s, background .18s;
    }
    #spin-btn:hover{ box-shadow:0 0 38px 14px #2987d888, 0 0 17px var(--neon), 0 0 13px #ffe47a44, 0 6px 20px #0a132bcc; background:#205093; }
    #spin-btn img{ width:100%; height:100%; border-radius:50%; object-fit:contain; background:#181b3f; }

    /* Preguntas full-width */
    .overlay-content { width:100vw; display:flex; flex-direction:column; align-items:center; }
    #pantalla { width:100vw; padding: 0 var(--page-pad) var(--page-pad); box-sizing:border-box; display:flex; flex-direction:column; align-items:center; justify-content:flex-start; }
    #main-question-box { width:100%; max-width:var(--maxw); }

    .question-box {
      width:100%; border-radius: 1.7rem; margin-bottom: 1.4rem;
      background: linear-gradient(120deg, rgba(4,38,78,0.81) 77%, rgba(0,52,94,0.81) 100%);
      border: 4px solid #00f0ff66; box-shadow: 0 2px 22px #012b4955, 0 0 2px #00f0ff22;
      padding: 1.25rem 1.5rem; backdrop-filter: blur(2px);
      display:flex; align-items:center; justify-content:center;
    }
    .question-title { font-size: clamp(1.2rem, 3vw, 1.9rem); font-weight: 900; text-align: center; margin: 0; color: var(--neon); text-shadow: 0 0 16px #00e8fc, 0 0 6px #fff3; }

    .options-grid {
      width:100%; display:grid; grid-template-columns: repeat(2, 1fr); grid-template-rows:1fr 1fr;
      gap: 1.15rem; max-width:var(--maxw); margin:0 auto;
    }
    .option-card {
      background: var(--panel); border: 3px solid #00f0ff44; color: var(--ink);
      font-weight: 900; border-radius: 1.2rem; min-height: 92px;
      display:flex; flex-direction:column; align-items:center; justify-content:center;
      text-align:center; cursor:pointer; box-shadow: 0 3px 15px #012b497a, 0 0 2px #00f0ff22;
      transition: all .19s cubic-bezier(.44,0,.61,1.15); backdrop-filter: blur(2px);
    }
    .option-card:hover { background:#00f0ff; color:#002640; border-color:#00f0ff; transform:scale(1.03); }
    .option-card.correct { background: var(--ok) !important; color:#001b0a !important; }
    .option-card.incorrect { background: var(--bad) !important; color:#fff !important; }
    #respuesta-msg{ margin-top: 8px; text-align:center; font-weight:900; display:none; text-shadow:0 0 12px #19ff8cbb, 0 0 3px #fff3; }

    @media (max-width:560px){
      #ruleta-container{ transform: scale(.9); transform-origin:center; }
      .options-grid{ gap:.9rem; }
      .option-card{ min-height:78px; }
    }

    /* Win & modal */
    .win-card{ width:min(92vw, 640px);
      background: linear-gradient(135deg, rgba(4,38,78,0.9) 0%, rgba(0,52,94,0.9) 100%);
      border: 4px solid #22fa68; border-radius: 1.6rem;
      box-shadow: 0 0 24px #22fa6899, 0 0 12px #00f0ff44;
      padding: 28px 26px; text-align:center; animation: fadeUpIn .45s cubic-bezier(.39,.58,.57,1.02) both;
    }
    .win-title{ font-size: clamp(1.8rem, 3vw, 2.4rem); color:#22fa68; font-weight:900; text-shadow:0 0 18px #22fa68aa; margin:0 0 8px; }
    .win-sub{ color:#e8f7ff; font-weight:800; margin-bottom:16px; }
    .win-actions{ display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
    .win-btn{ background:#132742; color:#fff; border:2px solid var(--border); border-radius:12px; padding:10px 16px; font-weight:900; cursor:pointer; }

    .modal{ position:fixed; inset:0; display:none; align-items:center; justify-content:center;
      background: rgba(6, 4, 18, .66); backdrop-filter: blur(3px); z-index: calc(var(--z-top) + 3); }
    .modal.open{ display:flex; animation: fadeUpIn .25s ease both; }
    .modal-card{
      width:min(92vw, 420px); background: linear-gradient(135deg, rgba(17,11,42,0.95) 0%, rgba(12,0,36,0.95) 100%);
      border:2px solid #ff444499; border-radius:18px; padding:24px 20px; text-align:center;
      box-shadow:0 0 30px #00f0ff55,0 0 50px #ff00ff22;
    }
    .modal-title{ font-size:1.4rem; color:#ffe27a; font-weight:900; margin-bottom:12px; text-shadow:0 0 10px #ffe27aaa; }
    .modal-text{ color:#ffd966; font-weight:800; margin-bottom:18px; }
    .modal-actions{ display:flex; gap:12px; justify-content:center; }
    .btn-cancel{ background:#222a37; color:#19ff8c; border:none; padding:10px 16px; border-radius:12px; font-weight:900; cursor:pointer; }
    .btn-exit{ background:#ff4444; color:#fff; border:none; padding:10px 16px; border-radius:12px; font-weight:900; cursor:pointer; }
    /* --- PREGUNTA: mitad inferior, ancho completo, 2x2 responsive --- */
#question-holder{
  position: fixed;      /* ancla a la ventana */
  inset: 0;
  display: flex;
  align-items: flex-end; /* baja el panel */
  justify-content: center;
  padding-bottom: env(safe-area-inset-bottom, 0);
}

#pantalla{
  width: 100vw;
  max-width: 100vw;
  height: 50vh;          /* ocupa la mitad inferior */
  min-height: 50vh;
  padding: var(--page-pad);
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
}

#main-question-box{ width:100%; max-width: var(--maxw); }
.question-box{ width:100%; }

/* 2x2 siempre, ocupando a lo ancho */
.options-grid{
  width: 100%;
  grid-template-columns: repeat(2, 1fr) !important;
  grid-auto-rows: minmax(64px, 1fr);
  gap: clamp(.8rem, 2.5vw, 1.15rem);
  margin: 0 auto;
}

.option-card{
  width: 100%;
  min-height: clamp(68px, 18vh, 120px); /* crece/encoge sin overflow */
  padding: clamp(8px, 2.2vw, 14px);
}

.option-card span{
  display:block;
  overflow-wrap:anywhere;  /* evita cortes/scrolls */
  word-break: break-word;
}

/* Ajustes en pantallas bajas o muy angostas */
@media (max-height: 700px){
  #pantalla{ height: 52vh; }
  .option-card{ min-height: clamp(60px, 16vh, 100px); }
}

@media (max-width: 480px){
  .question-title{ font-size: clamp(1rem, 4.6vw, 1.4rem); }
  .option-card{ padding: 10px; }
}
  /* --- Pregunta fija en la mitad inferior, sin recortes (PC y móvil) --- */
/* --- Pregunta en mitad inferior, apenas elevada, sin scroll --- */
#question-holder{
  position: fixed !important;
  left: 0; right: 0; top: auto !important;
  bottom: calc(env(safe-area-inset-bottom, 0px) + 25px); /* levanta ~10px y respeta notch */
  height: 50vh;                                         /* mitad de pantalla */
  z-index: 5000;
  display: flex;
  align-items: stretch;
  justify-content: center;
  pointer-events: none; /* tu JS ya agrega .show-up para habilitar interacción */
}
#question-holder.show-up{ pointer-events: auto; }

/* En móviles usar viewport dinámico para que nunca lo tape la barra del navegador */
@supports (height: 1dvh){
  #question-holder{ height: 50dvh; }
}

/* El contenido llena ese 50vh, sin scroll interno */
#pantalla{
  width: 100vw; max-width: 100vw;
  height: 100% !important;   /* ocupa el alto del holder */
  min-height: 0 !important;
  padding: var(--page-pad);
  padding-bottom: calc(env(safe-area-inset-bottom, 0px) + 10px);
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;

  overflow: visible !important;           /* sin scroll */
}

/* Mantén tu grid 2×2, ajustando alturas para que todo quepa sin recortes */
#main-question-box{ width:100%; max-width: var(--maxw); }
.options-grid{
  width: 100%;
  grid-template-columns: repeat(2, 1fr) !important;
  grid-auto-rows: 1fr;
  gap: clamp(.8rem, 2.5vw, 1.15rem);
  margin: 0 auto;
}

/* Alturas adaptativas (más compactas si la pantalla es baja) */
.option-card{ min-height: clamp(64px, 18vh, 110px); }
@supports (height: 1dvh){
  .option-card{ min-height: clamp(64px, 18dvh, 110px); }
}
@media (max-height: 700px){
  .option-card{ min-height: clamp(56px, 16vh, 96px); }
  @supports (height: 1dvh){
    .option-card{ min-height: clamp(56px, 16dvh, 96px); }
  }
}

  </style>
</head>
<body>

  <!-- Puntaje -->
<div class="puntaje-top-container">
  <div id="puntaje-container" class="puntaje-card">
    <span>Puntaje: <span id="score-num">0</span></span>
    <span id="gold-pill">ORO</span>
  </div>
</div>


<!-- HUD: opciones + sonido -->
<!-- HUD: opciones primero, luego sonido -->
<div class="hud-left" aria-label="Controles">

  <!-- VOLVER AL DASHBOARD -->
  <a href="{{ route('guest-dashboard') }}" class="hud-btn hud-back-btn" title="Volver al dashboard" aria-label="Volver al dashboard">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M19 12H5M5 12l7-7M5 12l7 7"/>
    </svg>
  </a>

  <!-- OPCIONES -->
  <div class="opts-wrap">
<button id="opts-btn" class="hud-btn" title="Opciones" aria-haspopup="true" aria-expanded="false">
  <!-- Engranaje limpio (material-like) -->
  <svg viewBox="0 0 24 24" aria-hidden="true" class="icon-gear">
    <path d="M19.14 12.94c.04-.31.06-.63.06-.94s-.02-.63-.06-.94l2.03-1.58a.5.5 0 0 0 .12-.62l-1.92-3.32a.5.5 0 0 0-.58-.22l-2.39.96c-.5-.39-1.04-.71-1.64-.95l-.36-2.54a.5.5 0 0 0-.49-.42h-3.82a.5.5 0 0 0-.49.42l-.36 2.54c-.6.24-1.14.56-1.64.95l-2.39-.96a.5.5 0 0 0-.58.22L2.86 8.06a.5.5 0 0 0 .12.62l2.03 1.58c-.04.31-.06.63-.06.94s.02.63.06.94l-2.03 1.58a.5.5 0 0 0-.12.62l1.92 3.32c.11.21.36.3.58.22l2.39-.96c.5.38 1.04.7 1.64.94l.36 2.54c.04.24.25.42.49.42h3.82c.24 0 .45-.18.49-.42l.36-2.54c.6-.24 1.14-.56 1.64-.94l2.39.96c.22.08.47-.01.58-.22l1.92-3.32a.5.5 0 0 0-.12-.62l-2.03-1.58Z"
          fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
    <circle cx="12" cy="12" r="3.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
  </svg>
</button>


    <div id="opts-menu" class="opts-menu" role="menu" aria-label="Menú de opciones">
      <button type="button" data-act="ruleta" role="menuitem">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="8"/>
            <circle cx="12" cy="12" r="4"/>
            <path d="M12 2v2M12 20v2M2 12h2M20 12h2"/>
          </g>
        </svg>
        Volver a la ruleta
      </button>
      <button type="button" data-act="restart" role="menuitem">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 12a8 8 0 1 1-2.34-5.66"/>
            <path d="M20 4v6h-6"/>
          </g>
        </svg>
        Reiniciar
      </button>
      <button type="button" data-act="exit" role="menuitem">
        <svg viewBox="0 0 24 24" aria-hidden="true">
          <g fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h4"/>
            <path d="M15 7l5 5-5 5"/>
            <path d="M8 12h12"/>
          </g>
        </svg>
        Salir del juego
      </button>
    </div>
  </div>

  <!-- SONIDO -->
  <button id="sound-btn" class="hud-btn" title="Sonido">
    <svg viewBox="0 0 24 24" aria-hidden="true">
      <path d="M4 9v6h3l4 3V6L7 9H4Z" fill="currentColor"></path>
      <g class="waves" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
        <path d="M14.5 8.5a4 4 0 0 1 0 7"/>
        <path d="M16.5 6a7 7 0 0 1 0 12"/>
      </g>
      <path class="slash" d="M3 3L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
    </svg>
  </button>
</div>



  <!-- Intro nombre -->
  <section id="name-screen" class="screen">
    <div class="intro-card">
      <h2 class="intro-title">Ingresá tu nombre</h2>
      <div class="intro-form">
        <input id="name-input" class="intro-input" type="text" placeholder="Tu nombre" maxlength="32" autofocus />
        <button id="name-go" class="intro-btn" type="button">Comenzar</button>
      </div>
    </div>
  </section>

  <!-- Countdown -->
  <section id="countdown-screen" class="screen hidden">
    <div class="countdown-card">
      <div class="cd-title">LISTO <span id="cd-name">Jugador</span>?</div>
      <div class="cd-num" id="cd-num">3</div>
    </div>
  </section>

  <!-- Win -->
<section id="win-screen" class="screen hidden" aria-live="polite">
  <div class="win-card">
    <h2 class="win-title">¡FELICIDADES <span id="win-name">JUGADOR</span>, GANASTE!</h2>
    <div class="win-sub">Te invitamos a ver el directo y jugar con nosotros 🙌</div>
    <div class="win-actions">
      <button id="win-play-again" class="win-btn" type="button">Jugar de nuevo</button>
      <button id="win-exit" class="win-btn" type="button">Salir</button>
    </div>
  </div>
</section>


  <!-- Modal salir -->
  <div id="exit-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="exit-title">
    <div class="modal-card">
      <div id="exit-title" class="modal-title">¿Seguro que querés salir del juego?</div>
      <div class="modal-text">Se perderán tu usuario y tu progreso.</div>
      <div class="modal-actions">
        <button class="btn-cancel" id="exit-cancel" type="button">Cancelar</button>
        <button class="btn-exit" id="exit-confirm" type="button">Sí, salir</button>
      </div>
    </div>
  </div>

  <div class="stage">

    <!-- RUEDA -->
    <section id="ruleta-holder" class="hide-up" style="display:none;" aria-label="Ruleta">
      <div class="tips-top anim-in-up">
        <span>Girá la ruleta</span>
        <span>Al detenerse, aparece la pregunta</span>
      </div>

      <div id="ruleta-container" class="anim-pop" aria-live="polite">
        <svg id="flecha-roja" viewBox="0 0 90 65"><polygon points="0,0 90,0 45,65" style="fill:rgba(255,60,60,0.93);stroke:#ff4747;stroke-width:7;" /></svg>
        <svg id="ruleta-svg" width="440" height="440"></svg>
        <button id="spin-btn" title="Girar" type="button" aria-label="Girar/Detener">
          <img src="/images/ruleta.png" alt="Logo Ruleta"
               onerror="this.onerror=null;this.src='https://upload.wikimedia.org/wikipedia/commons/9/9a/Circle-icons-profile.svg'">
        </button>
      </div>
    </section>

    <!-- PREGUNTAS -->
    <section id="question-holder" class="overlay-content hide-down" style="display:none;" aria-label="Pregunta">
      <div id="pantalla">
        <div id="main-question-box">
          <div class="question-box"><h2 id="question-text" class="question-title">—</h2></div>
          <form id="participar-form" class="space-y-0" autocomplete="off">
            <input type="hidden" name="question_id" value="">
            <div id="options-grid" class="options-grid"></div>
          </form>
          <div id="respuesta-msg" class="text-center" style="display:none;"></div>
        </div>
      </div>
    </section>

  </div>

  <!-- Sonidos -->
  <audio id="rightSound" src="/sounds/right.mp3" preload="auto"></audio>
  <audio id="wrongSound" src="/sounds/wrong.mp3" preload="auto"></audio>
  <audio id="applauseSound" src="/sounds/applause.mp3" preload="auto"></audio>


  <script>
    /* ---------- DATA ---------- */
    const FIXED_TYPES = ["pregunta de oro","random"];
    const BLOCKED     = new Set(["responde el chat","solo yo"]);
    const LOCAL_CATEGORIES = [
      { label:"Pregunta de oro", fixed:true }, { label:"Random", fixed:true },
      { label:"Historia" },{ label:"Ciencia" },{ label:"Deportes" },{ label:"Música" },
      { label:"Cine" },{ label:"Arte" },{ label:"Geografía" },{ label:"Tecnología" }
    ].filter(c=>!BLOCKED.has((c.label||'').toLowerCase()));

    const BANK = {
      "Historia":[{ pregunta_id:"H1", pregunta:"¿En qué año llegó Cristóbal Colón a América?",
        opciones:[{label:"A",texto:"1492"},{label:"B",texto:"1502"},{label:"C",texto:"1485"},{label:"D",texto:"1512"}], label_correcto:"A" }],
      "Ciencia":[{ pregunta_id:"C1", pregunta:"¿Cuál es el planeta más grande del Sistema Solar?",
        opciones:[{label:"A",texto:"Saturno"},{label:"B",texto:"Júpiter"},{label:"C",texto:"Neptuno"},{label:"D",texto:"Urano"}], label_correcto:"B" }],
      "Deportes":[{ pregunta_id:"D1", pregunta:"¿Cuántos jugadores por equipo hay en cancha en fútbol?",
        opciones:[{label:"A",texto:"9"},{label:"B",texto:"10"},{label:"C",texto:"11"},{label:"D",texto:"12"}], label_correcto:"C" }],
      "Música":[{ pregunta_id:"M1", pregunta:"¿Cuál de estos es un instrumento de cuerda?",
        opciones:[{label:"A",texto:"Trombón"},{label:"B",texto:"Violín"},{label:"C",texto:"Clarinete"},{label:"D",texto:"Flauta"}], label_correcto:"B" }],
      "Cine":[{ pregunta_id:"CI1", pregunta:"¿Quién dirigió 'Titanic' (1997)?",
        opciones:[{label:"A",texto:"Christopher Nolan"},{label:"B",texto:"James Cameron"},{label:"C",texto:"Ridley Scott"},{label:"D",texto:"Steven Spielberg"}], label_correcto:"B" }],
      "Arte":[{ pregunta_id:"A1", pregunta:"¿Quién pintó la Mona Lisa?",
        opciones:[{label:"A",texto:"Leonardo da Vinci"},{label:"B",texto:"Miguel Ángel"},{label:"C",texto:"Rafael"},{label:"D",texto:"Botticelli"}], label_correcto:"A" }],
      "Geografía":[{ pregunta_id:"G1", pregunta:"¿Cuál es el río más largo del mundo (medición tradicional)?",
        opciones:[{label:"A",texto:"Yangtsé"},{label:"B",texto:"Amazonas"},{label:"C",texto:"Nilo"},{label:"D",texto:"Misisipi"}], label_correcto:"C" }],
      "Tecnología":[{ pregunta_id:"T1", pregunta:"¿Qué significa 'CPU'?",
        opciones:[{label:"A",texto:"Central Processing Unit"},{label:"B",texto:"Computer Personal Unit"},{label:"C",texto:"Central Program Utility"},{label:"D",texto:"Core Parallel Unit"}], label_correcto:"A" }]
    };

    /* ---------- SESIÓN ---------- */
    const STORAGE_KEY = "DEMO_SPIN_QUIZ_LOCAL_V5";
    const MUTE_KEY = "DEMO_SPIN_QUIZ_MUTE";
    let state = { name:null, score:0, currentCat:null, gold:false, usedIdx:{}, lastQuestion:null };
    try{ Object.assign(state, JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}")); }catch(e){}
    if (!state.usedIdx || typeof state.usedIdx !== 'object') state.usedIdx = {};
    function save(){ localStorage.setItem(STORAGE_KEY, JSON.stringify(state)); }
    const scoreNum = document.getElementById('score-num');
    const goldPill = document.getElementById('gold-pill');
    function setScore(v){ state.score=v; scoreNum.textContent=v; save(); if(v>=10) showWin(); }
    function setGold(v){ state.gold=!!v; goldPill.style.display=v?'inline-block':'none'; save(); }
    function setCat(c){ state.currentCat=c; save(); }

    /* ---------- PANTALLAS ---------- */
    const wheel = document.getElementById('ruleta-holder');
    const qview = document.getElementById('question-holder');
    function toggleAnim(el, showClass, hideClass, mostrar, cb) {
      if (mostrar) { el.style.display = ''; requestAnimationFrame(()=>{ el.classList.add(showClass); el.classList.remove(hideClass); }); if (cb) setTimeout(cb, 560);
      } else { el.classList.add(hideClass); el.classList.remove(showClass); setTimeout(() => { el.style.display = 'none'; if (cb) cb(); }, 560); }
    }
    function showWheel(){ toggleAnim(qview,'hide-down','show-up',false, ()=> { toggleAnim(wheel,'show-down','hide-up',true); }); }
    function showQuestion(){ toggleAnim(wheel,'show-down','hide-up',false, ()=> { toggleAnim(qview,'show-up','hide-down',true); }); }

    const nameScreen = document.getElementById('name-screen');
    const nameInput  = document.getElementById('name-input');
    const nameGo     = document.getElementById('name-go');
    const cdScreen   = document.getElementById('countdown-screen');
    const cdName     = document.getElementById('cd-name');
    const cdNum      = document.getElementById('cd-num');

    function goCountdown(){
      nameScreen.classList.add('hidden');
      cdName.textContent = (state.name||'Jugador').toUpperCase();
      cdScreen.classList.remove('hidden');
      let n=3; cdNum.textContent=n;
      const t = setInterval(()=>{
        n--; if(n<=0){ clearInterval(t); cdScreen.classList.add('hidden'); wheel.style.display=''; wheel.classList.add('show-down'); }
        else { cdNum.textContent=n; cdNum.classList.remove('anim-pop'); void cdNum.offsetWidth; cdNum.classList.add('anim-pop'); }
      }, 1000);
    }
    function startOrResume(){
      scoreNum.textContent = state.score||0; setGold(!!state.gold);
      if(state.name){ nameScreen.classList.add('hidden'); goCountdown(); } else { nameScreen.classList.remove('hidden'); }
    }
    nameGo.onclick = ()=>{ const nm=(nameInput.value||'').trim(); if(nm.length<2){ nameInput.focus(); return; } state.name=nm; save(); goCountdown(); };
    nameInput.addEventListener('keydown', e=>{ if(e.key==='Enter'){ nameGo.click(); }});

    /* ---------- PREGUNTAS ---------- */
    const qForm   = document.getElementById('participar-form');
    const qText   = document.getElementById('question-text');
    const optsBox = document.getElementById('options-grid');
    const msg     = document.getElementById('respuesta-msg');
    const SCORE_RULES = { normal:{correct:+1, wrong:0}, gold:{correct:+5, wrong:-2} };

    function getNextQuestion(category){
      const cat = String(category||'').trim();
      const list = Array.isArray(BANK[cat]) ? BANK[cat] : [];
      if (!state.usedIdx || typeof state.usedIdx !== 'object') state.usedIdx = {};
      const used = state.usedIdx[cat] || (state.usedIdx[cat] = {});
      let idx = list.findIndex((_,i)=> !used[i]);
      if (idx === -1) { state.usedIdx[cat] = {}; save(); idx = list.length ? 0 : -1; }
      if (idx === -1) return null;
      (state.usedIdx[cat] || (state.usedIdx[cat] = {}))[idx] = true;
      save(); return list[idx] || null;
    }

    function renderQuestion(q){
      if(!q){ qText.textContent = 'No hay preguntas en esta categoría (demo).'; optsBox.innerHTML=''; msg.style.display='none'; return; }
      state.lastQuestion = q; save();
      qText.textContent = q.pregunta || '—';
      qForm.querySelector('input[name="question_id"]').value = q.pregunta_id || '';
      optsBox.innerHTML='';
      ['A','B','C','D'].forEach(L=>{
        const data = (q.opciones||[]).find(o=>o.label===L); if(!data) return;
        const el=document.createElement('button');
        el.type='button'; el.className='option-card'; el.dataset.label=L;
        el.innerHTML=`<span class="block" style="font-size:1.6rem;font-weight:900;margin-bottom:6px">${L}</span>
                      <span class="block" style="font-size:1.2rem;font-weight:800;">${data.texto}</span>`;
        el.onclick=()=> onAnswer(L, q.label_correcto);
        optsBox.appendChild(el);
      });
      msg.style.display='none';
    }

    function markCorrect(L){ [...optsBox.children].forEach(b=>{ if(b.dataset.label===L) b.classList.add('correct'); }); }
    function markIncorrect(L){ [...optsBox.children].forEach(b=>{ if(b.dataset.label===L) b.classList.add('incorrect'); }); }
    function afterQuestionReturnToWheel(delayMs){ setTimeout(showWheel, delayMs); }

    function onAnswer(sel, correcto){
      [...optsBox.children].forEach(b=> b.disabled=true);
      const rules = state.gold ? SCORE_RULES.gold : SCORE_RULES.normal;
      if(sel===correcto){
        markCorrect(correcto);
        setScore((state.score||0) + rules.correct);
        msg.style.display='block'; msg.style.color='var(--ok)'; msg.textContent= state.gold ? '¡Correcto! +5 (Oro)' : '¡Correcto! +1';
        playSound('rightSound'); setGold(false); afterQuestionReturnToWheel(2000);
      }else{
        markIncorrect(sel);
        if(rules.wrong!==0) setScore((state.score||0) + rules.wrong);
        msg.style.display='block'; msg.style.color='var(--bad)'; msg.textContent= state.gold ? 'Incorrecto. −2 (Oro)' : 'Incorrecto.';
        playSound('wrongSound'); setTimeout(()=> markCorrect(correcto), 5000); setGold(false); afterQuestionReturnToWheel(6500);
      }
    }

/* ---------- RULETA (PC igual; móvil rápido con texto radial) ---------- */
const colorBase   = "#153364";
const borderNeon  = "#2d60a6";
const borderShadow= "#193a68";
const winnerBlue  = "#2987d8";
const neonGreen   = "#22fa68";
const neonGreenText = "#eaffdb";
const GOLD_FILL   = "#FFD24A";
const GOLD_TEXT_DARK = "#8C6A00";

const SIZE_PRESETS = { "pregunta de oro": 0.10, "random": 0.18 };

const slots = LOCAL_CATEGORIES.map(cat=>{
  const lbl = String(cat.label||"");
  const low = lbl.toLowerCase();
  const isFixed = FIXED_TYPES.includes(low) || !!cat.fixed;
  const isGold  = (low === "pregunta de oro");
  return {
    label: lbl,
    fixed: isFixed,
    color: isFixed ? (isGold ? GOLD_FILL : winnerBlue) : neonGreen,
    textColor: isFixed ? (isGold ? GOLD_TEXT_DARK : "#99e6ff") : neonGreenText,
    size: SIZE_PRESETS[low] || null,
    type: isFixed ? low.replace(/\s/g,"") : "cat"
  };
});

let slotsSum = slots.reduce((acc,s)=> acc + (s.size||0), 0);
const regular = slots.filter(s=> !s.size);
const eachRegular = (1 - slotsSum) / (regular.length || 1);
slots.forEach(s=>{ if(!s.size) s.size = eachRegular; });

const svg = document.getElementById("ruleta-svg");
const W=440, H=440, CX=W/2, CY=H/2, R=205, R2=189;

let currentAngle=0, spinning=false, stopRequested=false;
let currentSpinSpeed=0;
const minSpeed=0.011, maxSpeed=0.29, decelStep=0.989;

// Solo aplicamos optimizaciones en dispositivos táctiles
const IS_COARSE = window.matchMedia && matchMedia("(pointer:coarse)").matches;
const FAST = !!IS_COARSE; // true en móvil/tablet
let rotorG = null;        // grupo rotativo para móvil

function easeOutBack(t){ const c1=1.70158, c3=c1+1; return 1 + c3*Math.pow(t-1,3) + c1*Math.pow(t-1,2); }

/* ====== Defs / capas (una vez) ====== */
let defsReady = false;
function ensureDefs(){
  if (defsReady) return;

  if (!svg.getAttribute("viewBox")) {
    svg.setAttribute("viewBox", `0 0 ${W} ${H}`);
    svg.setAttribute("preserveAspectRatio", "xMidYMid meet");
  }

  const defs = document.createElementNS("http://www.w3.org/2000/svg","defs");
  defs.innerHTML = `
    <radialGradient id="bgGradient" cx="50%" cy="50%" r="63%">
      <stop offset="0%" stop-color="#232946"/>
      <stop offset="100%" stop-color="#111b2b"/>
    </radialGradient>
    <linearGradient id="slotReflex" x1="20%" y1="10%" x2="90%" y2="80%">
      <stop offset="0%" stop-color="#fffde7" stop-opacity="0.32"/>
      <stop offset="28%" stop-color="#ffe47a" stop-opacity="0.15"/>
      <stop offset="66%" stop-color="#fffde7" stop-opacity="0.09"/>
      <stop offset="100%" stop-color="#ffe47a" stop-opacity="0.01"/>
    </linearGradient>

    <!-- Filtros (PC bonitos; en móvil se omiten al dibujar) -->
    <filter id="neonBorder" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur stdDeviation="4" result="glow"/>
      <feMerge><feMergeNode in="glow"/><feMergeNode in="SourceGraphic"/></feMerge>
    </filter>
    <filter id="slotGlow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="0" stdDeviation="3" flood-color="${borderShadow}" flood-opacity="0.31"/>
    </filter>
    <filter id="goldGlow" x="-25%" y="-25%" width="150%" height="150%">
      <feGaussianBlur stdDeviation="6" result="glow"/>
      <feMerge><feMergeNode in="glow"/><feMergeNode in="SourceGraphic"/></feMerge>
    </filter>
    <filter id="slotShadow" x="-16%" y="-16%" width="132%" height="132%">
      <feDropShadow dx="0" dy="0" stdDeviation="2.4" flood-color="#070c16f6" flood-opacity="0.85"/>
    </filter>
  `;
  svg.appendChild(defs);

  const g = document.createElementNS("http://www.w3.org/2000/svg","g");
  g.setAttribute("id","wheel-content");
  svg.appendChild(g);

  defsReady = true;
}
function clearWheel(){
  const g = document.getElementById("wheel-content");
  if (!g) return;
  while (g.firstChild) g.removeChild(g.firstChild);
}
function mkGroup(id){
  const g = document.createElementNS("http://www.w3.org/2000/svg","g");
  g.setAttribute("id", id);
  return g;
}

/* ===== Helper: TEXTO RADIAL por letra ===== */
function drawRadialText(g, txt, midAngle, slotR2, isWinner, s){
  txt = String(txt||"").replace(/\u00A0/g,' ').trim().toUpperCase();
  const letters=[...txt];
  const fontFamily="'Rajdhani','Orbitron',Arial,sans-serif";
  const minFS=11, maxFS=22, ls=0.83, margin=11;
  const minR=65-margin, maxRR=slotR2-10-margin, avail=Math.max(0,maxRR-minR);
  let fs=maxFS, ok=false, th=0;
  while(fs>=minFS){ th=(letters.length-1)*fs*ls; if(th<=avail){ok=true;break;} fs--; }
  if(!ok){ fs=minFS; th=(letters.length-1)*fs*ls; }
  const step=fs*ls, finalH=(letters.length-1)*step, startR=maxRR-(avail-finalH)/2;

  letters.forEach((ch,i)=>{
    const r=startR - i*step;
    const x=CX + r*Math.cos(midAngle);
    const y=CY + r*Math.sin(midAngle);
    const rot=(midAngle*180/Math.PI)+90;
    const t=document.createElementNS("http://www.w3.org/2000/svg","text");
    t.setAttribute("x",x); t.setAttribute("y",y);
    t.setAttribute("font-family",fontFamily);
    t.setAttribute("font-size",fs);
    t.setAttribute("font-weight","bold");
    t.setAttribute("letter-spacing","1px");
    t.setAttribute("text-anchor","middle");
    t.setAttribute("dominant-baseline","middle");
    t.setAttribute("transform",`rotate(${rot} ${x} ${y})`);
    t.setAttribute("stroke","none");
    t.setAttribute("fill", isWinner ? (s.type==="preguntadeoro" ? GOLD_TEXT_DARK : (s.textColor || "#99e6ff")) : "#ffffff");
    t.textContent=ch;
    g.appendChild(t);
  });
}

/* ==================== MÓVIL: construir rotor una vez y rotar ==================== */
function buildStaticWheel(initialAngleRad = 0){
  ensureDefs();
  clearWheel();

  const container = document.getElementById("wheel-content");

  // Fondo (no rota)
  const gBase = mkGroup("g-base");
  container.appendChild(gBase);
  const borderCircle = document.createElementNS("http://www.w3.org/2000/svg","circle");
  borderCircle.setAttribute("cx", CX);
  borderCircle.setAttribute("cy", CY);
  borderCircle.setAttribute("r",  R - 3);
  borderCircle.setAttribute("fill", "url(#bgGradient)");
  borderCircle.setAttribute("stroke", borderNeon);
  borderCircle.setAttribute("stroke-width", "2.1");
  if(!FAST) borderCircle.setAttribute("filter", "url(#neonBorder)"); // en móvil omitimos filtros
  borderCircle.setAttribute("pointer-events","none");
  gBase.appendChild(borderCircle);

  // Rotor (rota): rellenos + divisores + texto radial por letra (blanco mientras gira)
  rotorG = mkGroup("wheel-rotor");
  rotorG.style.willChange = "transform";
  rotorG.style.transformOrigin = `${CX}px ${CY}px`;
  container.appendChild(rotorG);

  let a0 = 0, a2pi = 2*Math.PI;

  // Rellenos
  slots.forEach(s=>{
    const ang = s.size * a2pi;
    const slotR2 = R2;
    const x1 = CX + slotR2 * Math.cos(a0), y1 = CY + slotR2 * Math.sin(a0);
    const x2 = CX + slotR2 * Math.cos(a0+ang), y2 = CY + slotR2 * Math.sin(a0+ang);
    const largeArc = ang > Math.PI ? 1 : 0;
    const d = `M ${CX} ${CY} L ${x1} ${y1} A ${slotR2} ${slotR2} 0 ${largeArc} 1 ${x2} ${y2} Z`;
    const p = document.createElementNS("http://www.w3.org/2000/svg","path");
    p.setAttribute("d", d);
    p.setAttribute("fill", colorBase);
    p.setAttribute("stroke", "none");
    rotorG.appendChild(p);
    a0 += ang;
  });

  // Divisores
  a0 = 0;
  slots.forEach(s=>{
    const ang = s.size * a2pi;
    const x1 = CX + R2 * Math.cos(a0), y1 = CY + R2 * Math.sin(a0);
    const x2 = CX + R2 * Math.cos(a0+ang), y2 = CY + R2 * Math.sin(a0+ang);
    const largeArc = ang > Math.PI ? 1 : 0;
    const d = `M ${CX} ${CY} L ${x1} ${y1} A ${R2} ${R2} 0 ${largeArc} 1 ${x2} ${y2} Z`;
    const divider = document.createElementNS("http://www.w3.org/2000/svg","path");
    divider.setAttribute("d", d);
    divider.setAttribute("fill", "none");
    divider.setAttribute("stroke", borderNeon);
    divider.setAttribute("stroke-width", "2");
    divider.setAttribute("vector-effect","non-scaling-stroke");
    rotorG.appendChild(divider);
    a0 += ang;
  });

  // Texto radial por letra (siempre radial durante el giro)
  a0 = 0;
  slots.forEach(s=>{
    const ang = s.size * a2pi;
    const mid = a0 + ang/2;
    drawRadialText(rotorG, s.label, mid, R2, false, s);
    a0 += ang;
  });

  setWheelAngle(initialAngleRad);
}
function setWheelAngle(rad){
  if(!rotorG) return;
  const deg = rad*180/Math.PI;
  rotorG.style.transformOrigin = `${CX}px ${CY}px`;
  rotorG.style.transform = `rotate(${deg}deg)`;
  rotorG.setAttribute("transform", `rotate(${deg} ${CX} ${CY})`); // fallback Safari
}

/* ====== Dibujo “bonito” (PC y al detener en móvil) ====== */
function drawRuleta(angleBase = 0, selectedIdx = null, highlightT = 0){
  ensureDefs();
  clearWheel();

  const container = document.getElementById("wheel-content");
  const gBase  = mkGroup("g-base");
  const gFill  = mkGroup("g-fills");
  const gDiv   = mkGroup("g-divs");
  const gTop   = mkGroup("g-overlay-win");
  const gText  = mkGroup("g-text");
  container.appendChild(gBase);
  container.appendChild(gFill);
  container.appendChild(gDiv);
  container.appendChild(gTop);
  container.appendChild(gText);

  // Disco base
  const borderCircle = document.createElementNS("http://www.w3.org/2000/svg","circle");
  borderCircle.setAttribute("cx", CX);
  borderCircle.setAttribute("cy", CY);
  borderCircle.setAttribute("r",  R - 3);
  borderCircle.setAttribute("fill", "url(#bgGradient)");
  borderCircle.setAttribute("stroke", borderNeon);
  borderCircle.setAttribute("stroke-width", "2.1");
  if(!FAST) borderCircle.setAttribute("filter", "url(#neonBorder)"); // en móvil sin filtros
  borderCircle.setAttribute("pointer-events","none");
  gBase.appendChild(borderCircle);

  // Rellenos base
  let a0 = angleBase;
  const slotDataList = [];
  slots.forEach((s, idx)=>{
    const ang = s.size * 2 * Math.PI;
    const isWinner = (selectedIdx !== null && idx === selectedIdx);
    const scale = isWinner ? (1 + 0.16 * easeOutBack(Math.max(0, Math.min(1, highlightT||0)))) : 1;
    const slotR2 = R2 * scale;

    const x1 = CX + slotR2 * Math.cos(a0),     y1 = CY + slotR2 * Math.sin(a0);
    const x2 = CX + slotR2 * Math.cos(a0+ang), y2 = CY + slotR2 * Math.sin(a0+ang);
    const largeArc = ang > Math.PI ? 1 : 0;
    const d = `M ${CX} ${CY} L ${x1} ${y1} A ${slotR2} ${slotR2} 0 ${largeArc} 1 ${x2} ${y2} Z`;

    const fill = document.createElementNS("http://www.w3.org/2000/svg","path");
    fill.setAttribute("d", d);
    fill.setAttribute("fill", colorBase);
    fill.setAttribute("stroke", "none");
    if(!FAST) fill.setAttribute("filter", "url(#slotShadow)");
    gFill.appendChild(fill);

    slotDataList.push({ s, midAngle: a0 + ang/2, slotR2, isWinner, dPath:d });
    a0 += ang;
  });

  // Divisores
  a0 = angleBase;
  slots.forEach(s=>{
    const ang = s.size * 2 * Math.PI;
    const x1  = CX + R2 * Math.cos(a0),      y1 = CY + R2 * Math.sin(a0);
    const x2  = CX + R2 * Math.cos(a0+ang),  y2 = CY + R2 * Math.sin(a0+ang);
    const largeArc = ang > Math.PI ? 1 : 0;
    const d = `M ${CX} ${CY} L ${x1} ${y1} A ${R2} ${R2} 0 ${largeArc} 1 ${x2} ${y2} Z`;

    const divider = document.createElementNS("http://www.w3.org/2000/svg","path");
    divider.setAttribute("d", d);
    divider.setAttribute("fill", "none");
    divider.setAttribute("stroke", borderNeon);
    divider.setAttribute("stroke-width", "2");
    divider.setAttribute("vector-effect","non-scaling-stroke");
    if(!FAST) divider.setAttribute("filter", "url(#slotGlow)");
    gDiv.appendChild(divider);

    a0 += ang;
  });

  // Overlay ganador
  if (selectedIdx !== null) {
    const S = slotDataList[selectedIdx];
    if (S) {
      const over = document.createElementNS("http://www.w3.org/2000/svg","path");
      over.setAttribute("d", S.dPath);
      over.setAttribute("fill", S.s.color);
      over.setAttribute("stroke", "none");
      if(!FAST) over.setAttribute("filter", S.s.type === "preguntadeoro" ? "url(#goldGlow)" : "url(#slotGlow)");
      gTop.appendChild(over);

      if(!FAST){
        const reflexTop = document.createElementNS("http://www.w3.org/2000/svg","path");
        reflexTop.setAttribute("d", S.dPath);
        reflexTop.setAttribute("fill", "url(#slotReflex)");
        reflexTop.setAttribute("opacity", "0.45");
        gTop.appendChild(reflexTop);
      }
    }
  }

  // TEXTO radial por letra
  slotDataList.forEach(({s, midAngle, slotR2, isWinner}) => {
    drawRadialText(gText, s.label, midAngle, slotR2, isWinner, s);
  });
}

/* ====== Auto-escala ====== */
(function autoScaleWheel(){
  const holder = document.getElementById("ruleta-container");
  if (!holder) return;
  function fit(){
    const maxW = Math.max(260, (window.innerWidth - 24));
    const maxH = Math.max(280, (window.innerHeight - 140));
    const scale = Math.min(1, maxW/460, maxH/460);
    holder.style.transform = scale < 1 ? `scale(${scale})` : "";
    holder.style.transformOrigin = "center";
  }
  window.addEventListener("resize", fit, { passive:true });
  fit();
})();

/* ====== Utilidad: índice del slot actual ====== */
function getSlotAtAngle(angleRad){
  const eps = 1e-6; // para no caer exacto en el borde
  const angle = (1.5*Math.PI - ((angleRad+eps) % (2*Math.PI)) + 2*Math.PI) % (2*Math.PI);
  let a0=0;
  for(let i=0;i<slots.length;i++){
    const ang=slots[i].size*2*Math.PI;
    if(angle>=a0 && angle<a0+ang) return i;
    a0+=ang;
  }
  return 0;
}

/* ====== Estado entre giros (oro en 2 pasos) ====== */
let lastSpecialSlot = null;

/* ==================== SPIN ==================== */
function startSpin(){
  if(spinning) return;
  spinning = true; stopRequested = false;
  currentSpinSpeed = maxSpeed*(0.87 + Math.random()*0.19);

  // En móvil: construir rotor una vez y solo rotar
  if (FAST && !rotorG) buildStaticWheel(currentAngle);

  (function loop(){
    if(!spinning) return;
    currentAngle += currentSpinSpeed;
    if(currentAngle>=2*Math.PI) currentAngle -= 2*Math.PI;

    if (FAST){
      setWheelAngle(currentAngle);     // móvil: rotar <g>
    } else {
      drawRuleta(currentAngle, null, 0); // PC: render completo como antes
    }

    if(stopRequested){
      currentSpinSpeed *= decelStep;
      if(currentSpinSpeed<=minSpeed){ spinning=false; stopRequested=false; finalizeSpin(); return; }
    }
    requestAnimationFrame(loop);
  })();
}

function finalizeSpin(){
  currentAngle = currentAngle % (2*Math.PI);
  const idx = getSlotAtAngle(currentAngle);
  // al detener, siempre dibujamos bonito (con filtros en PC; sin filtros en móvil)
  let f=0, frames = FAST ? 16 : 32;
  (function hi(){
    const t = Math.min(f/(frames-1),1);
    drawRuleta(currentAngle, idx, t);
    if(++f<frames) requestAnimationFrame(hi);
    else onWheelStopped(idx);
  })();
}

/* ====== Al parar: decidir acción y lanzar pregunta ====== */
function onWheelStopped(idx){
  const selected = slots[idx]||{};
  const lbl = String(selected.label||'');
  const type = String(selected.type||'');

  if(lastSpecialSlot!==null){
    setGold(true);
    if(type==='cat'){
      setTimeout(()=> startQuestionFlow(lbl), 50);
      lastSpecialSlot = null;
    } else if (type==='random'){
      const cats = LOCAL_CATEGORIES.filter(c=>!FIXED_TYPES.includes((c.label||'').toLowerCase()));
      const pick = cats[Math.floor(Math.random()*cats.length)]?.label || "Historia";
      setTimeout(()=> startQuestionFlow(pick), 50);
      lastSpecialSlot = null;
    }
    return;
  }

  if(type==='preguntadeoro'){
    lastSpecialSlot = lbl; // espera segundo giro para elegir categoría
    goldPill.style.display='inline-block';
  } else if (type==='random'){
    const cats = LOCAL_CATEGORIES.filter(c=>!FIXED_TYPES.includes((c.label||'').toLowerCase()));
    const pick = cats[Math.floor(Math.random()*cats.length)]?.label || "Historia";
    setGold(false);
    setTimeout(()=> startQuestionFlow(pick), 50);
  } else {
    setGold(false);
    setTimeout(()=> startQuestionFlow(lbl), 50);
  }
}

/* ====== Fallback: define startQuestionFlow si no existe ====== */
if (typeof window.startQuestionFlow !== 'function') {
  window.startQuestionFlow = function(category){
    let cat = category;
    if (!BANK[cat] || !(BANK[cat]||[]).length) {
      const withQ = Object.keys(BANK).filter(k => (BANK[k]||[]).length);
      if (withQ.length) cat = withQ[Math.floor(Math.random()*withQ.length)];
    }
    setCat(cat);
    const q = getNextQuestion(cat) || {pregunta:'No hay preguntas en esta categoría (demo).', opciones:[]};
    renderQuestion(q);
    showQuestion();
  };
}


    document.getElementById('spin-btn').addEventListener('click', (e)=>{ e.stopPropagation(); if(!spinning) startSpin(); else if(!stopRequested) stopRequested=true; });

    /* ===== SONIDO (mute persistente) ===== */
    let SOUND_ON = (localStorage.getItem(MUTE_KEY) !== '1');
    const soundBtn = document.getElementById('sound-btn');
    function applyMute(){
      soundBtn.classList.toggle('muted', !SOUND_ON);
      document.querySelectorAll('audio').forEach(a => a.muted = !SOUND_ON);
    }
    soundBtn.addEventListener('click', ()=>{
      SOUND_ON = !SOUND_ON;
      localStorage.setItem(MUTE_KEY, SOUND_ON ? '0' : '1');
      applyMute();
    });
    function playSound(id){
      if(!SOUND_ON) return;
      const a=document.getElementById(id); if(!a) return;
      a.currentTime=0; a.play().catch(()=>{});
    }
    applyMute(); // al cargar

    /* ===== OPCIONES (rueda + menú) ===== */
    const optsBtn  = document.getElementById('opts-btn');
    const optsMenu = document.getElementById('opts-menu');
    function closeMenu(){ optsMenu.classList.remove('open'); optsBtn.classList.remove('open'); optsBtn.setAttribute('aria-expanded','false'); }
    function toggleMenu(){
      const open = !optsMenu.classList.contains('open');
      if(open){ optsMenu.classList.add('open'); optsBtn.classList.add('open'); optsBtn.setAttribute('aria-expanded','true'); }
      else closeMenu();
    }
    optsBtn.addEventListener('click', (e)=>{ e.stopPropagation(); toggleMenu(); });
    document.addEventListener('click', (e)=>{ if(!optsMenu.contains(e.target) && e.target!==optsBtn) closeMenu(); });
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closeMenu(); });

    optsMenu.querySelectorAll('button[data-act]').forEach(b=>{
      b.addEventListener('click', ()=>{
        const act = b.dataset.act;
        closeMenu();
        if(act==='ruleta') showWheel();
        if(act==='restart') restartGame();
        if(act==='exit') openExitModal();
      });
    });

    /* Win / Restart / Exit */
    const winScreen = document.getElementById('win-screen');
    const winName = document.getElementById('win-name');
    document.getElementById('win-play-again').onclick = ()=>{ restartGame(); showWheel(); };
    document.getElementById('win-exit').onclick = ()=>{ openExitModal(); };

    function showWin(){
  if (!state.name) return;
  winName.textContent = (state.name || 'Jugador').toUpperCase();
  playSound('applauseSound');
  winScreen.classList.remove('hidden');
}

    function restartGame(){
      state.score=0; state.gold=false; state.usedIdx={}; state.currentCat=null; state.lastQuestion=null;
      setScore(0); setGold(false); save();
      winScreen.classList.add('hidden');
      currentAngle=0; drawRuleta(0); lastSpecialSlot=null;
    }

    const exitModal = document.getElementById('exit-modal');
    document.getElementById('exit-cancel').onclick = ()=> exitModal.classList.remove('open');
document.getElementById('exit-confirm').onclick = () => {
  try { localStorage.removeItem(STORAGE_KEY); } catch (e) {}
  window.location.replace('/guest-dashboard'); // usa replace para que no puedan volver con "Atrás"
};

    function openExitModal(){ exitModal.classList.add('open'); }

    /* Init */
    drawRuleta(0);
    startOrResume();

    window.addEventListener('keydown', e=>{
      if(e.code==='Space'){ e.preventDefault(); if(!spinning) startSpin(); else if(!stopRequested) stopRequested=true; }
    });
  </script>
</body>
</html>
