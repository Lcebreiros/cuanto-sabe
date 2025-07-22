@extends('layouts.app')

@section('content')
<style>
.top-panels-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 16px;
    width: 100%;
}

.panel-superior {
    flex: 1;
    min-width: 0; /* Permite que se contraiga si es necesario */
    max-width: 350px;
}

.session-info-box {
    background: #141e35;
    border-radius: 12px;
    border: 1.3px solid #00f0ff45;
    color: #fff;
    margin: 0; /* Eliminar margen para mejor alineación */
    padding: 8px 20px;
    font-size: 1.12rem;
    box-shadow: 0 0 8px #00f0ff22;
    /* Eliminar max-width fijo para que se adapte */
}

.side-queue-static {
    width: 355px;
    max-width: 380px;
    min-width: 260px;
    z-index: 1200;
    padding: 26px 28px 20px 28px;
    margin: 0; /* Eliminar márgenes automáticos */
    box-sizing: border-box;
    flex-shrink: 0; /* Evita que se contraiga */
}

/* Resto del CSS original */
.page-content-safe {
    width: 100%;
    max-width: 100vw;
    margin: 0;
    padding-left: max(12px, 2vw);
    padding-right: max(12px, 2vw);
    box-sizing: border-box;
    overflow-x: hidden;
}
@media (max-width: 600px) {
    .page-content-safe {
        padding-left: 8px;
        padding-right: 8px;
    }
}
/* ---- LUZ DE RADIO ---- */
.radio-light-btn {
    border: none;
    outline: none;
    border-radius: 38px;
    font-size: 1.24rem;
    font-family: 'Orbitron', Arial, sans-serif;
    font-weight: bold;
    padding: 12px 30px 12px 60px;
    margin-right: 10px;
    letter-spacing: 2px;
    background: #211;
    color: #fff;
    position: relative;
    transition: background 0.18s, color 0.16s, box-shadow 0.18s, transform 0.15s;
    box-shadow: 0 0 8px #211, 0 0 0 #fff0;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
    vertical-align: middle;
    /* IMPORTANTE: Mantener tamaño fijo SIEMPRE */
    width: auto !important;
    min-width: 130px !important;
    max-width: 240px !important;
}
.radio-light-btn .light {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #ff2d3b;
    box-shadow: 0 0 14px #ff2d3b99, 0 0 0 #fff0;
    border: 2px solid #fff2;
    transition: background 0.18s, box-shadow 0.18s, width 0.16s, height 0.16s;
}
.radio-light-btn.off .light { background: #ff2d3b; box-shadow: 0 0 14px #ff2d3b99; }
.radio-light-btn.off { background: #2d1313; color: #ff8888; box-shadow: 0 0 16px #ff2d3b30; }
.radio-light-btn.on .light { background: #10ff62; box-shadow: 0 0 22px #10ff628f, 0 0 14px #00ffb7bb inset; }
.radio-light-btn.on { background: #0c3320; color: #00ffb7; box-shadow: 0 0 28px #19ffa990, 0 0 1px #fff6; }
.radio-light-btn:hover, .radio-light-btn:focus {
    filter: brightness(1.09) contrast(1.07);
    transform: scale(1.04);
    box-shadow: 0 0 24px #00f0ff22, 0 0 0 #fff0;
}
.radio-light-btn:hover .light, .radio-light-btn:focus .light {
    width: 33px;
    height: 33px;
    box-shadow:
        0 0 30px #ffe78caa,
        0 0 50px #fff93333,
        0 0 30px #00ffb7a1,
        0 0 18px #ff2d3b80,
        0 0 38px #15ff90c9,
        0 0 22px #10ff6299;
}
/* ---- RESTO DE ESTILOS ---- */
.neon-btn-min {
    background: #0e1738;
    color: rgb(255,255,255);
    border: 1.5px solid #262b39;
    border-radius: 14px;
    font-size: 1.1rem;
    padding: 12px 28px;
    font-weight: bold;
    margin-bottom: 8px;
    margin-right: 10px;
    box-shadow: none;
    letter-spacing: 1px;
    transition: background 0.2s, color 0.2s, border-color 0.18s, box-shadow 0.18s;
    outline: none;
    position: relative;
}
.neon-btn-min:hover, .neon-btn-min:focus {
    background: #151f39;
    color: #fff;
    border-color: #00f0ff;
    box-shadow: 0 0 12px #00f0ff88, 0 0 4px #fff2;
    text-shadow: 0 0 4px #00f0ffbb;
}
.toggle-form {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transition: max-height 0.35s cubic-bezier(.4,0,.2,1), opacity 0.4s cubic-bezier(.4,0,.2,1);
    margin-bottom: 0;
}
.toggle-form.show {
    opacity: 1;
    max-height: 550px;
    margin-bottom: 14px;
}
.form-control, .form-select {
    background: #171c33;
    color: #fff;
    border: 1.2px solid #252d43;
    border-radius: 8px;
    box-shadow: none;
    margin-bottom: 6px;
    font-size: 1rem;
}
.form-control:focus, .form-select:focus {
    border-color: #00f0ff;
    background: #191f39;
    color: #fff;
    box-shadow: 0 0 10px #00f0ff44;
}
label { color: #61eaff; font-size: 0.97rem; margin-bottom: 2px; }
ul { list-style: none; padding-left: 0; color: #b2e2ff; font-size: 1rem; margin-bottom: 6px; }
ul li { margin-bottom: 2px; }

.radio-light-btn.off:hover .light,
.radio-light-btn.off:focus .light {
    background: #ff2d3b;
    box-shadow:
        0 0 30px #ff2d3baa,
        0 0 50px #ff2d3b33,
        0 0 30px #ff2d3b99,
        0 0 22px #ff2d3b80;
}
.radio-light-btn.on:hover .light,
.radio-light-btn.on:focus .light {
    background: #10ff62;
    box-shadow:
        0 0 30px #10ff62aa,
        0 0 50px #10ff6233,
        0 0 30px #00ffb7a1,
        0 0 18px #15ff90c9,
        0 0 38px #15ff90c9,
        0 0 22px #10ff6299;
}

/* Media queries para mobile */
@media (max-width: 700px) {
    /* En mobile, los paneles superiores van en columna */
    .top-panels-container {
        flex-direction: column !important;
        gap: 14px !important;
        align-items: stretch !important;
    }
    
    .panel-superior {
        width: 100% !important;
    }
    
    .session-info-box {
        width: 100% !important;
        box-sizing: border-box;
    }
    
    .side-queue-static {
        width: 100% !important;
        min-width: 0 !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 20px 0 !important;
        box-sizing: border-box !important;
    }

    .page-content-safe > div[style*="display: flex"] {
        flex-direction: column !important;
        gap: 14px !important;
        align-items: stretch !important;
        width: 100% !important;
    }
    .page-content-safe > div > div[style*="display: flex"] {
        flex-direction: column !important;
        gap: 10px !important;
        align-items: stretch !important;
        width: 100% !important;
    }
    /* Botones: SOLO los que no son ON/OFF toman ancho auto, el ON/OFF NO cambia */
    .neon-btn-min,
    .custom-btn {
        width: 100% !important;
        min-width: 0 !important;
        max-width: 100% !important;
        margin-right: 0 !important;
        margin-bottom: 8px !important;
        box-sizing: border-box;
        text-align: center !important;
        justify-content: center !important;
    }
    /* El botón ON/OFF nunca cambia de tamaño en mobile */
    .radio-light-btn {
        width: auto !important;
        min-width: 220px !important;
        max-width: 340px !important;
        margin-right: 0 !important;
        margin-bottom: 8px !important;
        box-sizing: border-box;
        text-align: center !important;
        justify-content: center !important;
    }
    /* Botón copiar URL nunca estirado */
    button[title="Copiar URL del Overlay"] {
        width: 46px !important;
        min-width: 46px !important;
        max-width: 46px !important;
        margin-bottom: 8px !important;
        margin-right: 0 !important;
        padding: 0 !important;
    }
    /* Inputs full ancho */
    .form-control, .form-select {
        width: 100% !important;
        min-width: 0 !important;
        box-sizing: border-box;
    }
    /* Panel de pregunta full ancho */
    #preguntaOverlayPanel {
        max-width: 100vw !important;
        width: 100% !important;
        padding-left: 2vw !important;
        padding-right: 2vw !important;
        box-sizing: border-box;
    }
}
</style>

<div class="page-content-safe">

            <h2 style="color: #00f0ff; text-shadow: 0 0 8px #00f0ff; margin-bottom: 18px; font-size: 1.7rem;">
                Panel de Juego
            </h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @php
                $activeSession = \App\Models\GameSession::where('status', 'active')->latest()->first();
            @endphp

<div style="display: flex; 
            align-items: center; 
            justify-content: space-between; 
            flex-direction: row;
            width: 100%;
            max-width: 100vw;
            margin: 0 0 28px 0;
            gap: 20px;">

    
    {{-- Contenedor del botón ON/OFF --}}
<div style="display: flex; align-items: center; flex-shrink: 0;">
        {{-- Luz de radio (Iniciar/Finalizar Juego) --}}
        @if(!$activeSession)
            <button type="button"
                class="radio-light-btn off"
                onclick="toggleForm('formStartGame')"
                style="display: inline-flex !important; 
                       align-items: center !important; 
                       white-space: nowrap !important;
                       vertical-align: middle !important;">
                <span class="light"></span>
                OFF
            </button>
        @else
            <form action="{{ route('game-session.end') }}" method="POST" 
                  style="display: inline-flex !important; margin: 0 !important; padding: 0 !important;">
                @csrf
                <button type="submit" 
                    class="radio-light-btn on"
                    style="display: inline-flex !important; 
                           align-items: center !important; 
                           white-space: nowrap !important;
                           vertical-align: middle !important;">
                    <span class="light"></span>
                    ON
                </button>
            </form>
        @endif
    </div>

    {{-- Contenedor del botón Overlay y botón copiar --}}
<div style="display: flex; align-items: center; flex-shrink: 0; gap: 8px; justify-content: center">
        <a href="{{ url('/overlay') }}" 
           target="_blank" 
           class="neon-btn-min"
           style="display: inline-flex !important; 
                  align-items: center !important; 
                  gap: 7px; 
                  background: #161e2a; 
                  color: #19faff; 
                  border-color: #19faff; 
                  font-weight: bold; 
                  min-width: 110px; 
                  justify-content: center;
                  text-decoration: none !important;
                  white-space: nowrap !important;
                  vertical-align: middle !important;">
            <svg width="22" height="22" fill="currentColor" class="bi bi-tv" viewBox="0 0 16 16">
                <path d="M2.5 13.5A1.5 1.5 0 0 1 1 12V4a1.5 1.5 0 0 1 1.5-1.5h11A1.5 1.5 0 0 1 15 4v8a1.5 1.5 0 0 1-1.5 1.5h-11ZM2 4a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-11A.5.5 0 0 1 2 12V4Z"/>
                <path d="M6.5 15a.5.5 0 0 1 0-1h3a.5.5 0 0 1 0 1h-3Z"/>
            </svg>
            Overlay
        </a>
        
        {{-- Botón copiar URL --}}
        <button type="button" 
                onclick="copyOverlayUrl()"
                title="Copiar URL del Overlay"
                style="display: inline-flex !important; 
                       align-items: center !important; 
                       justify-content: center !important;
                       height: 46px; 
                       width: 46px; 
                       background: #161e2a; 
                       color: #19faff; 
                       border: 2px solid #19faff; 
                       border-radius: 14px;
                       cursor: pointer;
                       transition: all 0.3s ease;
                       padding: 0;
                       font-size: 1.1rem;
                       font-family: 'Orbitron', Arial, sans-serif;
                       font-weight: bold;
                       box-shadow: none;
                       text-decoration: none !important;
                       white-space: nowrap !important;
                       vertical-align: middle !important;"
                onmouseover="this.style.background='#19faff'; this.style.color='#161e2a';"
                onmouseout="this.style.background='#161e2a'; this.style.color='#19faff';">
            <svg width="20" height="20" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V2Zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H6ZM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1H2Z"/>
            </svg>
        </button>

<button onclick="girarRuleta()" class="neon-btn-min">Girar ruleta</button>
<script>
function girarRuleta() {
    fetch('/game-session/girar-ruleta', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
}
</script>



    </div>

<script>
function copyOverlayUrl() {
    const overlayUrl = "{{ url('/overlay') }}";
    
    // Usar la API moderna del portapapeles si está disponible
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(overlayUrl).then(() => {
            showCopySuccess();
        }).catch(err => {
            console.error('Error copiando:', err);
            fallbackCopyTextToClipboard(overlayUrl);
        });
    } else {
        // Fallback para navegadores más antiguos
        fallbackCopyTextToClipboard(overlayUrl);
    }
}

function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showCopySuccess();
    } catch (err) {
        console.error('Error copiando:', err);
        alert('No se pudo copiar la URL. Por favor, copia manualmente: ' + text);
    }
    
    document.body.removeChild(textArea);
}

function showCopySuccess() {
    // Crear notificación temporal
    const notification = document.createElement('div');
    notification.textContent = '¡URL copiada!';
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #19faff;
        color: #161e2a;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: bold;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    document.body.appendChild(notification);

    // Remover la notificación después de 2 segundos
    setTimeout(() => {
        notification.remove();
    }, 2000);
}


// Agregar la animación CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);

</script>
</div>


            <div style="height: 24px;"></div>

{{-- Caja con info de sesión debajo de los botones --}}
@if($activeSession)
    <!-- Contenedor que agrupa panel superior y side-queue en la misma línea -->
    <div class="top-panels-container">
        <div class="panel-superior">
            <div class="session-info-box">
                <strong>Invitado:</strong> {{ $activeSession->guest_name }}<br>
                <strong>Motivo:</strong> {{ $activeSession->motivo->nombre ?? '—' }}
            </div>
        </div>

        @php
            $activeSession = \App\Models\GameSession::where('status', 'active')->latest()->first();
        @endphp

        <div class="side-queue-static">
            <div id="queue-container">
                @include('components.queue-list', [
                    'participants' => $activeSession ? $activeSession->participants : collect([]),
                    'session' => $activeSession,
                ])
            </div>
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.Echo === 'undefined') {
        console.error('❌ Echo no está disponible');
        return;
    }

    @if($activeSession)
        const sessionId = {{ $activeSession->id }};
        const channelName = `queue-session-${sessionId}`;
        console.log('📡 Suscribiéndose al canal:', channelName);

        window.Echo.channel(channelName)
            .subscribed(() => {
                console.log("✅ Suscripción al canal exitosa:", channelName);
            })
            .listen('.ParticipantQueueUpdated', (e) => {
                console.log("🎉 Evento ParticipantQueueUpdated recibido!", e); // ESTE ES EL LOG CLAVE
                updateQueueList(sessionId);
            });

        function updateQueueList(sessionId) {
            const url = `/queue-list/${sessionId}`;
            console.log("🔄 Actualizando lista desde:", url);

            fetch(url, {
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const container = document.getElementById('queue-container');
                if (container) {
                    console.log("✅ HTML de lista recibido. Reemplazando contenido...");
                    container.innerHTML = html;
                    container.style.animation = "updatePulse 0.3s";
                    setTimeout(() => container.style.animation = '', 350);
                } else {
                    console.warn("⚠️ No se encontró el contenedor #queue-container");
                }
            })
            .catch(error => {
                console.error("❌ Error al actualizar la lista:", error);
            });
        }
    @else
        console.warn('⚠️ No hay sesión activa para suscribirse');
    @endif
});
</script>





                <!-- NUEVO: Vista de pregunta y selección de opción para el participante -->
<div id="preguntaOverlayPanel" style="background:#101c2e;border-radius:17px;box-shadow:0 0 15px #0ff6;padding:24px 26px 17px 26px;margin-bottom:22px;max-width:1100px;">
                    <div id="textoPreguntaPanel" style="font-size:1.14rem;color:#18fff9;font-weight:bold;min-height:28px;text-shadow:0 0 7px #19faffd4;margin-bottom:14px;">
                        Pregunta aún no enviada
                    </div>
                    <div id="botonesOpcionesPanel" style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;margin-bottom:17px;">
                        <button type="button" class="opcion-btn-panel" id="panelA" style="display:none;">A</button>
                        <button type="button" class="opcion-btn-panel" id="panelB" style="display:none;">B</button>
                        <button type="button" class="opcion-btn-panel" id="panelC" style="display:none;">C</button>
                        <button type="button" class="opcion-btn-panel" id="panelD" style="display:none;">D</button>
                    </div>
                    <div style="text-align:center;">
                        <button type="button" class="neon-btn-min" style="background:#12ffcb;color:#001;font-weight:bold;" onclick="revelarRespuesta()">Revelar respuesta</button>
                        <button type="button" class="neon-btn-min" style="background:#111b2b;color:#19faff;font-weight:bold;" onclick="reiniciarOverlay()">Reiniciar overlay</button>
                    </div>
                </div>


            <!-- Botón para mandar pregunta random -->
            @if($activeSession)
            <form id="enviarPreguntaRandomForm" class="row g-2 align-items-center mb-3" onsubmit="return enviarPreguntaRandom(event)">
                <div class="col-auto">
                    <label for="categoriaRandom" class="form-label mb-0" style="color:#19faff;">Categoría:</label>
                </div>
                <div class="col-auto">
                    <select id="categoriaRandom" class="form-select" required>
                        <option value="">Elegí una categoría</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="neon-btn-min" style="background:#23ffe5; color:#002; border-color:#0ff;">Enviar pregunta random</button>
                </div>
            </form>
            @endif

            <!-- Formulario para iniciar juego -->
            <div id="formStartGame" class="toggle-form">
                <form action="{{ route('game-session.start') }}" method="POST" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-12 col-md-5">
                        <label>Nombre del invitado:</label>
                        <input type="text" name="guest_name" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-5">
                        <label>Motivo:</label>
                        <select name="motivo_id" class="form-select" required>
                            <option value="">Elegí motivo</option>
                            @foreach($motivos as $motivo)
                                <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
<div class="col-12 col-md-auto">
    <!-- Contenedor del botón con un poco de espaciado -->
    <div style="display: flex; justify-content: center; align-items: center; margin-top: 20px;">
        <!-- Botón con formato mejorado -->
        <button type="submit" class="btn custom-btn">
            Iniciar
        </button>
    </div>
</div>


                </form>
            </div>

            <div class="mb-3">
                <button type="button" class="neon-btn-min" onclick="toggleForm('formMotivo')">
                    + Crear motivo
                </button>
                <button type="button" class="neon-btn-min" onclick="toggleForm('formCategoria')">
                    + Crear categoría
                </button>
                <button type="button" class="neon-btn-min" onclick="toggleForm('formPregunta')">
                    + Crear pregunta
                </button>
            </div>

            <!-- FORMULARIOS -->
            <div id="formMotivo" class="toggle-form">
                <form action="{{ route('motivo.store') }}" method="POST" class="row g-2 align-items-end mb-2">
                    @csrf
                    <div class="col-12 col-md-7">
                        <label>Nuevo motivo:</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Programación" required>
                    </div>
                    <div class="col-12 col-md-auto">
                        <button type="submit" class="btn btn-success mt-2">Agregar Motivo</button>
                    </div>
                </form>
            </div>

            <div id="formCategoria" class="toggle-form">
                <form action="{{ route('categoria.store') }}" method="POST" class="row g-2 align-items-end mb-2">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label>Nueva categoría:</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: JavaScript" required>
                    </div>
                    <div class="col-12 col-md-5">
                        <label>Motivo:</label>
                        <select name="motivo_id" class="form-select" required>
                            <option value="">Elegí un motivo</option>
                            @foreach($motivos as $motivo)
                                <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-auto">
                        <button type="submit" class="btn btn-success mt-2">Agregar Categoría</button>
                    </div>
                </form>
            </div>

            <div id="formPregunta" class="toggle-form">
                <form action="{{ route('pregunta.store') }}" method="POST" class="row g-2 align-items-end mb-2">
                    @csrf
                    <div class="col-12 col-md-8 mb-2">
                        <label>Nueva pregunta:</label>
                        <input type="text" name="texto" class="form-control" placeholder="Pregunta..." required>
                    </div>
                    <div class="col-12 col-md-4 mb-2">
                        <label>Categoría:</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Elegí una categoría</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}">
                                    {{ $cat->nombre }} ({{ $cat->motivo->nombre }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6 mb-2">
                        <input type="text" name="opcion_correcta" class="form-control" placeholder="Opción correcta" required>
                    </div>
                    <div class="col-12 col-md-6 mb-2">
                        <input type="text" name="opcion_1" class="form-control" placeholder="Opción incorrecta 1" required>
                    </div>
                    <div class="col-12 col-md-6 mb-2">
                        <input type="text" name="opcion_2" class="form-control" placeholder="Opción incorrecta 2" required>
                    </div>
                    <div class="col-12 col-md-6 mb-2">
                        <input type="text" name="opcion_3" class="form-control" placeholder="Opción incorrecta 3" required>
                    </div>
                    <div class="col-12 col-md-auto">
                        <button type="submit" class="btn btn-success mt-2">Agregar Pregunta</button>
                    </div>
                </form>
            </div>

            <!-- Listado Motivos y Categorías -->
            <div class="mt-3">
                <div>
                    <strong>Motivos existentes:</strong>
                    <ul>
                        @foreach($motivos as $motivo)
                            <li>{{ $motivo->nombre }}</li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <strong>Categorías existentes:</strong>
                    <ul>
                        @foreach($categorias as $cat)
                            <li>{{ $cat->nombre }} (Motivo: {{ $cat->motivo->nombre }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>
<script>
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '{{ env('PUSHER_APP_KEY') }}',    // O poné el valor a mano
    cluster: '{{ env('PUSHER_APP_CLUSTER') }}', // O poné el valor a mano, ej: 'sa1'
    forceTLS: true,
});
</script>

<script>
    // Mostrar/ocultar formularios
    function toggleForm(formId) {
        document.querySelectorAll('.toggle-form').forEach(f => {
            if (f.id !== formId) f.classList.remove('show');
        });
        const el = document.getElementById(formId);
        el.classList.toggle('show');
    }

    // ---- REVELAR RESPUESTA: emite por websocket/evento ----
    function revelarRespuesta() {
        fetch("{{ route('game-session.reveal') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(resp => {
            // Feedback local si quieres.
        });
    }
    // --- Enviar pregunta random de una categoría al overlay ---
    function enviarPreguntaRandom(e) {
        e.preventDefault();
        let catId = document.getElementById('categoriaRandom').value;
        if (!catId) return false;
        fetch("{{ route('game-session.random-question') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ categoria_id: catId })
        })
        .then(res => res.json())
        .then(resp => {
            // Puedes poner feedback si querés
        });
        return false;
    }
</script>
<script>
// --- LOGICA PARA PANEL DE CONTROL DE PREGUNTA Y OPCIONES ---
let preguntaPanel = '';
let opcionesPanel = [];
let seleccionadaPanel = null;

// Recibe la pregunta enviada al overlay por WebSocket/Laravel Echo
window.Echo && Echo.channel('overlay-channel')
    .listen('.nueva-pregunta', (e) => {
        // Recibe el evento de nueva pregunta enviada desde el backend
        let data = e.data || e || {};
        let pregunta = data.pregunta || (data.data ? data.data.pregunta : null) || '';
        let opciones = data.opciones || (data.data ? data.data.opciones : []);
        document.getElementById('textoPreguntaPanel').textContent = pregunta || 'Pregunta aún no enviada';
        ['A','B','C','D'].forEach((l) => {
            let btn = document.getElementById('panel'+l);
            let opcion = opciones.find(op => op.label === l);
            if(opcion){
                btn.style.display = '';
                btn.textContent = l + ': ' + opcion.texto;
            } else {
                btn.style.display = 'none';
                btn.textContent = '';
            }
            btn.classList.remove('seleccionado-panel');
        });
        seleccionadaPanel = null;

        // --- Sincroniza la sesión del panel para revealAnswer ----
        fetch("{{ route('game-session.sync-question') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ pregunta: data })
        });
    });


// Selección de opción - SOLO UNA VEZ Y FUERA DE CUALQUIER CALLBACK
['A','B','C','D'].forEach(l => {
    let btn = document.getElementById('panel'+l);
    if(btn) {
        btn.onclick = function(){
            ['A','B','C','D'].forEach(x=>{
                document.getElementById('panel'+x).classList.remove('seleccionado-panel');
            });
            btn.classList.add('seleccionado-panel');
            seleccionadaPanel = l;

            // ENVÍA OPCIÓN SELECCIONADA AL BACKEND PARA EL OVERLAY
            fetch("{{ route('game-session.select-option') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ opcion: l })
            });
        }
    }
});

// Botón REINICIAR overlay (deberías crear una ruta tipo route('game-session.overlay-reset'))
function reiniciarOverlay(){
    fetch("{{ route('game-session.overlay-reset') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({})
    }).then(res=>res.json());

    // Limpia el panel localmente:
    document.getElementById('textoPreguntaPanel').textContent = 'Pregunta aún no enviada';
    ['A','B','C','D'].forEach(l => {
        let btn = document.getElementById('panel'+l);
        btn.style.display = 'none';
        btn.textContent = l;
        btn.classList.remove('seleccionado-panel');
    });
    seleccionadaPanel = null;
}


// Estilos para el botón seleccionado (solo una vez, sin duplicar)
const cssExtraPanel = document.createElement('style');
cssExtraPanel.textContent = `
.opcion-btn-panel {
    background: #0b1530;
    color: #fff;
    border: 2px solid #19faff;
    border-radius: 13px;
    font-size: 1.09rem;
    font-family: 'Orbitron', Arial, sans-serif;
    font-weight: 700;
    padding: 10px 21px;
    margin: 0 2px 8px 2px;
    cursor: pointer;
    box-shadow: 0 0 7px #19faff36;
    transition: background .16s, color .13s, border .12s, transform .12s;
    outline: none;
}
.opcion-btn-panel.seleccionado-panel {
    background: #ffe47a;
    color: #222;
    border: 2px solid #ffe47a;
    box-shadow: 0 0 16px #ffe47aaa, 0 0 3px #e6be2f77;
    transform: scale(1.07);
}
.opcion-btn-panel:hover:not(.seleccionado-panel) {
    background: #19faff22;
    color: #19faff;
    border: 2px solid #19faff;
}
`;
document.head.appendChild(cssExtraPanel);


// Escucha el evento de reset para limpiar el panel de la pregunta
window.Echo && Echo.channel('overlay-channel')
    .listen('.overlay-reset', (e) => {
        document.getElementById('textoPreguntaPanel').textContent = 'Pregunta aún no enviada';
        ['A','B','C','D'].forEach(l => {
            let btn = document.getElementById('panel'+l);
            btn.style.display = 'none';
            btn.textContent = l;
            btn.classList.remove('seleccionado-panel');
        });
        seleccionadaPanel = null;
    });


document.getElementById('btnGirarRuleta').onclick = function() {
    // Mandá un evento via backend, o por AJAX a un endpoint:
    fetch('/game-session/girar-ruleta', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    });
};


</script>

@endsection