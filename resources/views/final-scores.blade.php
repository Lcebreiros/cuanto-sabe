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
    padding: 20px;
    box-sizing: border-box;
}

.final-scores-container {
    width: 100%;
    max-width: 1400px;
    height: 100vh;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
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
    gap: 15px;
    height: 100%;
    overflow: hidden;
}

.guest-header {
    text-align: center;
    border-bottom: 2px solid #00f0ff44;
    padding-bottom: 12px;
}

.guest-title {
    font-size: 1rem;
    font-weight: 900;
    color: #00f0ff;
    text-shadow: 0 0 20px #00f0ff, 0 0 40px #00f0ff88;
    margin: 0 0 8px 0;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.guest-name {
    font-size: 1.5rem;
    font-weight: 900;
    color: #fff;
    text-shadow: 0 0 25px #ffe47a, 0 0 50px #ffe47a88;
    margin: 0;
    letter-spacing: 1px;
}

.guest-score-display {
    background: linear-gradient(90deg, #ffe47a 0%, #e6be2f 100%);
    border-radius: 10px;
    padding: 12px 15px;
    text-align: center;
    box-shadow: 0 0 20px #ffe47a99, inset 0 0 20px rgba(255, 228, 122, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.score-label {
    font-size: 0.8rem;
    font-weight: 700;
    color: #333;
    margin: 0;
    letter-spacing: 1px;
}

.score-value {
    font-size: 1.8rem;
    font-weight: 900;
    color: #000;
    margin: 0;
    text-shadow: 0 0 10px rgba(255, 228, 122, 0.8);
    letter-spacing: -1px;
}

/* === PANEL DERECHO: TOP 3 === */
.ranking-panel {
    background: linear-gradient(135deg, rgba(11, 21, 48, 0.95) 0%, rgba(18, 55, 92, 0.95) 100%);
    border: 2px solid #19ff8c;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 0 30px #19ff8caa, inset 0 0 40px rgba(25, 255, 140, 0.1);
    display: flex;
    flex-direction: column;
    gap: 15px;
    height: 100%;
    overflow: hidden;
}

.ranking-header {
    text-align: center;
    border-bottom: 2px solid #19ff8c44;
    padding-bottom: 12px;
}

.ranking-title {
    font-size: 1rem;
    font-weight: 900;
    color: #19ff8c;
    text-shadow: 0 0 20px #19ff8c, 0 0 40px #19ff8c88;
    margin: 0;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.ranking-subtitle {
    font-size: 0.75rem;
    color: #36d1ff;
    margin: 5px 0 0 0;
    letter-spacing: 1px;
}

.podium {
    display: flex;
    flex-direction: column;
    gap: 10px;
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    min-height: 0;
    padding-right: 5px;
}

.podium::-webkit-scrollbar {
    width: 6px;
}

.podium::-webkit-scrollbar-track {
    background: rgba(25, 255, 140, 0.1);
    border-radius: 3px;
}

.podium::-webkit-scrollbar-thumb {
    background: #19ff8c;
    border-radius: 3px;
}

.podium-item {
    display: grid;
    grid-template-columns: 40px 1fr auto;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: 10px;
    position: relative;
    overflow: hidden;
    animation: slideIn 0.6s ease-out backwards;
}

.podium-item:nth-child(1) {
    animation-delay: 0.2s;
}

.podium-item:nth-child(2) {
    animation-delay: 0.4s;
}

.podium-item:nth-child(3) {
    animation-delay: 0.6s;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.podium-item.first {
    background: linear-gradient(90deg, rgba(255, 228, 122, 0.25) 0%, rgba(230, 190, 47, 0.25) 100%);
    border: 2px solid #ffe47a;
    box-shadow: 0 0 20px #ffe47a88;
}

.podium-item.second {
    background: linear-gradient(90deg, rgba(54, 209, 255, 0.2) 0%, rgba(0, 240, 255, 0.2) 100%);
    border: 2px solid #36d1ff;
    box-shadow: 0 0 15px #36d1ff88;
}

.podium-item.third {
    background: linear-gradient(90deg, rgba(255, 99, 71, 0.2) 0%, rgba(255, 69, 58, 0.2) 100%);
    border: 2px solid #ff6347;
    box-shadow: 0 0 15px #ff634788;
}

.rank-badge {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    font-weight: 900;
    position: relative;
}

.podium-item.first .rank-badge {
    background: linear-gradient(135deg, #ffe47a 0%, #e6be2f 100%);
    color: #000;
    box-shadow: 0 0 15px #ffe47a, inset 0 0 15px rgba(230, 190, 47, 0.5);
}

.podium-item.second .rank-badge {
    background: linear-gradient(135deg, #36d1ff 0%, #00f0ff 100%);
    color: #000;
    box-shadow: 0 0 15px #36d1ff, inset 0 0 15px rgba(0, 240, 255, 0.5);
}

.podium-item.third .rank-badge {
    background: linear-gradient(135deg, #ff6347 0%, #ff4537 100%);
    color: #fff;
    box-shadow: 0 0 15px #ff6347, inset 0 0 15px rgba(255, 69, 58, 0.5);
}

.participant-info {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.participant-name {
    font-size: 1rem;
    font-weight: 900;
    color: #fff;
    margin: 0;
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
}

.participant-details {
    font-size: 0.7rem;
    color: #36d1ff;
    margin: 0;
}

.participant-score {
    font-size: 1.5rem;
    font-weight: 900;
    text-align: right;
}

.podium-item.first .participant-score {
    color: #ffe47a;
    text-shadow: 0 0 20px #ffe47a;
}

.podium-item.second .participant-score {
    color: #36d1ff;
    text-shadow: 0 0 20px #36d1ff;
}

.podium-item.third .participant-score {
    color: #ff6347;
    text-shadow: 0 0 20px #ff6347;
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
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 5px;
    min-height: 0;
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
    gap: 10px;
    padding: 8px 12px;
    border-radius: 8px;
    background: rgba(0, 240, 255, 0.05);
    border-left: 3px solid;
    font-size: 0.75rem;
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
    font-size: 0.7rem;
}

.q-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.q-text {
    color: #fff;
    font-size: 0.75rem;
    line-height: 1.3;
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

/* Responsive */
@media (max-width: 1200px) {
    .final-scores-container {
        grid-template-columns: 1fr;
        gap: 20px;
        grid-template-rows: auto auto;
    }

    .guest-panel, .ranking-panel {
        height: auto;
        max-height: 45vh;
    }
}
</style>
</head>
<body>
    <div class="final-scores-container">
        <!-- PANEL IZQUIERDO: INVITADO -->
        <div class="guest-panel">
            <div class="guest-header">
                <div class="guest-title">Invitado</div>
                <div class="guest-name">{{ $guestName }}</div>
            </div>

            <div class="guest-score-display">
                <div class="score-label">Puntuación</div>
                <div class="score-value">{{ $guestScore }}</div>
                <div class="score-label">pts</div>
            </div>

            <!-- MAPPING DE PREGUNTAS -->
            @if($guestAnswers->count() > 0)
            <div class="questions-list">
                @foreach($guestAnswers as $index => $answer)
                <div class="question-item {{ $answer['is_correct'] ? 'correct' : 'incorrect' }}">
                    <div class="q-number">Q{{ $index + 1 }}</div>
                    <div class="q-content">
                        <div class="q-text">{{ $answer['question_text'] }}</div>
                        <div class="q-answer">
                            @if($answer['is_correct'])
                                <span class="answer-correct">{{ $answer['selected_option'] }}</span>
                            @else
                                <span class="answer-wrong">{{ $answer['selected_option'] }}</span>
                                <span class="arrow">→</span>
                                <span class="answer-correct">{{ $answer['correct_option'] }}</span>
                            @endif
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
                            <div class="rank-badge">{{ $rankIcon }}</div>
                            <div class="participant-info">
                                <div class="participant-name">{{ $participant->username }}</div>
                                <div class="participant-details">
                                    ✓ {{ $participant->correct_answers }} · ✗ {{ $participant->incorrect_answers }}
                                </div>
                            </div>
                            <div class="participant-score">{{ $participant->puntaje }}</div>
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
