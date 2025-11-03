<div>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 px-4 py-8">
  <div class="max-w-7xl mx-auto">
    
    <!-- Header con gradiente y glassmorphism -->
    <div class="mb-10 relative">
      <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/10 to-blue-500/10 rounded-3xl blur-3xl"></div>
      <div class="relative bg-slate-800/40 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-8 shadow-2xl">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
          <div class="space-y-2">
            <div class="flex items-center gap-3">
              <div class="p-3 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl shadow-lg shadow-cyan-500/20">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400">
                Gestión de Reglas
              </h1>
            </div>
            <p class="text-slate-400 text-lg ml-1">Define las reglas que aparecerán en tu plataforma</p>
            @if(count($rules) > 0)
              <div class="flex items-center gap-2 ml-1 mt-3">
                <span class="px-3 py-1 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 rounded-full text-sm font-medium">
                  {{ count($rules) }} {{ count($rules) === 1 ? 'regla' : 'reglas' }}
                </span>
                <span class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-full text-sm font-medium">
                  {{ collect($rules)->where('active', true)->count() }} activas
                </span>
              </div>
            @endif
          </div>
          
          <div class="flex flex-wrap gap-3">
            <a href="{{ route('about-us') }}" 
               class="group inline-flex items-center gap-2 px-5 py-3 bg-slate-800/80 hover:bg-slate-700/80 border border-slate-600/50 hover:border-slate-500 text-slate-200 rounded-xl transition-all duration-300 font-medium shadow-lg hover:shadow-xl hover:shadow-slate-700/20 hover:-translate-y-0.5">
              <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
              Ver Landing
            </a>
            <button wire:click="create" 
                    class="group inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white rounded-xl transition-all duration-300 font-bold shadow-xl shadow-cyan-500/30 hover:shadow-2xl hover:shadow-cyan-500/40 hover:-translate-y-0.5">
              <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
              </svg>
              Nueva Regla
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Message con animación -->
    @if(session('success'))
      <div class="mb-8 animate-slideDown">
        <div class="relative bg-gradient-to-r from-emerald-500/10 to-green-500/10 border border-emerald-500/30 rounded-xl p-5 backdrop-blur-sm overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-r from-emerald-400/5 to-green-400/5 animate-pulse"></div>
          <div class="relative flex items-start gap-4">
            <div class="p-2 bg-emerald-500/20 rounded-lg">
              <svg class="w-6 h-6 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-emerald-300 font-semibold text-lg">¡Operación exitosa!</p>
              <p class="text-emerald-400/80 text-sm mt-1">{{ session('success') }}</p>
            </div>
          </div>
        </div>
      </div>
    @endif

    <!-- Rules Grid con animaciones staggered -->
    @if(count($rules) > 0)
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($rules as $index => $r)
          <div class="group relative animate-fadeInUp" style="animation-delay: {{ $index * 50 }}ms;">
            <!-- Glow effect -->
            <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-2xl opacity-0 group-hover:opacity-20 blur transition-opacity duration-500"></div>
            
            <!-- Card -->
            <div class="relative bg-slate-800/60 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6 hover:border-cyan-500/40 transition-all duration-500 hover:shadow-2xl hover:shadow-cyan-500/10 hover:-translate-y-1">
              
              <!-- Header -->
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1 min-w-0 space-y-2">
                  <h3 class="text-xl font-bold text-white truncate group-hover:text-cyan-400 transition-colors">
                    {{ $r['title'] }}
                  </h3>
                  <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-700/50 border border-slate-600/50 rounded-lg text-xs font-medium text-slate-300">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                      </svg>
                      Orden: {{ $r['sort_order'] }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $r['active'] ? 'bg-emerald-500/20 border border-emerald-500/40 text-emerald-300' : 'bg-slate-700/50 border border-slate-600/50 text-slate-400' }}">
                      <span class="w-1.5 h-1.5 rounded-full {{ $r['active'] ? 'bg-emerald-400' : 'bg-slate-500' }}"></span>
                      {{ $r['active'] ? 'Activo' : 'Inactivo' }}
                    </span>
                  </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-1.5 ml-3">
                  <button wire:click="edit({{ $r['id'] }})" 
                          class="group/btn p-2.5 text-cyan-400 hover:bg-cyan-500/10 rounded-xl transition-all duration-300 hover:scale-110 hover:shadow-lg hover:shadow-cyan-500/20"
                          title="Editar">
                    <svg class="w-5 h-5 transition-transform group-hover/btn:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <button wire:click="delete({{ $r['id'] }})" 
                          onclick="return confirm('¿Estás seguro de eliminar esta regla?')"
                          class="group/btn p-2.5 text-red-400 hover:bg-red-500/10 rounded-xl transition-all duration-300 hover:scale-110 hover:shadow-lg hover:shadow-red-500/20"
                          title="Eliminar">
                    <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Divider -->
              <div class="h-px bg-gradient-to-r from-transparent via-slate-600/50 to-transparent mb-4"></div>

              <!-- Content Preview -->
              <div class="prose prose-invert prose-sm max-w-none">
                <div class="text-slate-300 text-sm leading-relaxed line-clamp-3">
                  {!! \Illuminate\Support\Str::markdown($r['content'] ?? '') !!}
                </div>
              </div>
              
              <!-- Hover indicator -->
              <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <!-- Empty State mejorado -->
      <div class="text-center py-20 animate-fadeIn">
        <div class="relative inline-block mb-6">
          <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/20 to-blue-500/20 rounded-full blur-2xl"></div>
          <div class="relative p-6 bg-slate-800/50 backdrop-blur-sm rounded-full">
            <svg class="w-20 h-20 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
        </div>
        <h3 class="text-3xl font-bold text-white mb-3">No hay reglas creadas</h3>
        <p class="text-slate-400 text-lg mb-8 max-w-md mx-auto">
          Comienza definiendo las reglas que guiarán tu plataforma
        </p>
        <button wire:click="create" 
                class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white rounded-xl transition-all duration-300 font-bold shadow-2xl shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-1 text-lg">
          <svg class="w-6 h-6 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
          </svg>
          Crear Primera Regla
        </button>
      </div>
    @endif

  </div>

  {{-- Modal mejorado con backdrop blur --}}
  @if($showModal)
    <div class="fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center z-50 p-4 animate-fadeIn">
      <div class="bg-slate-900/95 backdrop-blur-xl rounded-3xl w-full max-w-4xl shadow-2xl border border-slate-700/50 animate-scaleIn overflow-hidden">
        
        <!-- Modal Header con gradiente -->
        <div class="relative bg-gradient-to-r from-slate-800 to-slate-900 border-b border-slate-700/50 p-8">
          <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/5 to-blue-500/5"></div>
          <div class="relative flex items-center justify-between">
            <div class="space-y-1">
              <h3 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400">
                {{ $editingId ? 'Editar Regla' : 'Nueva Regla' }}
              </h3>
              <p class="text-slate-400">{{ $editingId ? 'Modifica los datos de la regla existente' : 'Completa la información de la nueva regla' }}</p>
            </div>
            <button wire:click="closeModal" 
                    class="group p-3 text-slate-400 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all duration-300 hover:rotate-90">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Modal Body con scroll suave -->
        <form wire:submit.prevent="save" class="p-8 space-y-6 max-h-[calc(90vh-200px)] overflow-y-auto custom-scrollbar">
          
          <!-- Title -->
          <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-bold text-slate-200 mb-3">
              <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
              </svg>
              Título
              <span class="text-red-400">*</span>
            </label>
            <input type="text" 
                   wire:model.defer="title" 
                   class="w-full px-5 py-4 bg-slate-800/50 border border-slate-600/50 focus:border-cyan-500/50 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 transition-all text-lg"
                   placeholder="Ej: Respuesta correcta"
                   required>
            @error('title') 
              <p class="mt-2 text-sm text-red-400 flex items-center gap-2 bg-red-500/10 border border-red-500/20 rounded-lg px-3 py-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
              </p>
            @enderror
          </div>

          <!-- Content -->
          <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-bold text-slate-200 mb-3">
              <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
              Contenido
              <span class="text-slate-500 font-normal text-xs px-2 py-1 bg-slate-700/50 rounded">(Soporta Markdown)</span>
            </label>
            <textarea wire:model.defer="content" 
                      rows="10" 
                      class="w-full px-5 py-4 bg-slate-800/50 border border-slate-600/50 focus:border-cyan-500/50 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 transition-all font-mono text-sm"
                      placeholder="Escribe el contenido de la regla aquí...&#10;&#10;Puedes usar **negrita**, *cursiva*, listas, etc."></textarea>
            @error('content') 
              <p class="mt-2 text-sm text-red-400 flex items-center gap-2 bg-red-500/10 border border-red-500/20 rounded-lg px-3 py-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
              </p>
            @enderror
            <p class="text-xs text-slate-500 flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Usa sintaxis Markdown para dar formato al texto
            </p>
          </div>

          <!-- Active & Sort Order -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="group relative">
              <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-green-500/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
              <div class="relative flex items-center gap-4 p-5 bg-slate-800/30 hover:bg-slate-800/50 rounded-xl border border-slate-700/50 transition-all cursor-pointer">
                <input type="checkbox" 
                       wire:model.defer="active" 
                       id="active-checkbox"
                       class="w-6 h-6 rounded-lg border-slate-600 text-cyan-600 focus:ring-2 focus:ring-cyan-500 focus:ring-offset-0 bg-slate-700 cursor-pointer transition-transform hover:scale-110">
                <label for="active-checkbox" class="text-base font-semibold text-slate-200 cursor-pointer">
                  Regla activa
                </label>
              </div>
            </div>

            <div class="space-y-2">
              <label class="block text-sm font-bold text-slate-200 mb-3">Orden de visualización</label>
              <input type="number" 
                     wire:model.defer="sort_order" 
                     class="w-full px-5 py-4 bg-slate-800/50 border border-slate-600/50 focus:border-cyan-500/50 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 transition-all text-lg"
                     min="0"
                     placeholder="0">
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-end gap-4 pt-6 border-t border-slate-700/50">
            <button type="button" 
                    wire:click="closeModal" 
                    class="px-6 py-3 border-2 border-slate-600/50 text-slate-300 hover:bg-slate-700/50 hover:border-slate-500 rounded-xl transition-all duration-300 font-semibold hover:-translate-y-0.5">
              Cancelar
            </button>
            <button type="submit" 
                    class="group px-8 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white rounded-xl transition-all duration-300 font-bold shadow-xl shadow-cyan-500/30 hover:shadow-2xl hover:shadow-cyan-500/40 disabled:opacity-50 disabled:cursor-not-allowed hover:-translate-y-0.5"
                    wire:loading.attr="disabled">
              <span wire:loading.remove class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $editingId ? 'Guardar Cambios' : 'Crear Regla' }}
              </span>
              <span wire:loading class="inline-flex items-center gap-2">
                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Guardando...
              </span>
            </button>
          </div>
        </form>

      </div>
    </div>
  @endif
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes fadeInUp {
  from { 
    opacity: 0;
    transform: translateY(30px);
  }
  to { 
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}

.animate-fadeInUp {
  animation: fadeInUp 0.6s ease-out forwards;
  opacity: 0;
}

.animate-slideDown {
  animation: slideDown 0.4s ease-out;
}

.animate-scaleIn {
  animation: scaleIn 0.3s ease-out;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Prose styles */
.prose-sm p {
  margin-top: 0.25rem;
  margin-bottom: 0.25rem;
}

.prose-sm ul, .prose-sm ol {
  margin-top: 0.5rem;
  margin-bottom: 0.5rem;
  padding-left: 1.5rem;
}

.prose-sm strong {
  color: #fff;
  font-weight: 700;
}

.prose-sm code {
  background: rgba(100, 116, 139, 0.2);
  padding: 0.125rem 0.375rem;
  border-radius: 0.25rem;
  font-size: 0.875em;
}

/* Custom scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(30, 41, 59, 0.5);
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(71, 85, 105, 0.8);
  border-radius: 4px;
  transition: background 0.3s;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(100, 116, 139, 0.9);
}
</style>