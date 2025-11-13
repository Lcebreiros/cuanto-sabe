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
    padding: 40px;
    box-sizing: border-box;
}

.final-scores-container {
    width: 100%;
    max-width: 1400px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    animation: fadeIn 0.8s ease-out;
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
    border: 3px solid #00f0ff;
    border-radius: 24px;
    padding: 40px;
    box-shadow: 0 0 40px #00f0ffaa, inset 0 0 60px rgba(0, 240, 255, 0.1);
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.guest-header {
    text-align: center;
    border-bottom: 2px solid #00f0ff44;
    padding-bottom: 25px;
}

.guest-title {
    font-size: 1.8rem;
    font-weight: 900;
    color: #00f0ff;
    text-shadow: 0 0 20px #00f0ff, 0 0 40px #00f0ff88;
    margin: 0 0 15px 0;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.guest-name {
    font-size: 2.8rem;
    font-weight: 900;
    color: #fff;
    text-shadow: 0 0 25px #ffe47a, 0 0 50px #ffe47a88;
    margin: 0;
    letter-spacing: 1px;
}

.guest-score-display {
    background: linear-gradient(90deg, #ffe47a 0%, #e6be2f 100%);
    border-radius: 18px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 0 30px #ffe47a99, inset 0 0 30px rgba(255, 228, 122, 0.3);
}

.score-label {
    font-size: 1.3rem;
    font-weight: 700;
    color: #333;
    margin: 0 0 10px 0;
    letter-spacing: 1px;
}

.score-value {
    font-size: 4.5rem;
    font-weight: 900;
    color: #000;
    margin: 0;
    text-shadow: 0 0 15px rgba(255, 228, 122, 0.8);
    letter-spacing: -2px;
}

.guest-stats {
    display: flex;
    gap: 20px;
    justify-content: space-around;
}

.stat-box {
    flex: 1;
    border-radius: 16px;
    padding: 25px 20px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.stat-box.correct {
    background: linear-gradient(135deg, rgba(19, 255, 121, 0.2) 0%, rgba(7, 206, 94, 0.2) 100%);
    border: 2px solid #13ff79;
    box-shadow: 0 0 20px #13ff7988;
}

.stat-box.incorrect {
    background: linear-gradient(135deg, rgba(255, 63, 52, 0.2) 0%, rgba(208, 0, 21, 0.2) 100%);
    border: 2px solid #ff3f34;
    box-shadow: 0 0 20px #ff3f3488;
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.stat-box.correct .stat-icon {
    color: #13ff79;
    text-shadow: 0 0 15px #13ff79;
}

.stat-box.incorrect .stat-icon {
    color: #ff3f34;
    text-shadow: 0 0 15px #ff3f34;
}

.stat-number {
    font-size: 3.5rem;
    font-weight: 900;
    margin: 10px 0;
    line-height: 1;
}

.stat-box.correct .stat-number {
    color: #13ff79;
    text-shadow: 0 0 20px #13ff79;
}

.stat-box.incorrect .stat-number {
    color: #ff3f34;
    text-shadow: 0 0 20px #ff3f34;
}

.stat-label {
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* === PANEL DERECHO: TOP 3 === */
.ranking-panel {
    background: linear-gradient(135deg, rgba(11, 21, 48, 0.95) 0%, rgba(18, 55, 92, 0.95) 100%);
    border: 3px solid #19ff8c;
    border-radius: 24px;
    padding: 40px;
    box-shadow: 0 0 40px #19ff8caa, inset 0 0 60px rgba(25, 255, 140, 0.1);
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.ranking-header {
    text-align: center;
    border-bottom: 2px solid #19ff8c44;
    padding-bottom: 25px;
}

.ranking-title {
    font-size: 1.8rem;
    font-weight: 900;
    color: #19ff8c;
    text-shadow: 0 0 20px #19ff8c, 0 0 40px #19ff8c88;
    margin: 0;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.ranking-subtitle {
    font-size: 1rem;
    color: #36d1ff;
    margin: 10px 0 0 0;
    letter-spacing: 1px;
}

.podium {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.podium-item {
    display: grid;
    grid-template-columns: 60px 1fr auto;
    align-items: center;
    gap: 20px;
    padding: 20px;
    border-radius: 16px;
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
    border: 3px solid #ffe47a;
    box-shadow: 0 0 30px #ffe47a88;
}

.podium-item.second {
    background: linear-gradient(90deg, rgba(54, 209, 255, 0.2) 0%, rgba(0, 240, 255, 0.2) 100%);
    border: 3px solid #36d1ff;
    box-shadow: 0 0 25px #36d1ff88;
}

.podium-item.third {
    background: linear-gradient(90deg, rgba(255, 99, 71, 0.2) 0%, rgba(255, 69, 58, 0.2) 100%);
    border: 3px solid #ff6347;
    box-shadow: 0 0 20px #ff634788;
}

.rank-badge {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 900;
    position: relative;
}

.podium-item.first .rank-badge {
    background: linear-gradient(135deg, #ffe47a 0%, #e6be2f 100%);
    color: #000;
    box-shadow: 0 0 25px #ffe47a, inset 0 0 20px rgba(230, 190, 47, 0.5);
}

.podium-item.second .rank-badge {
    background: linear-gradient(135deg, #36d1ff 0%, #00f0ff 100%);
    color: #000;
    box-shadow: 0 0 25px #36d1ff, inset 0 0 20px rgba(0, 240, 255, 0.5);
}

.podium-item.third .rank-badge {
    background: linear-gradient(135deg, #ff6347 0%, #ff4537 100%);
    color: #fff;
    box-shadow: 0 0 25px #ff6347, inset 0 0 20px rgba(255, 69, 58, 0.5);
}

.participant-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.participant-name {
    font-size: 1.5rem;
    font-weight: 900;
    color: #fff;
    margin: 0;
    text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
}

.participant-details {
    font-size: 0.9rem;
    color: #36d1ff;
    margin: 0;
}

.participant-score {
    font-size: 2.5rem;
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

/* Responsive */
@media (max-width: 1200px) {
    .final-scores-container {
        grid-template-columns: 1fr;
        gap: 30px;
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
                <div class="score-label">Puntuación Final</div>
                <div class="score-value">{{ $guestScore }}</div>
            </div>

            <div class="guest-stats">
                <div class="stat-box correct">
                    <div class="stat-icon">✓</div>
                    <div class="stat-number">{{ $correctAnswers }}</div>
                    <div class="stat-label">Correctas</div>
                </div>

                <div class="stat-box incorrect">
                    <div class="stat-icon">✗</div>
                    <div class="stat-number">{{ $incorrectAnswers }}</div>
                    <div class="stat-label">Incorrectas</div>
                </div>
            </div>
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
