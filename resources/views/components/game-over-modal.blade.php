{{--
    Componente: Game Over Modal
    Uso: <x-game-over-modal /> en game.blade.php
    Activación: showGameOverModal(payload) desde el listener .revelar-respuesta
    cuando payload.question_limit_reached === true
--}}

<style>
/* ── Game Over Modal ───────────────────────────────── */
#gameOverModal {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.82);
    backdrop-filter: blur(7px);
}
#gameOverModal.show { display: flex; }

.go-card {
    background: linear-gradient(145deg, #0a0e23 0%, #0d1235 100%);
    border: 1px solid rgba(0, 240, 255, 0.28);
    border-radius: 18px;
    padding: 2rem 2.5rem 1.6rem;
    max-width: 520px;
    width: 92%;
    box-shadow: 0 0 60px rgba(0, 240, 255, 0.12), 0 20px 60px rgba(0,0,0,0.6);
    animation: goSlideIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes goSlideIn {
    from { transform: translateY(-28px) scale(0.96); opacity: 0; }
    to   { transform: translateY(0)     scale(1);    opacity: 1; }
}

.go-title {
    text-align: center;
    font-size: 1.55rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 1.4rem;
    letter-spacing: 0.02em;
}

.go-section {
    border-radius: 10px;
    padding: 0.75rem 1rem;
    margin-bottom: 0.6rem;
    display: flex;
    align-items: center;
    gap: 0.65rem;
}
.go-icon { font-size: 1.25rem; flex-shrink: 0; line-height: 1; }
.go-name { flex: 1; font-weight: 600; color: #fff; font-size: 0.95rem; }
.go-score { font-size: 0.82rem; color: #b8c7ff; white-space: nowrap; }

.go-badge-win  { background: rgba(25,255,140,0.09); border: 1px solid rgba(25,255,140,0.28); }
.go-badge-loss { background: rgba(255,68,68,0.07);  border: 1px solid rgba(255,68,68,0.18);  }
.go-badge-ind  { background: rgba(255,204,0,0.08);  border: 1px solid rgba(255,204,0,0.22);  }

.go-result-label {
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 4px;
    letter-spacing: 0.05em;
    white-space: nowrap;
}
.go-result-label.won  { background: rgba(25,255,140,0.15); color: #19ff8c; }
.go-result-label.lost { background: rgba(255,68,68,0.15);  color: #ff6666; }
.go-result-label.top  { background: rgba(255,204,0,0.18);  color: #ffcc00; }

.go-summary {
    text-align: center;
    font-size: 0.98rem;
    color: #b8c7ff;
    margin: 1.1rem 0 1.3rem;
    line-height: 1.5;
}
.go-summary strong { color: #fff; }

.go-close-btn {
    display: block;
    margin: 0 auto;
    background: transparent;
    border: 1px solid rgba(0, 240, 255, 0.35);
    color: #00f0ff;
    border-radius: 8px;
    padding: 0.45rem 2.2rem;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background 0.2s, border-color 0.2s;
}
.go-close-btn:hover { background: rgba(0, 240, 255, 0.1); border-color: rgba(0,240,255,0.6); }
</style>

<div id="gameOverModal">
    <div class="go-card">
        <div class="go-title">🏁 El juego ha finalizado</div>
        <div id="goResults"></div>
        <div id="goSummary" class="go-summary"></div>
        <button class="go-close-btn" onclick="document.getElementById('gameOverModal').classList.remove('show')">
            Cerrar
        </button>
    </div>
</div>

<script>
function showGameOverModal(payload) {
    const victoria    = payload.victoria    || {};
    const publicoGano = !!payload.publico_gano;
    const topP        = payload.top_participant || null;

    const invGano  = victoria.gano === true;
    const invScore = payload.puntaje_invitado ?? 0;
    const invObj   = victoria.objetivo        ?? 25;
    const tend     = payload.tendencias_acertadas ?? 0;
    const tendObj  = payload.tendencias_objetivo  ?? 10;

    const winners = [];
    let html = '';

    // ── Invitado ──────────────────────────────────────
    if (invGano) winners.push('Invitado');
    html += `
        <div class="go-section ${invGano ? 'go-badge-win' : 'go-badge-loss'}">
            <span class="go-icon">${invGano ? '🏆' : '😔'}</span>
            <div class="go-name">Invitado</div>
            <div class="go-score">${invScore} / ${invObj} pts</div>
            <span class="go-result-label ${invGano ? 'won' : 'lost'}">${invGano ? 'GANÓ' : 'PERDIÓ'}</span>
        </div>`;

    // ── Público grupal ────────────────────────────────
    if (publicoGano) winners.push('Público grupal');
    html += `
        <div class="go-section ${publicoGano ? 'go-badge-win' : 'go-badge-loss'}">
            <span class="go-icon">${publicoGano ? '🎉' : '👥'}</span>
            <div class="go-name">Público grupal</div>
            <div class="go-score">${tend} / ${tendObj} tendencias</div>
            <span class="go-result-label ${publicoGano ? 'won' : 'lost'}">${publicoGano ? 'GANÓ' : 'PERDIÓ'}</span>
        </div>`;

    // ── Público individual ────────────────────────────
    if (topP) {
        winners.push(`${topP.username} (individual)`);
        html += `
        <div class="go-section go-badge-ind">
            <span class="go-icon">🥇</span>
            <div class="go-name">${topP.username}</div>
            <div class="go-score">${topP.puntaje} pts</div>
            <span class="go-result-label top">MEJOR JUGADOR</span>
        </div>`;
    }

    // ── Resumen ───────────────────────────────────────
    let summaryText;
    if (!invGano && !publicoGano && !topP) {
        summaryText = '😞 Nadie alcanzó su objetivo esta vez.';
    } else if (winners.length === 1) {
        summaryText = `🎊 <strong>${winners[0]}</strong> ganó el juego.`;
    } else {
        summaryText = `🎊 Ganaron: <strong>${winners.join(', ')}</strong>.`;
    }

    document.getElementById('goResults').innerHTML = html;
    document.getElementById('goSummary').innerHTML = summaryText;
    document.getElementById('gameOverModal').classList.add('show');
}
</script>
