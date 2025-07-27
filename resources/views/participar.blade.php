@extends('layouts.app')
@section('content')
<div class="flex flex-col items-center justify-center min-h-[80vh] px-3 py-6">
  <div id="main-question-box" class="w-full max-w-2xl">
    <!-- CONTENEDOR DE LA PREGUNTA -->
    @if(isset($pregunta['pregunta']) && $pregunta['pregunta'])
  <div class="question-box bg-gradient-to-r from-[#001a35ee] to-[#072954ea] border-4 border-[#01e3fd66] rounded-2xl shadow-[0_4px_32px_#020d2455] mb-7 px-7 py-6 flex items-center justify-center">
    <h2 class="question-title text-2xl md:text-3xl font-extrabold text-[#00f0ff] text-center tracking-wide neon-glow m-0 p-0 leading-tight">
      {{ $pregunta['pregunta'] ?? '' }}
    </h2>
  </div>
@endif

    <!-- MENSAJE DE "NO HAY PREGUNTA ACTIVA" -->
    @if(isset($sinPregunta) && $sinPregunta)
      <div id="msg-no-question" class="bg-[#171c2e] rounded-[2.5rem] px-12 py-12 shadow-2xl border-4 border-[#01e3fd4d] text-2xl text-white font-semibold text-center max-w-2xl mx-auto tracking-wide neon-glow">
        No hay pregunta activa.<br><br>
        Esperá que el host envíe una nueva pregunta...
      </div>
    @endif

    <!-- OPCIONES EN CUADRÍCULA (oculto si no hay pregunta) -->
    <form id="participar-form" method="POST" class="space-y-0" autocomplete="off" style="{{ isset($sinPregunta) && $sinPregunta ? 'display:none;' : '' }}">
      @csrf
      <input type="hidden" name="question_id" value="{{ $pregunta['pregunta_id'] ?? '' }}">
      <div class="options-grid grid grid-cols-1 md:grid-cols-2 gap-8">
        @if(isset($pregunta['opciones']))
          @foreach($pregunta['opciones'] as $op)
            <button
              type="button"
              data-label="{{ $op['label'] }}"
              class="option-card group relative flex flex-col items-center justify-center min-h-[108px] md:min-h-[138px] h-full bg-[#051e38fa] border-[3px] border-[#00f0ff44] text-[#d7f6ff] font-bold text-xl md:text-2xl rounded-2xl transition-all duration-200 ease-out shadow-lg hover:bg-[#00f0ff] hover:text-[#002640] hover:scale-105 focus:ring-4 focus:ring-[#00f0ff77] select-none outline-none tracking-wide neon-glow-btn"
            >
              <span class="block text-3xl md:text-4xl font-black mb-2 group-hover:text-[#ff1fff] transition">{{ $op['label'] }}</span>
              <span class="block text-center w-full font-bold text-lg md:text-2xl">{{ $op['texto'] }}</span>
              <span class="selected-animation absolute inset-0 opacity-0 pointer-events-none"></span>
            </button>
          @endforeach
        @endif
      </div>
    </form>
    <div id="respuesta-msg" class="text-center mt-8 text-[#19ff8c] font-extrabold text-lg drop-shadow-neon" style="display:none;">
      <!-- Se muestra solo desde JS -->
    </div>
  </div>
</div>

  <style>
body {
  background:
    radial-gradient(circle at 52% 44%, #1b0362 0%, #030015 95%) !important,
    url('/images/CS.png') center center no-repeat;
  background-size: auto 80vh, cover;
  color: #00f0ff;
}

    .neon-glow { text-shadow: 0 0 16px #00e8fc, 0 0 6px #fff3; }
    .drop-shadow-neon { text-shadow: 0 0 12px #19ff8cbb, 0 0 3px #fff3; }
    .neon-glow-btn { text-shadow: 0 0 6px #00e8fc99; letter-spacing: .03em; }
.question-box {
  border-radius: 1.7rem;
  margin-bottom: 2.5rem;
  box-shadow: 0 2px 22px #012b4955, 0 0 2px #00f0ff22;
  background: linear-gradient(120deg, rgba(4,38,78,0.81) 77%, rgba(0,52,94,0.81) 100%);
  border-width: 4px;
  border-color: #00f0ff66;
  /* (Opcional) */
  backdrop-filter: blur(2px);
}

    .options-grid {
      width: 100%;
      display: grid;
      grid-template-columns: 1fr;
      gap: 2rem;
    }
    @media (min-width: 768px) {
      .options-grid {
        grid-template-columns: 1fr 1fr;
        gap: 2.2rem;
      }
    }
.option-card {
  background-color: rgba(5, 30, 56, 0.64) !important; /* 0.64 es bastante translúcido, podés bajarlo aún más */
  border: 3px solid #00f0ff44;
  box-shadow: 0 3px 15px #012b497a, 0 0 2px #00f0ff22;
  color: #d7f6ff;
  min-height: 108px;
  height: 100%;
  font-family: 'Orbitron', Arial, sans-serif;
  transition: all .19s cubic-bezier(.44,0,.61,1.15);
  justify-content: center;
  align-items: center;
  text-align: center;
  opacity: 1;
  backdrop-filter: blur(2px);
}

    .option-card:hover, .option-card:focus,
    .option-card.selected {
      background: #00f0ff !important;
      color: #002640 !important;
      border-color: #00f0ff !important;
      box-shadow: 0 0 30px #00e8fcaa, 0 0 16px #00f0ff;
      z-index: 2;
      opacity: 1 !important;
    }
    .option-card.selected .selected-animation {
      opacity: 1;
    }
    .option-card .selected-animation {
      background: radial-gradient(circle, #00e8fc60 40%, transparent 80%);
      transition: opacity 0.23s;
      z-index: 1;
      border-radius: 1.2em;
    }
    .option-card.disabled {
      pointer-events: none;
      opacity: 0.60;
      filter: grayscale(0.85);
      transition: opacity 0.2s ease;
    }
    .option-card.correct {
      background: #19ff8c !important;
      color: #000 !important;
    }
    .option-card.incorrect {
      background: #ff4444 !important;
      color: #fff !important;
      animation: blink 0.8s steps(2, start) 2;
    }
    @keyframes blink {
      to { opacity: 0.5; }
    }
    @media (max-width: 640px) {
  /* General layout */
  .flex.flex-col.items-center.justify-center.min-h-[80vh].px-3.py-6,
  .flex.flex-col.items-center.justify-center.min-h-[60vh] {
    min-height: 97vh !important;
    padding: 0.5rem !important;
  }
  #main-question-box {
    max-width: 99vw !important;
    width: 100vw !important;
    padding: 0 !important;
  }
  .question-box {
    padding: 1rem 0.4rem !important;
    margin-bottom: 1.5rem !important;
    border-radius: 1rem !important;
  }
  .question-title {
    font-size: 1.18rem !important;
    padding: 0 0.25rem !important;
    word-break: break-word;
  }
  .options-grid {
    grid-template-columns: 1fr !important;
    gap: 1.1rem !important;
    padding: 0 0.1rem !important;
  }
  .option-card {
    min-height: 64px !important;
    padding: 0.4rem 0.1rem !important;
    font-size: 1.02rem !important;
    border-radius: 0.85rem !important;
  }
  .option-card span.block.text-3xl,
  .option-card span.block.text-4xl,
  .option-card span.block.text-center {
    font-size: 1.15rem !important;
  }
  .option-card span.block.text-lg,
  .option-card span.block.text-2xl {
    font-size: 1rem !important;
  }
  #respuesta-msg {
    margin-top: 1.2rem !important;
    font-size: 1.05rem !important;
    padding: 0.15rem;
  }
  .bg-[#171c2e] {
    font-size: 1.08rem !important;
    padding: 2.5rem 0.6rem !important;
    border-radius: 1.1rem !important;
    max-width: 96vw !important;
  }
}
@media (max-width: 420px) {
  .question-title {
    font-size: 0.98rem !important;
    line-height: 1.3 !important;
  }
  .option-card {
    min-height: 50px !important;
    font-size: 0.98rem !important;
  }
  .option-card span.block.text-3xl,
  .option-card span.block.text-4xl,
  .option-card span.block.text-center {
    font-size: 0.96rem !important;
  }
  .option-card span.block.text-lg,
  .option-card span.block.text-2xl {
    font-size: 0.86rem !important;
  }
  #respuesta-msg {
    font-size: 0.98rem !important;
  }
}
body {
  position: relative;
  background: radial-gradient(circle at 52% 44%, #1b0362 0%, #030015 95%) !important;
  overflow-x: hidden;
}
body::before {
  content: "";
  position: fixed;
  inset: 0;
  z-index: -1;
  background: url('/images/CS.png') center center no-repeat;
  background-size: auto 80vh; /* o el valor que prefieras */
  opacity: 0.18; /* Ajustá la opacidad a gusto */
  pointer-events: none;
  filter: blur(0px);
  /* No uses background-blend-mode acá, ya que el gradiente está en el body */
}
html, body {
  min-height: 100vh !important;
  height: 100% !important;
  width: 100vw !important;
  /* Fondo gradiente + imagen, el gradiente arriba de la imagen */
  background: 
    radial-gradient(circle at 52% 44%, #1b0362 0%, #030015 95%) 0 0/100vw 100vh no-repeat,
    url('/images/CS.png') center center/auto 80vh no-repeat;
  background-color: #1b0362; /* fallback sólido si nada carga */
  color: #00f0ff;
  overflow-x: hidden;
}

/* Si usás body::before, elimínalo, NO hace falta en móviles, y rompe en iOS */
body::before {
  display: none !important;
}

  </style>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>
<script>
window.Pusher = Pusher;
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: '{{ env('PUSHER_APP_KEY') }}',
  cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
  forceTLS: true
});

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('participar-form');
  const msg = document.getElementById('respuesta-msg');
  const main = document.getElementById('main-question-box');
  let enviado = false;
  let yaRespondio = null;
  let lastQuestionId = form ? form.querySelector('input[name="question_id"]').value : null;

  function limpiarSeleccionUI() {
    document.querySelectorAll('.option-card').forEach(btn => {
      btn.classList.remove('selected', 'disabled', 'correct', 'incorrect', 'blinking');
      btn.style.opacity = '1';
      btn.style.filter = 'none';
    });
    if (msg) { msg.style.display = 'none'; }
  }

  function marcarSeleccionUI(label) {
    document.querySelectorAll('.option-card').forEach(btn => {
      if (btn.getAttribute('data-label') === label) {
        btn.classList.add('selected');
        btn.classList.remove('disabled');
        btn.style.opacity = '1';
        btn.style.filter = 'none';
      } else {
        btn.classList.remove('selected');
        btn.classList.add('disabled');
        btn.style.opacity = '0.6';
        btn.style.filter = 'grayscale(0.85)';
      }
    });
    if (msg) {
      msg.style.display = 'block';
      msg.innerHTML = '¡Respuesta enviada! Esperá la siguiente pregunta...';
      msg.style.color = '#19ff8c';
    }
  }

  function handleOptionClick() {
    if (enviado) return;
    const selectedLabel = this.getAttribute('data-label');
    const questionId = form.querySelector('input[name="question_id"]').value;
    limpiarSeleccionUI();
    marcarSeleccionUI(selectedLabel);
    fetch("{{ route('participar.enviar') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
        },
        body: new URLSearchParams({
            option_label: selectedLabel,
            question_id: questionId,
            _token: form.querySelector('input[name="_token"]').value
        })
    })
    .then(response => response.ok ? response.text() : Promise.reject(response))
    .then(data => {
        enviado = true;
        yaRespondio = selectedLabel;
        if (msg) {
            msg.style.display = 'block';
            msg.innerHTML = '¡Respuesta enviada! Esperá la siguiente pregunta...';
            msg.style.color = '#19ff8c';
        }
    })
    .catch(err => {
        enviado = false;
        if (msg) {
            msg.style.display = 'block';
            msg.style.color = '#ff4444';
            msg.innerHTML = 'Hubo un error al enviar la respuesta.';
        }
        limpiarSeleccionUI();
    });
  }

  // Estado inicial: limpiar todo
  limpiarSeleccionUI();

  if (form) {
    form.querySelectorAll('.option-card').forEach(btn => {
      btn.addEventListener('click', handleOptionClick);
    });
    form.addEventListener('submit', function(e) { e.preventDefault(); });
  }

  function renderPregunta(data) {
    if (form) {
      form.querySelector('input[name="question_id"]').value = data.pregunta_id || '';
    }
    const optionsGrid = form ? form.querySelector('.options-grid') : null;
    if (optionsGrid) {
      optionsGrid.innerHTML = '';
      (data.opciones || []).forEach(op => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.setAttribute('data-label', op.label);
        btn.className = "option-card group relative flex flex-col items-center justify-center min-h-[108px] md:min-h-[138px] h-full bg-[#051e38fa] border-[3px] border-[#00f0ff44] text-[#d7f6ff] font-bold text-xl md:text-2xl rounded-2xl transition-all duration-200 ease-out shadow-lg hover:bg-[#00f0ff] hover:text-[#002640] hover:scale-105 focus:ring-4 focus:ring-[#00f0ff77] select-none outline-none tracking-wide neon-glow-btn";
        btn.innerHTML = `
            <span class="block text-3xl md:text-4xl font-black mb-2 group-hover:text-[#ff1fff] transition">${op.label}</span>
            <span class="block text-center w-full font-bold text-lg md:text-2xl">${op.texto}</span>
            <span class="selected-animation absolute inset-0 opacity-0 pointer-events-none"></span>
        `;
        btn.addEventListener('click', handleOptionClick);
        optionsGrid.appendChild(btn);
      });
    }
    const questionTitle = document.querySelector('.question-title');
    if (questionTitle) questionTitle.textContent = data.pregunta || '';

    enviado = false;
    yaRespondio = null;
    lastQuestionId = data.pregunta_id;
    limpiarSeleccionUI();
    if (msg) msg.style.display = 'none';
    if (form) form.style.display = 'block';
    const msgNoQuestion = document.getElementById('msg-no-question');
    if (msgNoQuestion) msgNoQuestion.style.display = 'none';
  }

  // EVENTOS PUSHER/ECHO
  window.Echo.channel('overlay-channel')
    .listen('.nueva-pregunta', function(e) {
      const data = e.data || e;
      if (data.opciones && data.opciones.length > 0) {
        renderPregunta(data);
      }
    })
    .listen('.revelar-respuesta', function(e) {
      let data = e.data || e;
      let qid = form ? form.querySelector('input[name="question_id"]').value : null;
      if (String(data.pregunta_id) !== String(qid)) return;
      let respondida = yaRespondio;
      if (!respondida) {
        marcarCorrecta(data.label_correcto);
        return;
      }
      if (respondida === data.label_correcto) {
        marcarCorrecta(data.label_correcto);
        return;
      }
      marcarIncorrecta(respondida);
      if (msg) {
        msg.innerHTML = 'Respuesta incorrecta 😢';
        msg.style.color = '#ff4444';
        msg.style.display = 'block';
      }
      setTimeout(() => {
        marcarCorrecta(data.label_correcto);
        if (msg) {
          msg.innerHTML = 'La respuesta correcta era la opción ' + data.label_correcto + '!';
          msg.style.color = '#19ff8c';
        }
      }, 5000);
    })
    .listen('.overlay-reset', function() {
      // 🔥 Limpiar completamente pregunta y opciones!
      if (form) {
        // Oculta el form y limpia inputs
        form.style.display = 'none';
        form.querySelector('input[name="question_id"]').value = '';
        const optionsGrid = form.querySelector('.options-grid');
        if (optionsGrid) optionsGrid.innerHTML = '';
      }
      // Limpia el texto de la pregunta arriba
      const questionTitle = document.querySelector('.question-title');
      if (questionTitle) questionTitle.textContent = '';
      if (msg) msg.style.display = 'none';

      // Mensaje de "No hay pregunta activa"
      let msgNoQuestion = document.getElementById('msg-no-question');
      if (!msgNoQuestion && main) {
        msgNoQuestion = document.createElement('div');
        msgNoQuestion.id = 'msg-no-question';
        msgNoQuestion.className = 'bg-[#171c2e] rounded-[2.5rem] px-12 py-12 shadow-2xl border-4 border-[#01e3fd4d] text-2xl text-white font-semibold text-center max-w-2xl mx-auto tracking-wide neon-glow';
        main.appendChild(msgNoQuestion);
      }
      if (msgNoQuestion) {
        msgNoQuestion.innerHTML = `
          No hay pregunta activa.<br><br>
          Esperá que el host envíe una nueva pregunta...
        `;
        msgNoQuestion.style.display = 'block';
      }
    });

  // --- Corrección: Cuando marco la correcta, quito opacidad/filtro a esa también
  function marcarCorrecta(label) {
    document.querySelectorAll('.option-card').forEach(btn => {
      if (btn.getAttribute('data-label') === label) {
        btn.classList.add('correct');
        btn.classList.remove('incorrect', 'disabled');
        btn.style.opacity = '1';
        btn.style.filter = 'none';
      } else {
        btn.classList.remove('correct');
      }
    });
  }

  function marcarIncorrecta(label) {
    document.querySelectorAll('.option-card').forEach(btn => {
      if (btn.getAttribute('data-label') === label) {
        btn.classList.add('incorrect', 'blinking');
        btn.classList.remove('correct');
        // Mantiene la opacidad/filtro para la incorrecta seleccionada
      }
    });
  }

});
</script>
@endsection
