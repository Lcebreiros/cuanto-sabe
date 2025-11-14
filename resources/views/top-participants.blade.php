<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Top Participants - Cuanto Sabe</title>
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

.podium-container {
    width: 100%;
    max-width: 900px;
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

.ranking-panel {
    background: linear-gradient(135deg, rgba(11, 21, 48, 0.95) 0%, rgba(18, 55, 92, 0.95) 100%);
    border: 2px solid #19ff8c;
    border-radius: 16px;
    padding: 20px 30px;
    box-shadow: 0 0 30px #19ff8caa, inset 0 0 40px rgba(25, 255, 140, 0.1);
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.ranking-header {
    text-align: center;
    border-bottom: 2px solid #19ff8c44;
    padding-bottom: 12px;
}

.ranking-title {
    font-size: 1.2rem;
    font-weight: 900;
    color: #19ff8c;
    text-shadow: 0 0 20px #19ff8c, 0 0 40px #19ff8c88;
    margin: 0;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.ranking-subtitle {
    font-size: 0.8rem;
    color: #36d1ff;
    margin: 5px 0 0 0;
    letter-spacing: 1px;
}

.podium {
    display: flex;
    flex-direction: row;
    align-items: flex-end;
    justify-content: center;
    gap: 25px;
    padding: 20px 30px;
    min-height: 350px;
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
    width: 180px;
}

.podium-item:nth-child(1) {
    animation-delay: 0.2s; /* Top 1 - Oro - Centro */
    order: 2;
}

.podium-item:nth-child(2) {
    animation-delay: 0.4s; /* Top 2 - Plata - Izquierda */
    order: 1;
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
    border-radius: 10px 10px 0 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 18px 12px 12px 12px;
    position: relative;
}

.podium-item.first .podium-pedestal {
    background: linear-gradient(180deg, rgba(255, 228, 122, 0.35) 0%, rgba(230, 190, 47, 0.3) 100%);
    border: 3px solid #ffe47a;
    border-bottom: 5px solid #ffe47a;
    box-shadow: 0 0 30px #ffe47a99, inset 0 -4px 20px rgba(255, 228, 122, 0.25);
    height: 200px;
}

.podium-item.second .podium-pedestal {
    background: linear-gradient(180deg, rgba(192, 192, 192, 0.35) 0%, rgba(169, 169, 169, 0.3) 100%);
    border: 3px solid #c0c0c0;
    border-bottom: 5px solid #c0c0c0;
    box-shadow: 0 0 25px #c0c0c099, inset 0 -4px 20px rgba(192, 192, 192, 0.25);
    height: 160px;
}

.podium-item.third .podium-pedestal {
    background: linear-gradient(180deg, rgba(205, 127, 50, 0.35) 0%, rgba(184, 115, 51, 0.3) 100%);
    border: 3px solid #cd7f32;
    border-bottom: 5px solid #cd7f32;
    box-shadow: 0 0 25px #cd7f3299, inset 0 -4px 20px rgba(205, 127, 50, 0.25);
    height: 120px;
}

.rank-badge {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 900;
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
}

.podium-item.first .rank-badge {
    background: linear-gradient(135deg, #ffe47a 0%, #e6be2f 100%);
    box-shadow: 0 0 25px #ffe47a, inset 0 0 25px rgba(230, 190, 47, 0.5);
    border: 4px solid #ffd700;
}

.podium-item.second .rank-badge {
    background: linear-gradient(135deg, #e8e8e8 0%, #c0c0c0 100%);
    box-shadow: 0 0 25px #c0c0c0, inset 0 0 25px rgba(192, 192, 192, 0.5);
    border: 4px solid #c0c0c0;
}

.podium-item.third .rank-badge {
    background: linear-gradient(135deg, #cd7f32 0%, #b87333 100%);
    box-shadow: 0 0 25px #cd7f32, inset 0 0 25px rgba(205, 127, 50, 0.5);
    border: 4px solid #cd7f32;
}

.participant-info {
    display: flex;
    flex-direction: column;
    gap: 6px;
    align-items: center;
    text-align: center;
    width: 100%;
    margin-top: 40px;
}

.participant-name {
    font-size: 1rem;
    font-weight: 900;
    color: #fff;
    margin: 0;
    text-shadow: 0 0 15px rgba(255, 255, 255, 0.6);
    word-break: break-word;
    line-height: 1.2;
}

.participant-details {
    font-size: 0.75rem;
    color: #36d1ff;
    margin: 0;
}

.participant-score {
    font-size: 1.6rem;
    font-weight: 900;
    text-align: center;
    margin-top: auto;
    padding-top: 10px;
}

.podium-item.first .participant-score {
    color: #ffe47a;
    text-shadow: 0 0 25px #ffe47a;
}

.podium-item.second .participant-score {
    color: #c0c0c0;
    text-shadow: 0 0 25px #c0c0c0;
}

.podium-item.third .participant-score {
    color: #cd7f32;
    text-shadow: 0 0 25px #cd7f32;
}

.no-data {
    text-align: center;
    color: #36d1ff;
    font-size: 1.3rem;
    padding: 60px 20px;
    opacity: 0.7;
}

/* Responsive */
@media (max-width: 900px) {
    .podium {
        gap: 15px;
        padding: 15px 20px;
    }

    .podium-item {
        width: 140px;
    }

    .podium-item.first .podium-pedestal {
        height: 170px;
    }

    .podium-item.second .podium-pedestal {
        height: 140px;
    }

    .podium-item.third .podium-pedestal {
        height: 100px;
    }

    .rank-badge {
        width: 50px;
        height: 50px;
        font-size: 2rem;
        top: -25px;
    }

    .participant-name {
        font-size: 0.9rem;
    }

    .participant-score {
        font-size: 1.4rem;
    }
}
</style>
</head>
<body>
    <div class="podium-container">
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
