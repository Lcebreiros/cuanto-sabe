<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Final Scores - Cuanto Sabe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">

<style>
html, body {
    width: 100vw;
    height: 100vh;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background: transparent !important;
    font-family: 'Orbitron', Arial, sans-serif;
}

body {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    box-sizing: border-box;
}

.final-scores-container {
    width: 100%;
    max-width: 1400px;
    max-height: calc(100vh - 30px);
    display: grid;
    grid-template-rows: 1fr auto;
    gap: 15px;
    animation: fadeIn 0.8s ease-out;
    box-sizing: border-box;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* === PANEL IZQUIERDO: INVITADO === */
.guest-panel {
    background: linear-gradient(135deg, rgba(11, 21, 48, 0.95) 0%, rgba(18, 55, 92, 0.95) 100%);
    border: 2px solid #00f0ff;
    border-radius: 16px;
    padding: 15px;
    box-shadow: 0 0 30px #00f0ffaa, inset 0 0 40px rgba(0, 240, 255, 0.1);
    display: flex;
    flex-direction: column;
    gap: 8px;
    height: 100%;
    overflow: hidden;
}

.guest-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    border-bottom: 2px solid #00f0ff44;
    padding-bottom: 10px;
}

.guest-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.guest-title {
    font-size: 0.75rem;
    font-weight: 900;
    color: #00f0ff;
    text-shadow: 0 0 20px #00f0ff, 0 0 40px #00f0ff88;
    margin: 0;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.guest-name {
    font-size: 1.2rem;
    font-weight: 900;
    color: #fff;
    text-shadow: 0 0 25px #ffe47a, 0 0 50px #ffe47a88;
    margin: 0;
    letter-spacing: 1px;
}

.guest-score-display {
    background: linear-gradient(90deg, #ffe47a 0%, #e6be2f 100%);
    border-radius: 8px;
    padding: 6px 14px;
    text-align: center;
    box-shadow: 0 0 20px #ffe47a99, inset 0 0 20px rgba(255, 228, 122, 0.3);
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 5px;
    flex-shrink: 0;
    white-space: nowrap;
}

.score-text {
    font-size: 0.75rem;
    font-weight: 700;
    color: #333;
    margin: 0;
    letter-spacing: 0.3px;
}

.score-value {
    font-size: 1.5rem;
    font-weight: 900;
    color: #000;
    margin: 0 2px;
    text-shadow: 0 0 10px rgba(255, 228, 122, 0.8);
    letter-spacing: -1px;
    line-height: 1;
}

.score-unit {
    font-size: 0.75rem;
    font-weight: 700;
    color: #333;
    margin: 0;
    letter-spacing: 0.3px;
}

/* === PANEL INFERIOR: TOP 3 PODIO === */
.ranking-panel {
    background: linear-gradient(135deg, rgba(11, 21, 48, 0.95) 0%, rgba(18, 55, 92, 0.95) 100%);
    border: 2px solid #19ff8c;
    border-radius: 16px;
    padding: 15px 20px;
    box-shadow: 0 0 30px #19ff8caa, inset 0 0 40px rgba(25, 255, 140, 0.1);
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-height: 280px;
}

.ranking-header {
    text-align: center;
    border-bottom: 2px solid #19ff8c44;
    padding-bottom: 8px;
}

.ranking-title {
    font-size: 0.9rem;
    font-weight: 900;
    color: #19ff8c;
    text-shadow: 0 0 20px #19ff8c, 0 0 40px #19ff8c88;
    margin: 0;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.ranking-subtitle {
    font-size: 0.7rem;
    color: #36d1ff;
    margin: 3px 0 0 0;
    letter-spacing: 1px;
}

.podium {
    display: flex;
    flex-direction: row;
    align-items: flex-end;
    justify-content: center;
    gap: 15px;
    flex: 1;
    padding: 0 20px;
}

.podium-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    position: relative;
    overflow: visible;
    animation: slideUp 0.8s ease-out backwards;
    flex: 0 0 auto;
    width: 140px;
}

.podium-item:nth-child(1) {
    animation-delay: 0.4s; /* Top 2 - Plata - Izquierda */
    order: 1;
}

.podium-item:nth-child(2) {
    animation-delay: 0.2s; /* Top 1 - Oro - Centro */
    order: 2;
}

.podium-item:nth-child(3) {
    animation-delay: 0.6s; /* Top 3 - Bronce - Derecha */
    order: 3;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.podium-pedestal {
    width: 100%;
    border-radius: 8px 8px 0 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 12px 8px 8px 8px;
    position: relative;
}

.podium-item.first .podium-pedestal {
    background: linear-gradient(180deg, rgba(255, 228, 122, 0.3) 0%, rgba(230, 190, 47, 0.25) 100%);
    border: 2px solid #ffe47a;
    border-bottom: 4px solid #ffe47a;
    box-shadow: 0 0 25px #ffe47a88, inset 0 -3px 15px rgba(255, 228, 122, 0.2);
    height: 160px;
}

.podium-item.second .podium-pedestal {
    background: linear-gradient(180deg, rgba(192, 192, 192, 0.3) 0%, rgba(169, 169, 169, 0.25) 100%);
    border: 2px solid #c0c0c0;
    border-bottom: 4px solid #c0c0c0;
    box-shadow: 0 0 20px #c0c0c088, inset 0 -3px 15px rgba(192, 192, 192, 0.2);
    height: 130px;
}

.podium-item.third .podium-pedestal {
    background: linear-gradient(180deg, rgba(205, 127, 50, 0.3) 0%, rgba(184, 115, 51, 0.25) 100%);
    border: 2px solid #cd7f32;
    border-bottom: 4px solid #cd7f32;
    box-shadow: 0 0 20px #cd7f3288, inset 0 -3px 15px rgba(205, 127, 50, 0.2);
    height: 100px;
}

.rank-badge {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 900;
    position: absolute;
    top: -25px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
}

.podium-item.first .rank-badge {
    background: linear-gradient(135deg, #ffe47a 0%, #e6be2f 100%);
    box-shadow: 0 0 20px #ffe47a, inset 0 0 20px rgba(230, 190, 47, 0.5);
    border: 3px solid #ffd700;
}

.podium-item.second .rank-badge {
    background: linear-gradient(135deg, #e8e8e8 0%, #c0c0c0 100%);
    box-shadow: 0 0 20px #c0c0c0, inset 0 0 20px rgba(192, 192, 192, 0.5);
    border: 3px solid #c0c0c0;
}

.podium-item.third .rank-badge {
    background: linear-gradient(135deg, #cd7f32 0%, #b87333 100%);
    box-shadow: 0 0 20px #cd7f32, inset 0 0 20px rgba(205, 127, 50, 0.5);
    border: 3px solid #cd7f32;
}

.participant-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
    align-items: center;
    text-align: center;
    width: 100%;
    margin-top: 8px;
}

.participant-name {
    font-size: 0.85rem;
    font-weight: 900;
    color: #fff;
    margin: 0;
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    word-break: break-word;
    line-height: 1.2;
}

.participant-details {
    font-size: 0.65rem;
    color: #36d1ff;
    margin: 0;
}

.participant-score {
    font-size: 1.3rem;
    font-weight: 900;
    text-align: center;
    margin-top: auto;
    padding-top: 6px;
}

.podium-item.first .participant-score {
    color: #ffe47a;
    text-shadow: 0 0 20px #ffe47a;
}

.podium-item.second .participant-score {
    color: #c0c0c0;
    text-shadow: 0 0 20px #c0c0c0;
}

.podium-item.third .participant-score {
    color: #cd7f32;
    text-shadow: 0 0 20px #cd7f32;
}

.no-data {
    text-align: center;
    color: #36d1ff;
    font-size: 1.2rem;
    padding: 40px;
    opacity: 0.7;
}

/* === MAPPING DE PREGUNTAS === */
.questions-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3px 8px;
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 5px;
    min-height: 0;
    align-content: start;
}

.questions-list::-webkit-scrollbar {
    width: 6px;
}

.questions-list::-webkit-scrollbar-track {
    background: rgba(0, 240, 255, 0.1);
    border-radius: 3px;
}

.questions-list::-webkit-scrollbar-thumb {
    background: #00f0ff;
    border-radius: 3px;
}

.question-item {
    display: flex;
    gap: 5px;
    padding: 3px 6px;
    border-radius: 4px;
    background: rgba(0, 240, 255, 0.05);
    border-left: 2px solid;
    font-size: 0.65rem;
    height: fit-content;
}

.question-item.correct {
    border-left-color: #13ff79;
}

.question-item.incorrect {
    border-left-color: #ff3f34;
}

.q-number {
    font-weight: 700;
    color: #36d1ff;
    min-width: 18px;
    font-size: 0.6rem;
    flex-shrink: 0;
}

.q-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.q-text {
    color: #fff;
    font-size: 0.65rem;
    line-height: 1.15;
    opacity: 0.9;
}

.q-answer {
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 700;
    font-size: 0.8rem;
}

.answer-correct {
    color: #13ff79;
    text-shadow: 0 0 10px #13ff79;
}

.answer-wrong {
    color: #ff3f34;
    text-shadow: 0 0 10px #ff3f34;
    text-decoration: line-through;
}

.arrow {
    color: #36d1ff;
    font-size: 0.7rem;
}

.q-options {
    display: flex;
    flex-wrap: wrap;
    gap: 3px;
    margin-top: 2px;
}

.option-line {
    font-size: 0.6rem;
    line-height: 1.1;
    padding: 1px 4px;
    border-radius: 3px;
    display: inline-block;
    white-space: nowrap;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
}

.opt-correct {
    color: #13ff79;
    font-weight: 700;
    background: rgba(19, 255, 121, 0.2);
    text-shadow: 0 0 6px #13ff79;
    border: 1px solid rgba(19, 255, 121, 0.3);
}

.opt-incorrect {
    color: #ff3f34;
    font-weight: 700;
    background: rgba(255, 63, 52, 0.2);
    text-shadow: 0 0 6px #ff3f34;
    border: 1px solid rgba(255, 63, 52, 0.3);
}

/* Responsive */
@media (max-width: 1200px) {
    .final-scores-container {
        grid-template-rows: 1fr auto;
        gap: 15px;
    }

    .guest-panel {
        max-height: 60vh;
    }

    .ranking-panel {
        max-height: 35vh;
    }

    .guest-header {
        flex-direction: row;
        flex-wrap: wrap;
    }

    .guest-info {
        flex: 1;
        min-width: 200px;
    }

    .guest-score-display {
        flex-shrink: 1;
    }

    .podium {
        gap: 10px;
        padding: 0 10px;
    }

    .podium-item {
        width: 110px;
    }

    .podium-item.first .podium-pedestal {
        height: 140px;
    }

    .podium-item.second .podium-pedestal {
        height: 110px;
    }

    .podium-item.third .podium-pedestal {
        height: 85px;
    }

    /* Mantener 2 columnas en preguntas incluso en pantallas pequeñas */
    .questions-list {
        grid-template-columns: 1fr 1fr;
        gap: 3px 6px;
    }

    .question-item {
        padding: 3px 6px;
    }

    .q-text {
        font-size: 0.6rem;
    }

    .option-line {
        font-size: 0.55rem;
        padding: 1px 3px;
    }
}
</style>
</head>
<body>
    <div class="final-scores-container">
        <!-- PANEL IZQUIERDO: INVITADO -->
        <div class="guest-panel">
            <div class="guest-header">
                <div class="guest-info">
                    <div class="guest-title">Invitado</div>
                    <div class="guest-name">{{ $guestName }}</div>
                </div>
                <div class="guest-score-display">
                    <span class="score-text">Puntuación Final:</span>
                    <span class="score-value">{{ $guestScore }}</span>
                    <span class="score-unit">puntos</span>
                </div>
            </div>

            <!-- MAPPING DE PREGUNTAS -->
            @if($guestAnswers->count() > 0)
            <div class="questions-list">
                @foreach($guestAnswers as $index => $answer)
                <div class="question-item {{ $answer['is_correct'] ? 'correct' : 'incorrect' }}">
                    <div class="q-number">Q{{ $index + 1 }}</div>
                    <div class="q-content">
                        <div class="q-text">{{ $answer['question_text'] }}</div>
                        <div class="q-options">
                            @php
                                // Mostrar solo la opción correcta, o la seleccionada + correcta si falló
                                $optionsToShow = [];

                                // Si contestó incorrectamente, mostrar la seleccionada (incorrecta)
                                if (!$answer['is_correct'] && !empty($answer['selected_option_text'])) {
                                    $optionsToShow[] = [
                                        'text' => $answer['selected_option_text'],
                                        'class' => 'opt-incorrect'
                                    ];
                                }

                                // Siempre mostrar la correcta
                                $optionsToShow[] = [
                                    'text' => $answer['correct_text'],
                                    'class' => 'opt-correct'
                                ];
                            @endphp
                            @foreach($optionsToShow as $option)
                                <span class="option-line {{ $option['class'] }}">{{ $option['text'] }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- PANEL DERECHO: TOP 3 -->
        <div class="ranking-panel">
            <div class="ranking-header">
                <div class="ranking-title">Top 3 Participantes</div>
                <div class="ranking-subtitle">Los mejores puntajes</div>
            </div>

            <div class="podium">
                @if($topParticipants->count() > 0)
                    @foreach($topParticipants as $index => $participant)
                        @php
                            $rankClass = match($index) {
                                0 => 'first',
                                1 => 'second',
                                2 => 'third',
                                default => ''
                            };
                            $rankIcon = match($index) {
                                0 => '🥇',
                                1 => '🥈',
                                2 => '🥉',
                                default => ($index + 1)
                            };
                        @endphp
                        <div class="podium-item {{ $rankClass }}">
                            <div class="podium-pedestal">
                                <div class="rank-badge">{{ $rankIcon }}</div>
                                <div class="participant-info">
                                    <div class="participant-name">{{ $participant->username }}</div>
                                    <div class="participant-details">
                                        ✓ {{ $participant->correct_answers }} · ✗ {{ $participant->incorrect_answers }}
                                    </div>
                                </div>
                                <div class="participant-score">{{ $participant->puntaje }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-data">No hay participantes registrados</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
