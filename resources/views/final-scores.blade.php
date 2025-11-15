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
    padding: 20px;
    box-shadow: 0 0 30px #00f0ffaa, inset 0 0 40px rgba(0, 240, 255, 0.1);
    display: flex;
    flex-direction: column;
    gap: 20px;
    height: 100%;
    overflow: hidden;
}

.guest-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0;
    border-bottom: 2px solid #00f0ff44;
    padding-bottom: 15px;
    padding-top: 0;
}

.guest-info {
    display: flex;
    flex-direction: column;
    gap: 0;
    align-items: center;
    text-align: center;
}

.guest-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #00f0ff;
    text-shadow: 0 0 20px #00f0ff, 0 0 40px #00f0ff88;
    margin: 0;
    letter-spacing: 2px;
    text-transform: uppercase;
    opacity: 0.7;
}

.guest-name {
    font-size: 3rem;
    font-weight: 900;
    color: #fff;
    text-shadow: 0 0 30px #ffe47a, 0 0 60px #ffe47a88;
    margin: 0;
    letter-spacing: 2px;
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
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
    overflow: hidden;
    position: relative;
}

/* Gradient deshabilitado - ahora usamos fade en las preguntas */

.questions-scroll-container {
    display: flex;
    flex-direction: column;
    gap: 8px;
    animation: scrollQuestions 60s cubic-bezier(0.25, 0.1, 0.25, 1) forwards;
}

@keyframes scrollQuestions {
    0% {
        transform: translateY(0);
    }
    5% {
        transform: translateY(0);
    }
    70% {
        transform: translateY(calc(-100% + 100vh - 150px));
    }
    100% {
        transform: translateY(calc(-100% + 100vh - 150px));
    }
}

.question-item {
    display: flex;
    gap: 8px;
    padding: 12px 16px;
    border-radius: 8px;
    background: rgba(0, 240, 255, 0.05);
    border-left: 3px solid;
    font-size: 0.85rem;
    min-height: fit-content;
    flex-shrink: 0;
    animation: fadeOutQuestion 60s linear forwards;
}

@keyframes fadeOutQuestion {
    0%, 90% {
        opacity: 1;
    }
    95%, 100% {
        opacity: 0;
    }
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
    min-width: 25px;
    font-size: 0.75rem;
    flex-shrink: 0;
}

/* Resultado Final */
.final-result {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 30px;
    padding: 80px 20px;
    margin-top: 60px;
    flex-shrink: 0;
    min-height: 400px;
}

.final-score-big {
    font-size: 4.5rem;
    font-weight: 900;
    color: #ffe47a;
    text-shadow: 0 0 40px #ffe47a, 0 0 80px #ffe47a88;
    opacity: 0;
    animation: showThenFadeScore 7s ease-in-out forwards;
    animation-delay: 58s;
}

@keyframes showThenFadeScore {
    0% {
        opacity: 0;
        transform: scale(0.8);
    }
    10% {
        opacity: 1;
        transform: scale(1);
    }
    15%, 70% {
        opacity: 1;
        transform: scale(1);
    }
    80% {
        opacity: 1;
        transform: scale(1.05);
    }
    90%, 100% {
        opacity: 0;
        transform: scale(1.1);
    }
}

.result-status {
    font-size: 6rem;
    font-weight: 900;
    text-align: center;
    letter-spacing: 5px;
    text-transform: uppercase;
    opacity: 0;
    animation: showResultDelayed 4s ease-in-out forwards;
    animation-delay: 65.5s;
}

.result-status.won {
    color: #13ff79;
    text-shadow: 0 0 50px #13ff79, 0 0 100px #13ff79;
}

.result-status.lost {
    color: #ff3f34;
    text-shadow: 0 0 50px #ff3f34, 0 0 100px #ff3f34;
}

@keyframes showResultDelayed {
    0% {
        opacity: 0;
        transform: scale(0.5);
    }
    20% {
        opacity: 1;
        transform: scale(1.3);
    }
    35% {
        opacity: 1;
        transform: scale(0.95);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    65% {
        opacity: 1;
        transform: scale(1);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.final-stats {
    display: flex;
    gap: 30px;
    font-size: 1.2rem;
    opacity: 0;
    animation: showThenFadeScore 7s ease-in-out forwards;
    animation-delay: 58s;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.stat-item.correct {
    color: #13ff79;
}

.stat-item.incorrect {
    color: #ff3f34;
}

.q-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.q-text {
    color: #fff;
    font-size: 0.9rem;
    line-height: 1.3;
    opacity: 0.95;
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
    gap: 6px;
    margin-top: 6px;
}

.option-line {
    font-size: 0.75rem;
    line-height: 1.2;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
    max-width: 100%;
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
    .question-item {
        padding: 10px 12px;
    }

    .q-text {
        font-size: 0.8rem;
    }

    .option-line {
        font-size: 0.7rem;
        padding: 3px 6px;
    }

    .result-status {
        font-size: 3rem;
    }

    .final-score-big {
        font-size: 2.5rem;
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
            </div>

            <!-- MAPPING DE PREGUNTAS -->
            @if($guestAnswers->count() > 0)
            @php
                $totalQuestions = $guestAnswers->count();
                $correctCount = $guestAnswers->filter(fn($a) => $a['is_correct'])->count();
                $incorrectCount = $totalQuestions - $correctCount;
                $winThreshold = ceil($totalQuestions * 0.5); // 50% o más para ganar
                $didWin = $correctCount >= $winThreshold;
            @endphp
            <div class="questions-list">
                <div class="questions-scroll-container">
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

                    <!-- RESULTADO FINAL -->
                    <div class="final-result">
                        <div class="final-score-big">{{ $guestScore }} puntos</div>
                        <div class="final-stats">
                            <div class="stat-item correct">
                                <span>✓</span>
                                <span>{{ $correctCount }} Correctas</span>
                            </div>
                            <div class="stat-item incorrect">
                                <span>✗</span>
                                <span>{{ $incorrectCount }} Incorrectas</span>
                            </div>
                        </div>
                        <div class="result-status {{ $didWin ? 'won' : 'lost' }}">
                            {{ $didWin ? '¡GANASTE!' : 'PERDISTE' }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</body>
</html>
