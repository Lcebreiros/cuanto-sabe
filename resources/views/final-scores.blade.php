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
    max-width: 1200px;
    max-height: calc(100vh - 30px);
    display: flex;
    flex-direction: column;
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
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border-bottom: 2px solid #00f0ff44;
    padding-bottom: 12px;
}

.guest-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
    align-items: center;
    text-align: center;
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

.opt-neutral {
    color: #999;
    opacity: 0.5;
    background: rgba(255, 255, 255, 0.02);
}

/* Responsive */
@media (max-width: 1200px) {
    .guest-header {
        flex-direction: column;
        align-items: center;
    }

    .guest-info {
        align-items: center;
        text-align: center;
    }

    .guest-score-display {
        width: auto;
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
                            @foreach($answer['all_options'] as $option)
                                @php
                                    $isCorrect = ($option === $answer['correct_text']);
                                    // Solo marcar como incorrecta si tenemos el texto de la opción seleccionada
                                    $isWrong = (
                                        !$answer['is_correct'] &&
                                        !empty($answer['selected_option_text']) &&
                                        $option === $answer['selected_option_text']
                                    );

                                    if ($isWrong) {
                                        $class = 'opt-incorrect';
                                    } elseif ($isCorrect) {
                                        $class = 'opt-correct';
                                    } else {
                                        $class = 'opt-neutral';
                                    }
                                @endphp
                                <span class="option-line {{ $class }}">{{ $option }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</body>
</html>
