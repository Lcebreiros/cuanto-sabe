@props(['puntaje'])

<div style="
    background: linear-gradient(90deg, #001a35ee 0%, #072954ea 100%);
    border: 4px solid #01e3fd66;
    border-radius: 1.5rem;
    box-shadow: 0 4px 32px #020d2455;
    padding: 0.8rem 1.3rem 0.8rem 1.1rem;
    min-width: 120px;
    max-width: 340px;
    width: fit-content;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 1.1rem;
    text-align: left;
">
    <span style="
        color: #00f0ff;
        font-size: 1.28rem;
        font-weight: bold;
        text-shadow: 0 0 10px #00e8fc, 0 0 4px #fff2;
        letter-spacing: 0.02em;
        white-space: nowrap;
        flex-shrink: 0;
    ">
        Tus puntos:
    </span>
    <span id="puntaje-num" style="
        color: #19ff8c;
        font-size: 2.25rem;
        font-weight: 900;
        line-height: 1;
        text-shadow: 0 0 12px #19ff8caa, 0 0 3px #fff3;
        margin-left: 0.2em;
        letter-spacing: 0.01em;
        white-space: nowrap;
    ">
        {{ $puntaje['total'] }}
    </span>
</div>

@if(session('participant_session_id'))
<script>
    window.PARTICIPANT_SESSION_ID = '{{ session('participant_session_id') }}';
    console.log("Participant session ID:", window.PARTICIPANT_SESSION_ID);

    if (window.Echo && window.PARTICIPANT_SESSION_ID) {
        let canal = 'puntaje.' + window.PARTICIPANT_SESSION_ID;
        console.log("Intentando suscribir a canal:", canal);

window.Echo.channel(canal)
    .listen('.PuntajeActualizado', function(e) {
        console.log("EVENTO PuntajeActualizado RECIBIDO!", e);
        const nuevoPuntaje = (typeof e.puntaje === 'object') ? e.puntaje.total : e.puntaje;
        let el = document.getElementById('puntaje-num');
        let container = document.getElementById('puntaje-container');
        if (el) {
            let puntajePrevio = parseInt(el.textContent) || 0;
            el.textContent = nuevoPuntaje;
            if (container) {
                container.classList.remove('puntaje-anim-bounce', 'puntaje-anim-shake');
                setTimeout(function() {
                    if (nuevoPuntaje > puntajePrevio) {
                        container.classList.add('puntaje-anim-bounce');
                    } else if (nuevoPuntaje < puntajePrevio) {
                        container.classList.add('puntaje-anim-shake');
                    }
                }, 15);
                container.addEventListener('animationend', function limpiarAnim(e) {
                    container.classList.remove('puntaje-anim-bounce', 'puntaje-anim-shake');
                    container.removeEventListener('animationend', limpiarAnim);
                });
            }
            console.log("Puntaje DOM actualizado:", nuevoPuntaje);
        }
    });

    } else {
        console.error("Echo o PARTICIPANT_SESSION_ID no disponible.");
    }
</script>
@endif
