<div id="preguntaOverlayPanel" style="background:#101c2e;border-radius:17px;box-shadow:0 0 15px #0ff6;padding:24px 26px 17px 26px;margin-bottom:22px;max-width:1100px;">
    <div id="textoPreguntaPanel" style="font-size:1.14rem;color:#18fff9;font-weight:bold;min-height:28px;text-shadow:0 0 7px #19faffd4;margin-bottom:14px;">
        {{ $pregunta }}
    </div>

    <div id="botonesOpcionesPanel" style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;margin-bottom:17px;">
        @foreach(['A','B','C','D'] as $label)
            @php
                $op = collect($opciones)->first(fn($o) => ($o['label'] ?? '') === $label);
            @endphp
            <button type="button"
                    wire:click="selectOption('{{ $label }}')"
                    class="opcion-btn-panel {{ $seleccionada === $label ? 'seleccionado-panel' : '' }}"
                    style="{{ $op ? '' : 'display:none;' }}">
                {{ $label }}@if($op): {{ $op['texto'] }} @endif
            </button>
        @endforeach
    </div>

    <div style="text-align:center;">
        <button wire:click="reveal" type="button" class="neon-btn-min" style="background:#12ffcb;color:#001;font-weight:bold;">Revelar respuesta</button>
        <button wire:click="resetOverlay" type="button" class="neon-btn-min" style="background:#111b2b;color:#19faff;font-weight:bold;">Reiniciar overlay</button>
    </div>
</div>

@push('scripts')
<script>
    // Escuchamos eventos de broadcasting con Echo y se los pasamos al componente Livewire
    if (window.Echo) {
        window.Echo.channel('cuanto-sabe-overlay')
            .listen('.nueva-pregunta', (e) => {
                // Normalizamos la data y la enviamos a Livewire
                let data = e.data || e || {};
                let pregunta = data.pregunta || data.data?.pregunta || data.texto || '';
                let opciones = data.opciones || data.data?.opciones || [];
                Livewire.emit('overlayQuestionReceived', { pregunta: pregunta, opciones: opciones });
            })
            .listen('.overlay-reset', (e) => {
                Livewire.emit('overlayQuestionReceived', { pregunta: 'Pregunta aún no enviada', opciones: [] });
            });
    }
</script>
@endpush
