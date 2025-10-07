<div class="game-control-root flex flex-col w-full">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:18px;width:100%;flex-wrap:wrap;">
        {{-- ON/OFF area --}}
        <div style="display:flex;align-items:center;gap:12px;">
            @if(!$activeSession)
                <button wire:click="$emit('toggleStartForm')" class="radio-light-btn off" type="button">
                    <span class="light"></span> OFF
                </button>
            @else
                <button wire:click="endSession" class="radio-light-btn on" type="button">
                    <span class="light"></span> ON
                </button>
            @endif
        </div>

        {{-- Overlay / copy / girar ruleta / girar --}}
        <div style="display:flex;align-items:center;gap:8px;">
            <a href="{{ url('/overlay') }}" target="_blank" class="neon-btn-min" style="min-width:110px;">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M2.5 13.5A1.5 1.5 0 0 1 1 12V4a1.5 1.5 0 0 1 1.5-1.5h11A1.5 1.5 0 0 1 15 4v8a1.5 1.5 0 0 1-1.5 1.5h-11Z"/></svg>
                Overlay
            </a>

            <button type="button" wire:click="copyOverlayUrl" title="Copiar URL del Overlay" class="neon-btn-min" style="width:46px;height:46px;padding:0;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8"/></svg>
            </button>

            <button type="button" wire:click="girarRuleta" class="neon-btn-min">Girar ruleta</button>
        </div>
    </div>

    {{-- Formulario para iniciar sesión (colapsable con Alpine-like minimal) --}}
    <div style="margin-top:12px;">
        <div wire:ignore.self x-data="{ open: false }" x-init="
            Livewire.on('toggleStartForm', ()=> open = !open);
            Livewire.on('sessionStarted', ()=> { open = false });
        ">
            <div x-show="open" style="transition:all .25s;">
                <div class="card shadow-lg border-0" style="margin-top:10px;">
                    <form wire:submit.prevent="startSession" class="card-body row g-3">
                        @csrf
                        <div class="col-12 d-flex justify-content-between align-items-center mb-0">
                            <div>
                                <h5 class="card-title mb-0">Iniciar nueva sesión</h5>
                                <small class="text-muted">Completa los datos para empezar</small>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <span :class="{}">Normal</span>
                                <label class="switch-pill m-0">
                                    <input type="checkbox" wire:model="isExpress" wire:change="toggleMode">
                                    <span class="switch-pill-track"></span>
                                    <span class="switch-pill-thumb"></span>
                                </label>
                                <span>Express</span>
                            </div>
                        </div>

                        <input type="hidden" name="modo_juego" :value="modo">

                        <div class="col-12 col-md-6">
                            <label for="guest_name" class="form-label">Nombre del invitado</label>
                            <input type="text" id="guest_name" wire:model.defer="guest_name" class="form-control" placeholder="Ej: Juan Pérez" required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="motivo_id" class="form-label">Motivo</label>
                            <select id="motivo_id" wire:model.defer="motivo_id" class="form-select" required>
                                <option value="">Elegí motivo</option>
                                @foreach($motivos as $motivo)
                                    <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" @click="open = false">Cancelar</button>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-play-fill me-1"></i> Iniciar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Form para enviar pregunta random --}}
        @if($activeSession)
        <form wire:submit.prevent="$emit('sendRandom', $event.target.categoriaRandom.value)" class="row g-2 align-items-center mb-3" style="margin-top:14px;">
            <div class="col-auto">
                <label for="categoriaRandom" class="form-label mb-0" style="color:#19faff;">Categoría:</label>
            </div>
            <div class="col-auto">
                <select id="categoriaRandom" class="form-select" required>
                    <option value="">Elegí una categoría</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="neon-btn-min" style="background:#23ffe5; color:#002; border-color:#0ff;">Enviar pregunta random</button>
            </div>
        </form>
        @endif
    </div>

    @push('scripts')
    <script>
        // clipboard and basic toast handlers triggered by Livewire browser events
        Livewire.on('copy-overlay-url', (payload) => {
            const overlayUrl = payload.url;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(overlayUrl).then(()=> {
                    Livewire.dispatch('toast', { message: 'URL copiada' });
                    showTemporaryNotification('¡URL copiada!');
                });
            } else {
                // fallback
                const el = document.createElement('textarea');
                el.value = overlayUrl;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                el.remove();
                showTemporaryNotification('¡URL copiada!');
            }
        });

        Livewire.on('toast', data => {
            showTemporaryNotification(data.message || 'OK');
        });

        function showTemporaryNotification(text) {
            const notification = document.createElement('div');
            notification.textContent = text;
            notification.style.cssText = 'position:fixed;top:18px;right:18px;background:#19faff;color:#061; padding:10px 16px;border-radius:6px;z-index:9999;font-weight:bold;';
            document.body.appendChild(notification);
            setTimeout(()=> notification.remove(), 1800);
        }

        // event bridge for sending random
        Livewire.on('sendRandom', (categoriaId) => {
            Livewire.emit('enviarPreguntaRandom', categoriaId);
        });

        // Expose helper on window if needed
        window.livewireGameControl = {
            refresh: () => Livewire.emit('refreshGameControl')
        };
    </script>
    @endpush
</div>
