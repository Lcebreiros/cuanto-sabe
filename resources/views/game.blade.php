@php
    $hideNavigation = true;
    $hideFooter = true;
@endphp

@extends('layouts.app')

@section('content')
@php
    $activeSession = \App\Models\GameSession::where('status', 'active')->latest()->first();
    $questionCount = $activeSession
        ? \App\Models\GuestAnswer::where('game_session_id', $activeSession->id)->count()
        : 0;
@endphp

<style>
    /* Resetear padding del main para este panel específico */
    main {
        padding: 0 !important;
    }

    html {
        overflow: hidden;
        width: 100%;
        height: 100%;
    }

    body {
        overflow: hidden;
        position: fixed;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
    }

    * {
        box-sizing: border-box;
    }

    /* Prevenir scroll horizontal */
    .flex-grow,
    .w-full,
    .max-w-7xl,
    .max-w-none {
        overflow: hidden;
        max-width: 100% !important;
    }

    /* Asegurar que inputs y selects se ajusten */
    input, select, button {
        max-width: 100%;
    }

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
        max-width: 100%;
        width: 100%;
        margin: 0;
        padding: 0.5rem;
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 15px;
        height: 100vh;
        height: 100dvh; /* Dynamic viewport height para móvil */
        overflow: hidden;
    }

    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        grid-column: 1 / -1;
        position: relative;
        padding: 0.5rem 0;
    }

    .back-button-container {
        position: fixed;
        left: 8px;
        top: 8px;
        z-index: 1000;
    }

    .back-btn {
        background: rgba(0, 240, 255, 0.15);
        color: var(--primary-color);
        border: 2px solid rgba(0, 240, 255, 0.4);
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        backdrop-filter: blur(10px);
    }

    .back-btn:hover {
        background: rgba(0, 240, 255, 0.3);
        border-color: var(--primary-color);
        box-shadow: 0 0 20px rgba(0, 240, 255, 0.5);
        transform: scale(1.05);
    }

    .back-btn svg {
        width: 14px;
        height: 14px;
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

    .tendencias-counter {
        background: rgba(0, 191, 255, 0.2);
        border-radius: 50px;
        padding: 8px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid rgba(0, 191, 255, 0.3);
        box-shadow: 0 0 20px rgba(0, 191, 255, 0.1);
        min-width: 120px;
    }

    .tendencias-label {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .tendencias-value {
        font-weight: 800;
        font-size: 1.3rem;
        color: #00bfff;
        text-shadow: 0 0 10px rgba(0, 191, 255, 0.8);
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
        gap: 10px;
        overflow-y: auto;
        max-height: calc(100vh - 80px);
        flex: 1;
    }

    /* BOTONERA UNIFICADA */
    .control-panel {
        background: rgba(10, 14, 35, 0.95);
        border-radius: 20px;
        padding: 15px;
        margin-bottom: 10px;
        border: 1px solid rgba(0, 240, 255, 0.2);
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        grid-column: 1;
    }

    .control-buttons-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        align-items: stretch;
    }

    .control-buttons-grid form {
        display: contents;
    }

    .control-btn {
        background: rgba(15, 18, 42, 0.8);
        color: var(--primary-color);
        border: 2px solid rgba(0, 240, 255, 0.3);
        border-radius: 12px;
        padding: 20px 15px;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 100px;
        max-height: 100px;
        position: relative;
        overflow: hidden;
    }

    .control-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .control-btn:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0, 240, 255, 0.4);
        border-color: var(--primary-color);
    }

    .control-btn svg {
        width: 24px;
        height: 24px;
        stroke: currentColor;
    }

    .spin-btn {
        border-color: rgba(0, 240, 255, 0.5);
    }

    .spin-btn.spinning {
        background: linear-gradient(135deg, #ff00ff 0%, #ff2d3b 100%);
        border-color: #ff00ff;
        animation: pulse 1s infinite;
    }

    .reveal-btn {
        border-color: rgba(25, 255, 140, 0.5);
        color: var(--success-color);
    }

    .reset-btn {
        border-color: rgba(255, 204, 0, 0.5);
        color: var(--warning-color);
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .participants-container {
        background: transparent;
        border-radius: 16px;
        overflow: hidden;
        max-height: calc(100vh - 80px);
        grid-column: 2;
        grid-row: 2 / span 2;
        display: flex;
        flex-direction: column;
    }

    .participants-title {
        color: var(--primary-color);
        font-size: 1rem;
        margin: 0;
        padding: 10px 15px;
        text-shadow: 0 0 8px var(--primary-color);
        border-bottom: 1px solid rgba(0, 240, 255, 0.3);
        background: rgba(15, 18, 42, 0.5);
        flex-shrink: 0;
    }

    .participants-content {
        padding: 0;
        flex: 1;
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
        padding: 15px;
        margin-bottom: 10px;
        border: 1px solid rgba(0, 240, 255, 0.3);
        box-shadow: 0 0 25px rgba(0, 240, 255, 0.1);
        grid-column: 1;
        position: relative;
    }

    .question-number-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(0, 240, 255, 0.15);
        border: 1px solid rgba(0, 240, 255, 0.4);
        border-radius: 8px;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--primary-color);
        text-shadow: 0 0 8px rgba(0, 240, 255, 0.8);
        letter-spacing: 0.5px;
        display: none;
    }

    .question-number-badge.active {
        display: block;
    }

    .slot-special-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: linear-gradient(135deg, #ff00ff 0%, #00f0ff 100%);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #fff;
        text-shadow: 0 0 10px rgba(0, 0, 0, 0.8);
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: none;
        animation: pulse-glow 2s infinite;
    }

    .slot-special-badge.active {
        display: block;
    }

    @keyframes pulse-glow {
        0%, 100% {
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.5);
        }
        50% {
            box-shadow: 0 0 30px rgba(0, 240, 255, 0.8);
        }
    }

    .question-text {
        font-size: 1.1rem;
        color: #18fff9;
        font-weight: 700;
        text-shadow: 0 0 10px rgba(25, 250, 255, 0.8);
        margin-bottom: 12px;
        min-height: 35px;
        text-align: center;
    }

    .options-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 12px;
    }

    .option-btn {
        background: #0b1530;
        color: #fff;
        border: 2px solid var(--primary-color);
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 700;
        padding: 10px;
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
        min-height: 60px;
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

    /* Ajustes para responsive de botonera */
    @media (max-width: 768px) {
        .control-buttons-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .control-btn, .apuesta-btn, .descarte-btn, .power-btn-control {
            min-height: 70px;
            max-height: 70px;
            padding: 12px 8px;
            font-size: 0.75rem;
        }

        .control-btn svg {
            width: 18px;
            height: 18px;
        }

        .power-btn-control .indicator {
            width: 16px;
            height: 16px;
        }

        .control-panel {
            padding: 12px;
        }
    }

    @media (max-width: 480px) {
        .control-buttons-grid {
            gap: 6px;
        }

        .control-btn, .apuesta-btn, .descarte-btn, .power-btn-control {
            min-height: 60px;
            max-height: 60px;
            padding: 10px 6px;
            font-size: 0.65rem;
        }

        .control-btn svg {
            width: 16px;
            height: 16px;
        }

        .power-btn-control .indicator {
            width: 14px;
            height: 14px;
        }

        .bonus-btn .label {
            font-size: 0.7rem;
        }

        .bonus-btn .badge {
            font-size: 0.7rem;
            padding: 2px 6px;
        }
    }

    .start-game-form {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 15px;
        margin: 0 auto 10px;
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
        max-height: 300px;
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
        padding: 8px;
        width: 100%;
    }

    .start-btn {
        background: var(--success-color);
        color: #00361e;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        height: fit-content;
        font-size: 0.9rem;
    }

    /* ---- BOTÓN POWER EN BOTONERA ---- */
.power-btn-control {
    background: rgba(15, 18, 42, 0.8);
    border: 2px solid rgba(0, 240, 255, 0.3);
    border-radius: 12px;
    padding: 20px 15px;
    font-size: 0.9rem;
    font-weight: 700;
    text-transform: uppercase;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 100px;
    max-height: 100px;
    position: relative;
    overflow: hidden;
}

.power-btn-control.off {
    background: linear-gradient(135deg, #2d1313 0%, #4a1a1a 100%);
    color: #ff9a9a;
    border-color: rgba(255, 45, 59, 0.5);
}

.power-btn-control.on {
    background: linear-gradient(135deg, #0c3320 0%, #1a4a2d 100%);
    color: #00ffb7;
    border-color: rgba(16, 255, 98, 0.5);
    box-shadow: 0 0 25px rgba(25, 255, 169, 0.3);
}

.power-btn-control .indicator {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.2);
    transition: var(--transition);
}

.power-btn-control.off .indicator {
    background: #ff2d3b;
    box-shadow: 0 0 20px #ff2d3b99;
}

.power-btn-control.on .indicator {
    background: #10ff62;
    box-shadow: 0 0 25px #10ff628f, 0 0 15px #00ffb7bb inset;
}

.power-btn-control:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0, 240, 255, 0.4);
}

    .random-question-form {
        display: flex;
        gap: 10px;
        align-items: center;
        margin: 0 auto 10px;
        flex-wrap: wrap;
        background: var(--card-bg);
        border-radius: 12px;
        padding: 12px;
        border: 1px solid rgba(0, 240, 255, 0.2);
        max-width: 800px;
        grid-column: 1;
        width: 100%;
    }

    .random-question-form .form-group {
        flex: 1;
        min-width: 150px;
    }

    .random-question-form .form-select {
        width: 100%;
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
        padding: 8px 18px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.9rem;
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
/* Botones Bonus - estilo cuadrado unificado */
.apuesta-btn, .descarte-btn {
    background: rgba(15, 18, 42, 0.8);
    border: 2px solid rgba(0, 240, 255, 0.3);
    border-radius: 12px;
    padding: 15px 10px;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    min-height: 100px;
    max-height: 100px;
    position: relative;
    color: var(--text-secondary);
}

.apuesta-btn {
    border-color: rgba(30, 144, 255, 0.5);
}

.descarte-btn {
    border-color: rgba(255, 69, 0, 0.5);
}

/* Estado activo Apuesta */
.apuesta-btn.on {
    background: linear-gradient(135deg, #1e90ff 0%, #00bfff 100%);
    color: #fff;
    border-color: #00bfff;
    box-shadow: 0 0 25px rgba(0, 191, 255, 0.8);
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
}

/* Estado activo Descarte */
.descarte-btn.on {
    background: linear-gradient(135deg, #ff4500 0%, #ff6347 100%);
    color: #fff;
    border-color: #ff6347;
    box-shadow: 0 0 25px rgba(255, 99, 71, 0.8);
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
}

/* Hover */
.apuesta-btn:hover:not(:disabled), .descarte-btn:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0, 240, 255, 0.4);
}

.apuesta-btn:disabled, .descarte-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

/* Estilos para elementos internos de botones bonus */
.bonus-btn {
    width: 100%;
}

.bonus-btn .light {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: currentColor;
    flex-shrink: 0;
}

.bonus-btn .label {
    font-size: 0.85rem;
    font-weight: 700;
}

.bonus-btn .badge {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 3px 10px;
    font-size: 0.8rem;
    font-weight: 700;
    margin-top: 4px;
}

.power-toggle-container {
    position: absolute;
    right: 0;
    top: 0;
    display: flex;
    flex-direction: row;  /* Horizontal */
    align-items: center;
    gap: 12px;  /* Espacio entre botones */
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

.fs-icon {
    transition: transform 0.3s ease;
}

.fullscreen-btn-minimal:hover .fs-icon {
    transform: scale(1.1);
}

/* Responsive: en móvil apilar verticalmente */
@media (max-width: 768px) {
    .power-toggle-container {
        position: static;
        flex-direction: column;
        gap: 10px;
        align-self: flex-end;
        margin-bottom: 15px;
    }
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

    /* Tablet y menor */
    @media (max-width: 768px) {
        .game-panel-container {
            grid-template-columns: 1fr;
            padding: 0.25rem;
            gap: 4px;
            height: 100vh;
            height: 100dvh;
            grid-template-rows: auto 1fr auto;
        }

        .panel-header {
            margin-bottom: 0;
            padding: 0.25rem 0;
        }

        .main-content {
            max-height: none;
            overflow: visible;
            gap: 4px;
            flex: 1;
        }

        .participants-container {
            grid-row: auto;
            max-height: 200px;
        }

        .question-panel {
            padding: 6px;
            margin-bottom: 4px;
        }

        .question-text {
            font-size: 0.85rem;
            margin-bottom: 4px;
            min-height: 25px;
        }

        .options-grid {
            grid-template-columns: 1fr 1fr;
            gap: 4px;
            margin-bottom: 4px;
        }

        .option-btn {
            min-width: 0;
            font-size: 0.7rem;
            padding: 6px 2px;
            min-height: 40px;
        }

        .question-number-badge {
            font-size: 0.65rem;
            padding: 2px 8px;
            top: 8px;
            right: 8px;
        }

        .slot-special-badge {
            font-size: 0.65rem;
            padding: 4px 10px;
            top: 8px;
            left: 8px;
        }

        .control-panel {
            padding: 8px;
        }

        .control-buttons-grid {
            gap: 4px;
        }

        .control-btn, .apuesta-btn, .descarte-btn, .power-btn-control {
            min-height: 65px;
            max-height: 65px;
            padding: 10px 8px;
            font-size: 0.7rem;
        }

        .control-btn svg {
            width: 16px;
            height: 16px;
        }

        .power-btn-control .indicator {
            width: 15px;
            height: 15px;
        }

        .bonus-btn .light {
            width: 10px;
            height: 10px;
        }

        .bonus-btn .label {
            font-size: 0.7rem;
        }

        .bonus-btn .badge {
            font-size: 0.65rem;
            padding: 2px 6px;
        }

        .back-btn {
            padding: 4px 8px;
            font-size: 0.7rem;
        }

        .back-btn svg {
            width: 12px;
            height: 12px;
        }

        .guest-info-container {
            gap: 6px;
            padding: 4px 8px;
        }

        .guest-name {
            font-size: 0.75rem;
        }

        .guest-meta {
            font-size: 0.65rem;
            gap: 4px;
        }

        .guest-points {
            font-size: 0.75rem;
            padding: 4px 12px;
        }

        .tendencias-counter {
            padding: 4px 12px;
            min-width: 100px;
        }

        .tendencias-label {
            font-size: 0.6rem;
        }

        .tendencias-value {
            font-size: 0.85rem;
        }

        .radio-light-btn {
            font-size: 0.7rem;
            padding: 5px 12px 5px 28px;
        }

        .radio-light-btn .light {
            width: 14px;
            height: 14px;
            left: 8px;
        }

        .fullscreen-btn-minimal {
            width: 28px;
            height: 28px;
            padding: 4px;
        }

        .fullscreen-btn-minimal svg {
            width: 14px;
            height: 14px;
        }

        .power-toggle-container {
            gap: 4px;
        }

        .random-question-form {
            padding: 6px;
            margin: 0 auto 4px;
            gap: 4px;
            flex-direction: column;
            align-items: stretch;
        }

        .random-question-form .form-group {
            min-width: 100%;
            margin-bottom: 0;
        }

        .random-question-form .form-label {
            font-size: 0.7rem;
            margin-bottom: 2px;
        }

        .random-question-form .form-select {
            font-size: 0.75rem;
            padding: 4px;
        }

        .submit-random-btn {
            padding: 5px 10px;
            font-size: 0.7rem;
            width: 100%;
        }

        .start-game-form {
            padding: 8px;
            margin: 0 auto 4px;
        }

        .start-game-form.show {
            max-height: 350px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .form-control, .form-select {
            padding: 5px;
            font-size: 0.8rem;
        }

        .start-btn {
            padding: 6px 12px;
            font-size: 0.75rem;
            width: 100%;
        }

        .mode-options {
            gap: 6px;
            padding: 3px;
        }

        .mode-label {
            font-size: 0.7rem;
            padding: 6px;
        }

        .participants-title {
            font-size: 0.85rem;
            padding: 6px 10px;
        }

        .power-toggle-container {
            gap: 4px;
        }

        .power-toggle-container form {
            margin: 0;
        }

        .mode-pill {
            font-size: 0.65rem;
            padding: 3px 10px;
            gap: 4px;
        }

        .mode-pill::before {
            width: 5px;
            height: 5px;
        }

        /* Asegurar que nada cause scroll horizontal */
        .panel-header,
        .header-content,
        .guest-info-container {
            max-width: 100%;
            overflow: hidden;
        }
    }

    /* Ajustes específicos para pantallas muy pequeñas */
    @media (max-width: 480px) {
        .game-panel-container {
            padding: 0.2rem;
            gap: 2px;
        }

        .back-btn {
            font-size: 0.6rem;
            padding: 2px 5px;
        }

        .guest-info-container {
            padding: 3px 6px;
        }

        .guest-name {
            font-size: 0.7rem;
        }

        .guest-meta {
            font-size: 0.6rem;
        }

        .guest-points {
            font-size: 0.7rem;
            padding: 3px 10px;
        }

        .tendencias-counter {
            padding: 3px 10px;
            min-width: 90px;
        }

        .tendencias-label {
            font-size: 0.55rem;
        }

        .tendencias-value {
            font-size: 0.75rem;
        }

        .mode-pill {
            font-size: 0.6rem;
            padding: 2px 8px;
        }

        .spin-btn {
            font-size: 0.75rem;
            padding: 6px 12px;
            min-width: 100px;
        }

        .option-btn {
            font-size: 0.65rem;
            padding: 4px 2px;
            min-height: 35px;
        }

        .question-text {
            font-size: 0.75rem;
        }

        .slot-special-badge {
            font-size: 0.6rem;
            padding: 3px 8px;
            top: 6px;
            left: 6px;
        }

        .control-panel {
            padding: 6px;
        }

        .control-buttons-grid {
            gap: 3px;
        }

        .control-btn, .apuesta-btn, .descarte-btn, .power-btn-control {
            min-height: 55px;
            max-height: 55px;
            padding: 8px 5px;
            font-size: 0.6rem;
        }

        .control-btn svg {
            width: 14px;
            height: 14px;
        }

        .power-btn-control .indicator {
            width: 12px;
            height: 12px;
        }

        .bonus-btn .light {
            width: 8px;
            height: 8px;
        }

        .bonus-btn .label {
            font-size: 0.65rem;
        }

        .bonus-btn .badge {
            font-size: 0.65rem;
            padding: 1px 5px;
        }
    }
</style>

<div class="game-panel-container">
    <!-- Encabezado con información del invitado -->
    <div class="panel-header">
        <!-- Botón Volver -->
        <div class="back-button-container">
            <a href="{{ route('dashboard') }}" class="back-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>
        </div>

        <div class="header-content">
<div class="guest-info-container">
    @if($activeSession)
        <div class="guest-details">
            <div class="guest-name">{{ $activeSession->guest_name }}</div>
            <div class="guest-meta">
                <div class="guest-meta-item">
                    <strong>Motivo:</strong> {{ $activeSession->motivo->nombre ?? '—' }}
                </div>
                <div class="guest-meta-item">
                <!-- PASTILLA DE MODO (sin texto "Modo:") -->
                <span class="mode-pill {{ $activeSession->modo_juego }}">
                    {{ $activeSession->modo_juego === 'express' ? 'Express' : 'Normal' }}
                </span>
                </div>
            </div>
        </div>
<div class="guest-points">
    <span id="guestPointsValue">{{ $activeSession->guest_points ?? 0 }}</span> pts
</div>
<div class="tendencias-counter">
    <span class="tendencias-label">Tendencias del público</span>
    <span class="tendencias-value">
        <span id="tendenciasAcertadas">{{ $activeSession->tendencias_acertadas ?? 0 }}</span>
        /
        <span id="tendenciasObjetivo">{{ $activeSession->tendencias_objetivo ?? 10 }}</span>
    </span>
</div>
    @else
        <div class="guest-info-placeholder">
            No hay sesión activa
        </div>
    @endif
</div>

<style>
.guest-info-container {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 12px;
    background-color: #1f1b2e;
    border-radius: 12px;
    color: #fff;
}

.guest-details {
    flex-grow: 1;
}

.guest-name {
    font-weight: bold;
    font-size: 1rem;
}

.guest-meta {
    display: flex;
    gap: 8px;
    font-size: 0.85rem;
    margin-top: 2px;
}

.guest-meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
}

/* PASTILLA DE MODO MEJORADA */
.mode-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 14px;
    border-radius: 16px;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.mode-pill::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    animation: pulse 2s ease-in-out infinite;
}

.mode-pill.normal {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #fff;
}

.mode-pill.express {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
}
</style>

        </div>
        
        <!-- Botón Pantalla Completa -->
        <div class="power-toggle-container">
            <button type="button"
                id="fullscreenBtn"
                class="fullscreen-btn-minimal"
                onclick="toggleFullscreen()"
                title="Pantalla completa (Ctrl+F)">
                <!-- Ícono expandir -->
                <svg class="fs-icon expand" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                </svg>
                <!-- Ícono contraer -->
                <svg class="fs-icon compress" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                    <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/>
                </svg>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Panel de Pregunta -->
        <div class="question-panel">
            <span id="slotSpecialBadge" class="slot-special-badge"></span>
            <span id="questionNumberBadge" class="question-number-badge"></span>
            <div id="textoPreguntaPanel" class="question-text">
                {{ $activeSession ? 'Pregunta aún no enviada' : 'Inicie una sesión para comenzar' }}
            </div>
            <div class="options-grid">
                <button type="button" class="option-btn" id="panelA" style="display:none;" {{ !$activeSession ? 'disabled' : '' }}></button>
                <button type="button" class="option-btn" id="panelB" style="display:none;" {{ !$activeSession ? 'disabled' : '' }}></button>
                <button type="button" class="option-btn" id="panelC" style="display:none;" {{ !$activeSession ? 'disabled' : '' }}></button>
                <button type="button" class="option-btn" id="panelD" style="display:none;" {{ !$activeSession ? 'disabled' : '' }}></button>
            </div>
        </div>

        <!-- BOTONERA UNIFICADA -->
        <div class="control-panel">
            @php
            // calcular límites y disponibles
            $apuestaLimite = $activeSession ? ($activeSession->isExpress() ? 1 : 2) : 0;
            $apuestaUsadas = $activeSession ? (int)$activeSession->apuesta_x2_usadas : 0;
            $apuestaDisponibles = max(0, $apuestaLimite - $apuestaUsadas);

            $descarteLimite = 1;
            $descarteUsados = $activeSession ? (int)$activeSession->descarte_usados : 0;
            $descarteDisponibles = max(0, $descarteLimite - $descarteUsados);
            @endphp

            <div class="control-buttons-grid">
                <!-- Botón ON/OFF -->
                @if(!$activeSession)
                    <button type="button" class="power-btn-control off" onclick="toggleStartForm('formStartGame')">
                        <span class="indicator"></span>
                        <span>OFF</span>
                    </button>
                @else
                    <form action="{{ route('game-session.end') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="power-btn-control on">
                            <span class="indicator"></span>
                            <span>ON</span>
                        </button>
                    </form>
                @endif

                <!-- Botón Girar/Parar Ruleta -->
                <button id="spinButton" class="control-btn spin-btn" onclick="toggleSpinButton()" {{ !$activeSession ? 'disabled' : '' }}>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polygon points="10 8 16 12 10 16 10 8"></polygon>
                    </svg>
                    <span>Girar Ruleta</span>
                </button>

                <!-- Botón Revelar -->
                <button type="button" id="revealBtn" class="control-btn reveal-btn" onclick="revelarRespuesta()" {{ !$activeSession ? 'disabled' : '' }}>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <span>Revelar</span>
                </button>

                <!-- Botón Apuesta x2 -->
                <form id="form-apuesta-x2" style="margin: 0;">
                    @csrf
                    <button
                        id="apuesta-btn"
                        type="submit"
                        class="bonus-btn apuesta-btn {{ $activeSession && $activeSession->apuesta_x2_active ? 'on' : 'off' }}"
                        data-active="{{ $activeSession && $activeSession->apuesta_x2_active ? '1' : '0' }}"
                        data-usadas="{{ $apuestaUsadas }}"
                        data-limite="{{ $apuestaLimite }}"
                    >
                        <span class="light"></span>
                        <span class="label">APUESTA</span>
                        <span id="apuesta-badge" class="badge">x{{ $apuestaDisponibles }}</span>
                    </button>
                </form>

                <!-- Botón Descarte -->
                <form id="form-descarte" style="margin: 0;">
                    @csrf
                    <button
                        id="descarte-btn"
                        type="submit"
                        class="bonus-btn descarte-btn {{ $descarteDisponibles > 0 ? 'on' : 'off' }}"
                        data-usados="{{ $descarteUsados }}"
                        data-limite="{{ $descarteLimite }}"
                    >
                        <span class="light"></span>
                        <span class="label">DESCARTE</span>
                        <span id="descarte-badge" class="badge">x{{ $descarteDisponibles }}</span>
                    </button>
                </form>

                <!-- Botón Reset/Refrescar -->
                <button type="button" id="botonReiniciar" class="control-btn reset-btn" {{ !$activeSession ? 'disabled' : '' }}>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"></path>
                    </svg>
                    <span>Refrescar</span>
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
            <!-- Nombre del invitado -->
            <div class="form-group">
                <label>Nombre del invitado:</label>
                <input type="text" name="guest_name" class="form-control" required>
            </div>

            <!-- Motivo -->
            <div class="form-group">
                <label>Motivo:</label>
                <select name="motivo_id" class="form-select" required>
                    <option value="">Seleccione motivo</option>
                    @foreach($motivos as $motivo)
                        <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Modo de juego -->
                <!-- Modo de juego MEJORADO -->
                <div class="form-group">
                    <label>Modo de juego:</label>
                    <div class="mode-switch-container">
                        <div class="mode-options">
                            <div class="mode-option">
                                <input type="radio" id="mode_normal" name="modo_juego" value="normal" checked>
                                <label for="mode_normal" class="mode-label">
                                    <span class="mode-name">Normal</span>
                                    <span class="mode-target">Meta: 25 puntos</span>
                                </label>
                            </div>
                            <div class="mode-option">
                                <input type="radio" id="mode_express" name="modo_juego" value="express">
                                <label for="mode_express" class="mode-label">
                                    <span class="mode-name">Express</span>
                                    <span class="mode-target">Meta: 10 puntos</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Botón Iniciar -->
            <button type="submit" class="start-btn">Iniciar Juego</button>
        </div>
    </form>
</div>

<!-- CSS del toggle -->
<style>
/* SWITCH MEJORADO */
        .mode-switch-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .mode-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            background: rgba(15, 23, 42, 0.4);
            padding: 6px;
            border-radius: 16px;
            border: 2px solid rgba(148, 163, 184, 0.1);
        }

        .mode-option {
            position: relative;
        }

        .mode-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .mode-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 16px 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .mode-option input:checked + .mode-label {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
            transform: translateY(-2px);
        }

        .mode-option input:not(:checked) + .mode-label:hover {
            background: rgba(79, 70, 229, 0.1);
            border-color: rgba(79, 70, 229, 0.3);
        }

        .mode-name {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 4px;
            color: #e2e8f0;
            transition: color 0.3s;
        }

        .mode-option input:checked + .mode-label .mode-name {
            color: #ffffff;
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
        }

        .mode-target {
            font-size: 0.85rem;
            font-weight: 600;
            color: #94a3b8;
            transition: color 0.3s;
        }

        .mode-option input:checked + .mode-label .mode-target {
            color: #c7d2fe;
        }

        .mode-option input:checked + .mode-label {
            opacity: 1;
        }
</style>


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

document.addEventListener('DOMContentLoaded', function () {
    const apuestaForm = document.getElementById('form-apuesta-x2');
    const descarteForm = document.getElementById('form-descarte');

    const apuestaBtn = document.getElementById('apuesta-btn');
    const descarteBtn = document.getElementById('descarte-btn');

    const apuestaBadge = document.getElementById('apuesta-badge');
    const descarteBadge = document.getElementById('descarte-badge');

    // ✅ DESHABILITAR BONOS AL INICIO si no hay pregunta activa
    if (!lastOverlayQuestion) {
        if (apuestaBtn) {
            apuestaBtn.style.opacity = '0.5';
            apuestaBtn.style.pointerEvents = 'none';
        }
        if (descarteBtn) {
            descarteBtn.style.opacity = '0.5';
            descarteBtn.style.pointerEvents = 'none';
        }
    }

    // helper UI update
    function updateApuestaUI(payload) {
        // payload: { apuesta_x2_active, apuesta_x2_usadas, apuesta_x2_disponibles, modo_juego }
        const active = !!payload.apuesta_x2_active;
        const usadas = Number(payload.apuesta_x2_usadas || 0);
        const disponibles = Number(payload.apuesta_x2_disponibles ?? 0);

        apuestaBtn.classList.toggle('on', active);
        apuestaBtn.classList.toggle('off', !active);
        apuestaBtn.dataset.active = active ? '1' : '0';
        apuestaBtn.dataset.usadas = usadas;

        apuestaBadge.textContent = 'x' + disponibles;

        // si no quedan disponibles, deshabilitar el botón visualmente
        apuestaBtn.disabled = disponibles <= 0 && !active;
        if (apuestaBtn.disabled) apuestaBtn.style.opacity = 0.6;
        else apuestaBtn.style.opacity = 1;
    }

    function updateDescarteUI(payload) {
        // payload: { descarte_usados, descarte_disponible (opcional) }
        const usados = Number(payload.descarte_usados || 0);
        // si la API devuelve descarte_disponible usalo, sino calcular por limite=1
        const disponibles = typeof payload.descarte_disponible !== 'undefined'
            ? (payload.descarte_disponible ? 1 : 0)
            : Math.max(0, 1 - usados);

        descarteBtn.classList.toggle('on', disponibles > 0);
        descarteBtn.classList.toggle('off', disponibles <= 0);
        descarteBadge.textContent = 'x' + disponibles;
        descarteBtn.disabled = disponibles <= 0;
        if (descarteBtn.disabled) descarteBtn.style.opacity = 0.6;
        else descarteBtn.style.opacity = 1;
    }

    // Fetch: activar/desactivar apuesta
    apuestaForm.addEventListener('submit', function (e) {
        e.preventDefault();
        apuestaBtn.disabled = true; // evitar double click
        fetch("{{ route('game.toggleApuestaX2') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok || !data.success) {
                const err = data.error || 'No se pudo activar la apuesta';
                alert(err);
            } else {
                updateApuestaUI({
                    apuesta_x2_active: data.apuesta_x2_active,
                    apuesta_x2_usadas: data.apuesta_x2_usadas,
                    apuesta_x2_disponibles: data.apuesta_x2_disponibles,
                    modo_juego: data.modo_juego ?? null
                });

                // opcional: guardar en session JS si necesitás
                // sessionStorage.setItem('guest_apuesta_x2', data.apuesta_x2_active ? '1' : '0');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error en la petición (ver consola).');
        })
        .finally(() => {
            apuestaBtn.disabled = false;
        });
    });

    // Fetch: usar descarte
    descarteForm.addEventListener('submit', function (e) {
        e.preventDefault();
        descarteBtn.disabled = true;
        fetch("{{ route('game.toggleDescarte') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok || !data.success) {
                const err = data.error || 'No se pudo usar el descarte';
                alert(err);
            } else {
                updateDescarteUI({
                    descarte_usados: data.descarte_usados ?? data.descarteUsados,
                    descarte_disponible: data.descarte_disponible ?? (data.descarte_usados < 1)
                });

                // si el descarte lanza una nueva pregunta aquí puedes manejarlo (por ejemplo refrescar overlay)
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error en la petición (ver consola).');
        })
        .finally(() => {
            descarteBtn.disabled = false;
        });
    });

    // Escuchar broadcasts en cuanto-sabe-overlay -> GameBonusUpdated
    try {
        if (window.Echo) {
            window.Echo.channel('cuanto-sabe-overlay')
                .listen('.GameBonusUpdated', (payload) => {
                    // payload ejemplo: { apuesta_x2_active, apuesta_x2_usadas, descarte_usados, modo_juego }
                    // Acomodar nombres si tu backend envía sin prefijos
                    updateApuestaUI({
                        apuesta_x2_active: payload.apuesta_x2_active ?? payload.apuesta_x2Active,
                        apuesta_x2_usadas: payload.apuesta_x2_usadas ?? payload.apuesta_x2Usadas,
                        apuesta_x2_disponibles: (payload.modo_juego ? ((payload.modo_juego === 'express') ? 1 : 2) : (Number(apuestaBtn.dataset.limite || 2))) - (payload.apuesta_x2_usadas ?? 0),
                        modo_juego: payload.modo_juego
                    });

                    updateDescarteUI({
                        descarte_usados: payload.descarte_usados ?? payload.descarteUsados,
                        // si el backend no envía descarte_disponible, lo calculamos
                    });
                });
        }
    } catch (e) {
        console.warn('Echo/Pusher no disponible: live updates deshabilitadas.', e);
    }

});


// Funciones JavaScript
function toggleStartForm() {
    document.getElementById('start-game-form').classList.toggle('show');
}

function toggleSpinButton() {
    const spinButton = document.getElementById('spinButton');
    isSpinning = !isSpinning;

    if (isSpinning) {
        spinButton.innerHTML = `
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="6" y="4" width="4" height="16"></rect>
                <rect x="14" y="4" width="4" height="16"></rect>
            </svg>
            <span>Parar</span>
        `;
        spinButton.classList.add('spinning');
    } else {
        spinButton.innerHTML = `
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polygon points="10 8 16 12 10 16 10 8"></polygon>
            </svg>
            <span>Girar Ruleta</span>
        `;
        spinButton.classList.remove('spinning');
    }

    girarRuleta();
}

function girarRuleta() {
    console.log('🎲 [Panel] Botón Girar Ruleta presionado, isSpinning:', isSpinning);

    fetch('/game-session/girar-ruleta', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ action: isSpinning ? 'start' : 'stop' })
    })
    .then(response => {
        console.log('📡 [Panel] Respuesta recibida:', response.status, response.statusText);
        return response.json();
    })
    .then(data => {
        console.log('✅ [Panel] Ruleta girada exitosamente:', data);
        if (data.error) {
            console.error('❌ [Panel] Error del servidor:', data.error);
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('❌ [Panel] Error en fetch:', error);
        alert('Error al girar ruleta. Ver consola para detalles.');
    });
}

let lastOverlayQuestion = null;
let panelQuestionCounter = {{ $questionCount }};
let currentSelectedOption = null;  // Opción seleccionada en el panel (sincrónico, sin race condition)


// Función de revelar
function revelarRespuesta() {
    if (!lastOverlayQuestion) {
        console.warn("No hay pregunta activa aún");
        return;
    }

    fetch("{{ route('game-session.reveal') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        // Incluir la opción seleccionada en el body para evitar race conditions
        // (el body se establece sincrónicamente, antes de hacer el fetch)
        body: JSON.stringify({ selected_option: currentSelectedOption })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            console.log("Respuesta revelada correctamente");
        } else {
            console.warn("No se pudo revelar:", data.error);
        }
    })
    .catch(err => console.error("Error al revelar:", err));
}

// Asignar onclick
const revealBtn = document.getElementById('revealBtn');
if (revealBtn) revealBtn.onclick = revelarRespuesta;


let overlayResetting = false;

function reiniciarOverlay() {
    if (overlayResetting) return;
    overlayResetting = true;

    fetch("{{ route('game-session.overlay-reset') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        }
    }).finally(() => {
        overlayResetting = false;
    });

    document.getElementById('textoPreguntaPanel').textContent = 'Pregunta aún no enviada';

    // Limpiar slot especial badge
    const slotBadge = document.getElementById('slotSpecialBadge');
    if (slotBadge) {
        slotBadge.textContent = '';
        slotBadge.classList.remove('active');
    }

    // Mantener el badge visible con el número actual (no resetear)
    // El contador se mantiene sincronizado con la DB

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

// Asociar el listener después de que el DOM esté cargado
document.addEventListener('DOMContentLoaded', () => {
    const boton = document.getElementById('botonReiniciar');
    if (boton) {
        boton.addEventListener('click', reiniciarOverlay);
    }

    // Mostrar badge de número de pregunta si ya hay preguntas guardadas
    const badge = document.getElementById('questionNumberBadge');
    if (badge && panelQuestionCounter > 0) {
        badge.textContent = `Pregunta ${panelQuestionCounter}/15`;
        badge.classList.add('active');
        if (panelQuestionCounter >= 15) {
            badge.style.background = 'rgba(255, 68, 68, 0.2)';
            badge.style.borderColor = 'rgba(255, 68, 68, 0.5)';
            badge.style.color = '#ff6666';
        }
    }

    // ✅ Restaurar pregunta activa al refrescar la página
    // Si hay una pregunta en curso en la BD, volvemos a mostrarla en el panel
    // sin necesidad de esperar el próximo evento Pusher.
    fetch('/overlay/api/pregunta')
        .then(r => r.json())
        .then(data => {
            if (!data || !data.pregunta_id || !data.opciones || !data.opciones.length) return;

            // Restaurar variable global para que revelarRespuesta() funcione
            lastOverlayQuestion = data;

            // Restaurar texto de pregunta
            const txt = document.getElementById('textoPreguntaPanel');
            if (txt) txt.textContent = data.pregunta || '';

            // Restaurar slot especial badge
            const slotBadge = document.getElementById('slotSpecialBadge');
            if (slotBadge) {
                const indicator = data.slot_special || data.special_indicator || '';
                if (indicator) {
                    slotBadge.textContent = indicator;
                    slotBadge.classList.add('active');
                } else {
                    slotBadge.textContent = '';
                    slotBadge.classList.remove('active');
                }
            }

            // Restaurar botones de opciones
            ['A','B','C','D'].forEach(l => {
                const btn = document.getElementById('panel'+l);
                if (!btn) return;
                const opcion = data.opciones.find(op => op.label === l);
                if (opcion) {
                    btn.style.display = '';
                    const base = `${l}: ${opcion.texto}`;
                    btn.dataset.baseText = base;
                    btn.textContent = base;
                } else {
                    btn.style.display = 'none';
                    btn.dataset.baseText = '';
                    btn.textContent = '';
                }
                btn.classList.remove('selected', 'trend');
            });

            // Habilitar botones de bonos
            const apuestaBtn = document.getElementById('apuesta-btn');
            const descarteBtn = document.getElementById('descarte-btn');
            if (apuestaBtn) { apuestaBtn.style.opacity = '1'; apuestaBtn.style.pointerEvents = 'auto'; }
            if (descarteBtn) { descarteBtn.style.opacity = '1'; descarteBtn.style.pointerEvents = 'auto'; }

            // Actualizar badge (sin incrementar: el contador ya viene de la BD al renderizar)
            const badgeEl = document.getElementById('questionNumberBadge');
            if (badgeEl && panelQuestionCounter > 0) {
                badgeEl.textContent = `Pregunta ${panelQuestionCounter}/15`;
                badgeEl.classList.add('active');
            }

            console.log('[Panel] Pregunta restaurada desde BD tras recarga:', data.pregunta_id);
        })
        .catch(e => console.warn('[Panel] No se pudo restaurar pregunta activa:', e));
});


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

                // ✅ Guardar sincrónicamente ANTES del fetch para evitar race condition con reveal
                currentSelectedOption = l;

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
    const overlay = Echo.channel('cuanto-sabe-overlay');
    Echo.channel('cuanto-sabe-overlay')
        .listen('.GameBonusUpdated', (event) => {
            console.log('[PANEL] Evento bonus recibido:', event);

            // Actualizar botón Apuesta x2
            const btnApuesta = document.querySelector('.apuesta-btn');
            if(btnApuesta){
                if(event.apuesta_x2_active){
                    btnApuesta.classList.add('on');
                    btnApuesta.classList.remove('off');
                } else {
                    btnApuesta.classList.remove('on');
                    btnApuesta.classList.add('off');
                }
            }

            // Actualizar botón Descarte
            const btnDescarte = document.querySelector('.descarte-btn');
            if(btnDescarte){
                if(event.descarte_usados > 0){
                    btnDescarte.classList.add('on');
                    btnDescarte.classList.remove('off');
                } else {
                    btnDescarte.classList.remove('on');
                    btnDescarte.classList.add('off');
                }
            }

            // Actualizar contador de tendencias en vivo
            if (typeof event.tendencias_acertadas !== 'undefined') {
                const acertadasEl = document.getElementById('tendenciasAcertadas');
                const objetivoEl  = document.getElementById('tendenciasObjetivo');
                if (acertadasEl) acertadasEl.textContent = event.tendencias_acertadas;
                if (objetivoEl)  objetivoEl.textContent  = event.tendencias_objetivo;
            }
        });

    // Nueva pregunta
    overlay.listen('.nueva-pregunta', (e) => {
        const data = e.data || e || {};
        const pregunta = data.pregunta || (data.data ? data.data.pregunta : '') || '';
        const opciones = data.opciones || (data.data ? data.data.opciones : []) || [];
        const slotSpecial = data.slot_special || (data.data ? data.data.slot_special : '') || '';

        // Actualizar contador de tendencias si el slot redujo el objetivo (Solo yo / Pregunta de oro)
        if (typeof data.tendencias_acertadas !== 'undefined') {
            const acertadasEl = document.getElementById('tendenciasAcertadas');
            const objetivoEl  = document.getElementById('tendenciasObjetivo');
            if (acertadasEl) acertadasEl.textContent = data.tendencias_acertadas;
            if (objetivoEl)  objetivoEl.textContent  = data.tendencias_objetivo;
        }

        // 🔥 GUARDAR EN VARIABLE GLOBAL
        lastOverlayQuestion = data;
        console.log('✅ Pregunta guardada en lastOverlayQuestion:', lastOverlayQuestion);

        // ✅ HABILITAR BOTONES DE BONOS cuando hay pregunta activa
        const apuestaBtn = document.getElementById('apuesta-btn');
        const descarteBtn = document.getElementById('descarte-btn');
        if (apuestaBtn && !apuestaBtn.disabled) {
            apuestaBtn.style.opacity = '1';
            apuestaBtn.style.pointerEvents = 'auto';
        }
        if (descarteBtn && !descarteBtn.disabled) {
            descarteBtn.style.opacity = '1';
            descarteBtn.style.pointerEvents = 'auto';
        }

        const txt = document.getElementById('textoPreguntaPanel');
        if (txt) txt.textContent = pregunta || 'Pregunta aún no enviada';

        // ✅ MOSTRAR SLOT ESPECIAL si existe
        const slotBadge = document.getElementById('slotSpecialBadge');
        if (slotBadge) {
            if (slotSpecial) {
                slotBadge.textContent = slotSpecial;
                slotBadge.classList.add('active');
            } else {
                slotBadge.textContent = '';
                slotBadge.classList.remove('active');
            }
        }

        // Incrementar contador solo para preguntas reales (con opciones)
        // Los eventos de modos especiales (primer giro) no tienen opciones y no cuentan
        if (opciones && opciones.length > 0) {
            panelQuestionCounter++;
        }
        const badge = document.getElementById('questionNumberBadge');
        if (badge && panelQuestionCounter > 0) {
            badge.textContent = `Pregunta ${panelQuestionCounter}/15`;
            badge.classList.add('active');
            badge.style.background = '';
            badge.style.borderColor = '';
            badge.style.color = '';
            badge.title = '';
        }

        ['A','B','C','D'].forEach((l) => {
            const btn = document.getElementById('panel'+l);
            if (!btn) return;
            const opcion = opciones.find(op => op.label === l);

            if (opcion) {
                btn.style.display = '';
                const base = `${l}: ${opcion.texto}`;
                btn.dataset.baseText = base;
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
        })
        .then(res => res.json())
        .then(result => {
            console.log('✅ Pregunta sincronizada con backend:', result);
        })
        .catch(err => console.error('❌ Error al sincronizar:', err));
    });

    // Tendencia actualizada
    overlay.listen('.tendencia-actualizada', (e) => {
        const payload = e.data || e || {};
        const tendencia = payload.tendencia || payload;
        const label = tendencia.option_label || payload.option_label;
        const total = tendencia.total || payload.total || 0;

        console.log('✅ Tendencia actualizada recibida en panel:', { label, total, payload });

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
                console.log('✅ Tendencia aplicada al botón:', label);
            }
        }
    });

    // Reset overlay
    overlay.listen('.overlay-reset', () => {
        lastOverlayQuestion = null;       // Limpiar al resetear
        currentSelectedOption = null;     // Limpiar opción seleccionada
        console.log('🔄 Overlay reseteado, lastOverlayQuestion y currentSelectedOption limpiados');

        // ✅ DESHABILITAR BOTONES DE BONOS cuando no hay pregunta
        const apuestaBtn = document.getElementById('apuesta-btn');
        const descarteBtn = document.getElementById('descarte-btn');
        if (apuestaBtn) {
            apuestaBtn.style.opacity = '0.5';
            apuestaBtn.style.pointerEvents = 'none';
        }
        if (descarteBtn) {
            descarteBtn.style.opacity = '0.5';
            descarteBtn.style.pointerEvents = 'none';
        }

        // ✅ LIMPIAR SLOT ESPECIAL
        const slotBadge = document.getElementById('slotSpecialBadge');
        if (slotBadge) {
            slotBadge.textContent = '';
            slotBadge.classList.remove('active');
        }

        reiniciarOverlay();
    });
}

    // Deshabilitar funcionalidades si no hay sesión activa
    if (!@json($activeSession ? true : false)) {
        document.querySelectorAll('.spin-btn, .reveal-btn, .reset-btn, .option-btn').forEach(btn => {
            btn.disabled = true;
        });
    }
});

        // Animación al cambiar de modo
        document.querySelectorAll('input[name="modo_juego"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const label = this.nextElementSibling;
                label.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    label.style.transform = '';
                }, 200);
            });
        });
if (window.Echo) {
    Echo.channel('cuanto-sabe-overlay')
        .listen('.GuestPointsUpdated', e => {
            const val = document.getElementById('guestPointsValue');
            if (val) {
                val.textContent = e.points; // actualiza el valor
            }
        });
}

if (window.Echo) {
    Echo.channel('cuanto-sabe-overlay')
        .listen('.revelar-respuesta', (e) => {
            const payload = e.data || e || {};

            // Actualizar texto de tendencia en botones
            ['A','B','C','D'].forEach(l => {
                const btn = document.getElementById('panel'+l);
                if (!btn) return;
                btn.classList.remove('trend');
                if (btn.dataset.baseText) btn.textContent = btn.dataset.baseText;
            });

            const tendenciaOption = payload.tendencia?.option ?? payload.tendencia?.option_label;
            if (tendenciaOption) {
                const trendBtn = document.getElementById('panel' + tendenciaOption);
                if (trendBtn) {
                    trendBtn.classList.add('trend');
                    const base = trendBtn.dataset.baseText || '';
                    trendBtn.textContent = `${base} — Tendencia (${payload.tendencia.votes ?? payload.tendencia.total ?? ''})`;
                }
            }

            // ✅ ACTUALIZAR CONTADOR DE TENDENCIAS
            if (typeof payload.tendencias_acertadas !== 'undefined') {
                const acertadasEl = document.getElementById('tendenciasAcertadas');
                const objetivoEl = document.getElementById('tendenciasObjetivo');

                if (acertadasEl) acertadasEl.textContent = payload.tendencias_acertadas;
                if (objetivoEl) objetivoEl.textContent = payload.tendencias_objetivo;

                console.log('✅ Tendencias actualizadas:', {
                    acertadas: payload.tendencias_acertadas,
                    objetivo: payload.tendencias_objetivo,
                    restantes: payload.tendencias_restantes
                });

                if (payload.publico_gano) {
                    console.log('🎉 ¡EL PÚBLICO GANÓ!');
                }
            }

            // ✅ ACTUALIZAR BADGE DE PREGUNTA con conteo real desde BD (nunca retrocede)
            if (typeof payload.question_count !== 'undefined') {
                panelQuestionCounter = Math.max(panelQuestionCounter, payload.question_count);
                const badge = document.getElementById('questionNumberBadge');
                if (badge) {
                    const limit = payload.question_limit || 15;
                    badge.textContent = `Pregunta ${panelQuestionCounter}/${limit}`;
                    badge.classList.add('active');
                    if (payload.question_limit_reached) {
                        badge.style.background = 'rgba(255, 68, 68, 0.2)';
                        badge.style.borderColor = 'rgba(255, 68, 68, 0.5)';
                        badge.style.color = '#ff6666';
                        badge.title = 'Límite de 15 preguntas alcanzado';
                    }
                }
            }

            console.log('[revelar-respuesta]', payload);
        });
}
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

@endsection