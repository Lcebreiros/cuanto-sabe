@extends('layouts.app')

@section('content')
@php
    $activeSession = \App\Models\GameSession::where('status', 'active')->latest()->first();
    $categorias = \App\Models\Categoria::all() ?? [];
    $motivos = \App\Models\Motivo::all() ?? [];
@endphp

<style>
    :root {
        --primary-color: #00f0ff;
        --secondary-color: #ff00ff;
        --success-color: #19ff8c;
        --warning-color: #ffcc00;
        --error-color: #ff4444;
        --dark-bg: #0a0e23;
        --card-bg: rgba(15, 18, 42, 0.95);
        --input-bg: rgba(23, 28, 51, 0.8);
        --text-primary: #ffffff;
        --text-secondary: #b8c7ff;
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .game-panel-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1rem 2rem;
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
    }

    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        grid-column: 1 / -1;
        position: relative;
    }

    .header-content {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .power-toggle-container {
        position: absolute;
        right: 0;
        top: 0;
    }

    .power-btn {
        border: none;
        outline: none;
        border-radius: 50px;
        font-size: 1.2rem;
        font-family: 'Orbitron', sans-serif;
        font-weight: 800;
        padding: 14px 30px 14px 70px;
        letter-spacing: 2px;
        color: #fff;
        position: relative;
        transition: var(--transition);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        min-width: 200px;
    }

    .power-btn .indicator {
        position: absolute;
        left: 25px;
        top: 50%;
        transform: translateY(-50%);
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.2);
        transition: var(--transition);
    }

    .power-btn.off {
        background: linear-gradient(to right, #2d1313, #4a1a1a);
        color: #ff9a9a;
    }

    .power-btn.off .indicator {
        background: #ff2d3b;
        box-shadow: 0 0 20px #ff2d3b99;
    }

    .power-btn.on {
        background: linear-gradient(to right, #0c3320, #1a4a2d);
        color: #00ffb7;
    }

    .power-btn.on .indicator {
        background: #10ff62;
        box-shadow: 0 0 25px #10ff628f, 0 0 15px #00ffb7bb inset;
    }

    .power-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.4);
    }

    .guest-info-container {
        display: flex;
        gap: 20px;
        align-items: center;
        background: linear-gradient(135deg, rgba(0, 240, 255, 0.1) 0%, rgba(255, 0, 255, 0.1) 100%);
        border-radius: 16px;
        padding: 15px 25px;
        border: 1px solid rgba(0, 240, 255, 0.3);
        box-shadow: 0 0 30px rgba(0, 240, 255, 0.2);
        backdrop-filter: blur(5px);
        max-width: 800px;
        margin: 0 auto;
    }

    .guest-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00f0ff 0%, #ff00ff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-bg);
    }

    .guest-details {
        flex: 1;
    }

    .guest-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 5px;
        text-shadow: 0 0 10px var(--primary-color);
    }

    .guest-meta {
        display: flex;
        gap: 20px;
    }

    .guest-meta-item {
        font-size: 1rem;
    }

    .guest-meta-item strong {
        color: var(--primary-color);
    }

    .guest-points {
        background: rgba(25, 255, 140, 0.2);
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: 800;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        border: 1px solid rgba(25, 255, 140, 0.3);
        box-shadow: 0 0 20px rgba(25, 255, 140, 0.1);
    }

    .guest-info-placeholder {
        font-size: 1.2rem;
        color: var(--text-secondary);
        text-align: center;
        width: 100%;
        padding: 20px;
    }

    .main-content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .spin-btn-container {
        display: flex;
        justify-content: center;
        margin: 20px 0;
        grid-column: 1;
    }

    .spin-btn {
        background: linear-gradient(135deg, #00f0ff 0%, #ff00ff 100%);
        color: #00122c;
        border: none;
        border-radius: 50px;
        padding: 20px 40px;
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        box-shadow: 0 0 40px rgba(0, 240, 255, 0.5),
                    0 0 80px rgba(255, 0, 255, 0.3);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        min-width: 250px;
    }

    .spin-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .spin-btn.spinning {
        background: linear-gradient(135deg, #ff00ff 0%, #ff2d3b 100%);
    }

    .participants-container {
        background: transparent;
        border-radius: 16px;
        overflow: hidden;
        max-height: 600px;
        grid-column: 2;
        grid-row: 2 / span 2;
    }

    .participants-title {
        color: var(--primary-color);
        font-size: 1.2rem;
        margin: 0;
        padding: 15px 20px;
        text-shadow: 0 0 8px var(--primary-color);
        border-bottom: 1px solid rgba(0, 240, 255, 0.3);
        background: rgba(15, 18, 42, 0.5);
    }

    .participants-content {
        padding: 0;
        max-height: 540px;
        overflow-y: auto;
    }

    .empty-queue-message {
        color: var(--text-secondary);
        padding: 20px;
        text-align: center;
    }

    .question-panel {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid rgba(0, 240, 255, 0.3);
        box-shadow: 0 0 25px rgba(0, 240, 255, 0.1);
        grid-column: 1;
    }

    .question-text {
        font-size: 1.3rem;
        color: #18fff9;
        font-weight: 700;
        text-shadow: 0 0 10px rgba(25, 250, 255, 0.8);
        margin-bottom: 20px;
        min-height: 50px;
        text-align: center;
    }

    .options-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    .option-btn {
        background: #0b1530;
        color: #fff;
        border: 2px solid var(--primary-color);
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        padding: 15px;
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
        min-height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .option-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .option-btn:hover:not(:disabled) {
        background: rgba(25, 250, 255, 0.1);
    }

    .option-btn.selected {
        background: #ffe47a;
        color: #222;
        border-color: #ffe47a;
        box-shadow: 0 0 20px rgba(255, 228, 122, 0.7);
    }

    .panel-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .panel-action-btn {
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        font-size: 1rem;
    }

    .panel-action-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .reveal-btn {
        background: var(--success-color);
        color: #00361e;
        border: none;
    }

    .reset-btn {
        background: #111b2b;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }

    .start-game-form {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 25px;
        margin: 0 auto 25px;
        border: 1px solid rgba(0, 240, 255, 0.3);
        box-shadow: 0 0 20px rgba(0, 240, 255, 0.1);
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: max-height 0.4s ease, opacity 0.4s ease;
        max-width: 800px;
        grid-column: 1 / -1;
    }

    .start-game-form.show {
        max-height: 500px;
        opacity: 1;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 15px;
        align-items: end;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-control, .form-select {
        background: var(--input-bg);
        color: var(--text-primary);
        border: 1px solid rgba(37, 45, 67, 0.8);
        border-radius: 8px;
        padding: 12px;
        width: 100%;
    }

    .start-btn {
        background: var(--success-color);
        color: #00361e;
        border: none;
        border-radius: 8px;
        padding: 12px 25px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        height: fit-content;
    }

    .random-question-form {
        display: flex;
        gap: 15px;
        align-items: center;
        margin: 0 auto 25px;
        flex-wrap: wrap;
        background: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid rgba(0, 240, 255, 0.2);
        max-width: 800px;
        grid-column: 1;
    }

    .form-label {
        color: var(--primary-color);
        font-size: 1rem;
        margin-bottom: 8px;
    }

    .submit-random-btn {
        background: #23ffe5;
        color: #002;
        border: none;
        border-radius: 8px;
        padding: 12px 25px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    /* resaltar opción en tendencia */
.option-btn.trend {
    border-color: #22fa68;
    box-shadow: 0 0 18px rgba(34,250,104,.45);
    position: relative;
}
.option-btn.trend::after {
    content: 'TENDENCIA';
    position: absolute;
    top: -10px;
    right: 10px;
    font-size: .8rem;
    font-weight: 800;
    letter-spacing: .5px;
    padding: 2px 8px;
    border-radius: 8px;
    background: linear-gradient(90deg,#22fa68 60%,#19faff 100%);
    color: #0b2314;
}


    @media (max-width: 1200px) {
        .game-panel-container {
            grid-template-columns: 1fr;
        }
        
        .participants-container {
            grid-column: 1;
            grid-row: auto;
            margin-top: 30px;
        }
    }

    @media (max-width: 992px) {
        .options-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .panel-header {
            flex-direction: column;
            gap: 15px;
        }
        
        .power-toggle-container {
            position: static;
            align-self: flex-end;
            margin-bottom: 15px;
        }
        
        .guest-info-container {
            flex-direction: column;
            text-align: center;
        }
        
        .guest-meta {
            flex-direction: column;
            gap: 5px;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .panel-actions {
            flex-direction: column;
        }
        
        .random-question-form {
            flex-direction: column;
            align-items: stretch;
        }
    }

    @media (max-width: 480px) {
        .game-panel-container {
            padding: 1rem;
        }
        
        .spin-btn {
            font-size: 1.2rem;
            padding: 16px 30px;
        }
        
        .power-btn {
            min-width: 180px;
            padding: 12px 20px 12px 60px;
            font-size: 1.1rem;
        }
        
        .guest-name {
            font-size: 1.3rem;
        }
        
        .option-btn {
            min-width: 160px;
        }
        
        .options-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="game-panel-container">
    <!-- Encabezado con información del invitado -->
    <div class="panel-header">
        <div class="header-content">
            <div class="guest-info-container">
                @if($activeSession)
                    <div class="guest-avatar">
                        {{ substr($activeSession->guest_name, 0, 1) }}
                    </div>
                    <div class="guest-details">
                        <div class="guest-name">{{ $activeSession->guest_name }}</div>
                        <div class="guest-meta">
                            <div class="guest-meta-item">
                                <strong>Motivo:</strong> {{ $activeSession->motivo->nombre ?? '—' }}
                            </div>
                        </div>
                    </div>
                    <div class="guest-points">
                        {{ $activeSession->guest_points ?? 0 }} pts
                    </div>
                @else
                    <div class="guest-info-placeholder">
                        No hay sesión activa
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Botón ON/OFF -->
        <div class="power-toggle-container">
            <button type="button" class="power-btn {{ $activeSession ? 'on' : 'off' }}" onclick="toggleStartForm()">
                <span class="indicator"></span>
                {{ $activeSession ? 'ON' : 'OFF' }}
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Botón Girar Ruleta -->
        <div class="spin-btn-container">
            <button id="spinButton" class="spin-btn" onclick="toggleSpinButton()" {{ !$activeSession ? 'disabled' : '' }}>
                Girar Ruleta
            </button>
        </div>

        <!-- Panel de Pregunta -->
        <div class="question-panel">
            <div id="textoPreguntaPanel" class="question-text">
                {{ $activeSession ? 'Pregunta aún no enviada' : 'Inicie una sesión para comenzar' }}
            </div>
            <div class="options-grid">
                <button type="button" class="option-btn" id="panelA" style="display:none;" {{ !$activeSession ? 'disabled' : '' }}></button>
                <button type="button" class="option-btn" id="panelB" style="display:none;" {{ !$activeSession ? 'disabled' : '' }}></button>
                <button type="button" class="option-btn" id="panelC" style="display:none;" {{ !$activeSession ? 'disabled' : '' }}></button>
                <button type="button" class="option-btn" id="panelD" style="display:none;" {{ !$activeSession ? 'disabled' : '' }}></button>
            </div>
            <div class="panel-actions">
                <button type="button" class="panel-action-btn reveal-btn" onclick="revelarRespuesta()" {{ !$activeSession ? 'disabled' : '' }}>
                    Revelar respuesta
                </button>
                <button type="button" class="panel-action-btn reset-btn" onclick="reiniciarOverlay()" {{ !$activeSession ? 'disabled' : '' }}>
                    Volver a la ruleta
                </button>
            </div>
        </div>

        <!-- Formulario Pregunta Random -->
        @if($activeSession)
            <form id="enviarPreguntaRandomForm" class="random-question-form" onsubmit="return enviarPreguntaRandom(event)">
                <div class="form-group">
                    <label for="categoriaRandom" class="form-label">Categoría:</label>
                    <select id="categoriaRandom" class="form-select" required>
                        <option value="">Seleccione categoría</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="submit-random-btn">
                    Enviar pregunta random
                </button>
            </form>
        @endif
    </div>

    <!-- Panel de Participantes -->
    <div class="participants-container">
        <h3 class="participants-title">Participantes</h3>
        <div class="participants-content" id="queue-container">
            @if($activeSession)
                @include('components.queue-list', ['participants' => $activeSession->participants])
            @else
                <div class="empty-queue-message">
                    La lista de participantes aparecerá aquí cuando inicie una sesión
                </div>
            @endif
        </div>
    </div>

    <!-- Formulario Iniciar Juego -->
    <div id="start-game-form" class="start-game-form">
        <form action="{{ route('game-session.start') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre del invitado:</label>
                    <input type="text" name="guest_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Motivo:</label>
                    <select name="motivo_id" class="form-select" required>
                        <option value="">Seleccione motivo</option>
                        @foreach($motivos as $motivo)
                            <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="start-btn">
                    Iniciar Juego
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>
<script>
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '{{ env('PUSHER_APP_KEY') }}',
    cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
    forceTLS: true,
});

// Estado del botón de ruleta
let isSpinning = false;

// Funciones JavaScript
function toggleStartForm() {
    document.getElementById('start-game-form').classList.toggle('show');
}

function toggleSpinButton() {
    const spinButton = document.getElementById('spinButton');
    isSpinning = !isSpinning;
    
    if (isSpinning) {
        spinButton.textContent = 'Parar Ruleta';
        spinButton.classList.add('spinning');
    } else {
        spinButton.textContent = 'Girar Ruleta';
        spinButton.classList.remove('spinning');
    }
    
    girarRuleta();
}

function girarRuleta() {
    fetch('/game-session/girar-ruleta', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: isSpinning ? 'start' : 'stop' })
    });
}

function revelarRespuesta() {
    fetch("{{ route('game-session.reveal') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        }
    });
}

function reiniciarOverlay() {
    fetch("{{ route('game-session.overlay-reset') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        }
    });
    
    document.getElementById('textoPreguntaPanel').textContent = 'Pregunta aún no enviada';
    ['A','B','C','D'].forEach(l => {
        const btn = document.getElementById('panel'+l);
        if (!btn) return;
        btn.style.display = 'none';
        btn.textContent = l;
        btn.classList.remove('selected','trend');
        btn.dataset.baseText = '';
    });
    
    if (isSpinning) {
        toggleSpinButton();
    }
}

function enviarPreguntaRandom(e) {
    e.preventDefault();
    const catId = document.getElementById('categoriaRandom').value;
    if (!catId) return false;
    
    fetch("{{ route('game-session.random-question') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ categoria_id: catId })
    });
    
    return false;
}

// WebSocket listeners
document.addEventListener('DOMContentLoaded', function() {
    // Selección de opciones
    ['A','B','C','D'].forEach(l => {
        const btn = document.getElementById('panel'+l);
        if (btn) {
            btn.onclick = function() {
                ['A','B','C','D'].forEach(x => {
                    const other = document.getElementById('panel'+x);
                    if (other) other.classList.remove('selected');
                });
                btn.classList.add('selected');
                
                fetch("{{ route('game-session.select-option') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ opcion: l })
                });
            };
        }
    });

    // Actualización de lista de participantes
    @if($activeSession)
        const sessionId = {{ $activeSession->id }};
        const channelName = `queue-session-${sessionId}`;
        
        window.Echo.channel(channelName)
            .listen('.ParticipantQueueUpdated', () => {
                updateQueueList(sessionId);
            });

        function updateQueueList(sessionId) {
            fetch(`/queue-list/${sessionId}`, {
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const container = document.getElementById('queue-container');
                if (container) container.innerHTML = html;
            });
        }
    @endif

    // --- Overlay channel: nueva pregunta + tendencia + reset ---
    if (window.Echo) {
        const overlay = Echo.channel('overlay-channel');

        // Nueva pregunta
        overlay.listen('.nueva-pregunta', (e) => {
            const data = e.data || e || {};
            const pregunta = data.pregunta || (data.data ? data.data.pregunta : '') || '';
            const opciones = data.opciones || (data.data ? data.data.opciones : []) || [];

            const txt = document.getElementById('textoPreguntaPanel');
            if (txt) txt.textContent = pregunta || 'Pregunta aún no enviada';

            ['A','B','C','D'].forEach((l) => {
                const btn = document.getElementById('panel'+l);
                if (!btn) return;
                const opcion = opciones.find(op => op.label === l);

                if (opcion) {
                    btn.style.display = '';
                    const base = `${l}: ${opcion.texto}`;
                    btn.dataset.baseText = base;   // guardar el texto base
                    btn.textContent = base;
                } else {
                    btn.style.display = 'none';
                    btn.dataset.baseText = '';
                    btn.textContent = '';
                }
                btn.classList.remove('selected','trend');
            });

            // Sincronizar pregunta con backend
            fetch("{{ route('game-session.sync-question') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ pregunta: data })
            }).catch(err => console.error('sync-question error', err));
        });

        // Tendencia actualizada
        overlay.listen('.tendencia-actualizada', (e) => {
            // Tu evento envía $data, así que lo leo desde e.data
            const payload = e.data || {};
            const label = payload.option_label; // 'A' | 'B' | 'C' | 'D'
            const total = payload.total;

            // limpiar tendencia previa
            ['A','B','C','D'].forEach(l => {
                const btn = document.getElementById('panel'+l);
                if (!btn) return;
                btn.classList.remove('trend');
                if (btn.dataset.baseText) btn.textContent = btn.dataset.baseText;
            });

            // marcar nueva tendencia
            if (label) {
                const btn = document.getElementById('panel'+label);
                if (btn) {
                    btn.classList.add('trend');
                    const base = btn.dataset.baseText || btn.textContent || label;
                    btn.textContent = `${base} — Tendencia (${total})`;
                }
            }
        });

        // Reset overlay
        overlay.listen('.overlay-reset', reiniciarOverlay);
    }

    // Deshabilitar funcionalidades si no hay sesión activa
    if (!@json($activeSession ? true : false)) {
        document.querySelectorAll('.spin-btn, .reveal-btn, .reset-btn, .option-btn').forEach(btn => {
            btn.disabled = true;
        });
    }
});
</script>

@endsection