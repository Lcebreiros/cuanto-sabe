<div class="min-h-screen bg-gradient-to-br from-gray-900 to-indigo-900 p-6">
    <!-- Header -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">
                    Administrar Equipo
                </h1>
                <p class="text-gray-300 mt-2">Gestiona los miembros del equipo que aparecen en la landing page</p>
            </div>

            <div class="flex gap-3">
                <button wire:click="create"
                        type="button"
                        class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-purple-500 text-white rounded-lg font-semibold hover:from-cyan-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-cyan-500/25 flex items-center gap-2"
                        aria-controls="team-list">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Agregar Miembro</span>
                </button>

                <a href="{{ route('about-us') }}"
                   class="px-6 py-3 border border-cyan-400 text-cyan-400 rounded-lg font-semibold hover:bg-cyan-400 hover:text-gray-900 transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3 7h7l-5.5 4 2 7L12 16l-6.5 6-1-7L2 9h7z"/></svg>
                    <span>Ver Landing</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto mb-6 p-4 bg-green-500/10 border border-green-500 rounded-lg text-green-400 flex items-center gap-3">
            <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15 4.293 10.879a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0l9-9a1 1 0 00-1.414-1.414L8.414 15 4.293 10.879z" clip-rule="evenodd" /></svg>
            <span class="sr-only">Éxito</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Grid de Miembros -->
    <div id="team-list" class="max-w-7xl mx-auto">
        {{-- Loading skeleton mientras Livewire hidrata --}}
        <div wire:loading.class.remove="hidden" class="hidden">
            {{-- placeholder (no crítico) --}}
        </div>

        @if(count($members) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($members as $member)
                    <article class="bg-gray-800/50 backdrop-blur-sm rounded-xl border border-cyan-500/20 overflow-hidden hover:border-cyan-400/40 transition-all duration-300 transform hover:scale-105 group" role="article" aria-labelledby="member-{{ $member['id'] }}-name">
                        <!-- Avatar/Foto -->
                        <div class="h-48 bg-gradient-to-br from-cyan-900/30 to-purple-900/30 flex items-center justify-center relative overflow-hidden">
                            @if(!empty($member['photo_url']))
                                <img src="{{ $member['photo_url'] }}"
                                     alt="{{ $member['name'] }}"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="text-center text-gray-400">
                                    <div class="text-6xl mb-2">👤</div>
                                    <p class="text-sm">Sin foto</p>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 to-transparent pointer-events-none"></div>

                            <div class="absolute bottom-3 left-3">
                                <span class="px-2 py-1 bg-cyan-500/20 text-cyan-300 text-xs rounded-full border border-cyan-500/30">
                                    Orden: {{ $member['sort_order'] ?? 0 }}
                                </span>
                            </div>
                        </div>

                        <!-- Información -->
                        <div class="p-4">
                            <h3 id="member-{{ $member['id'] }}-name" class="font-bold text-lg text-cyan-100 mb-1">{{ $member['name'] }}</h3>
                            <p class="text-cyan-400 text-sm font-medium mb-3">{{ $member['role'] ?? 'Sin puesto definido' }}</p>
                            <p class="text-gray-300 text-sm line-clamp-3 mb-4" title="{{ $member['description'] ?? '' }}">
                                {{ \Illuminate\Support\Str::limit($member['description'] ?? '', 120) }}
                            </p>

                            <!-- Acciones -->
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $member['id'] }})"
                                        type="button"
                                        class="flex-1 px-3 py-2 bg-cyan-600/20 text-cyan-300 rounded-lg text-sm font-medium hover:bg-cyan-600/30 transition-colors border border-cyan-500/30 flex items-center justify-center gap-2"
                                        aria-label="Editar {{ $member['name'] }}">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15.232 5.232l3.536 3.536M3 21l6.9-.9L20.7 9.3l-6.9.9L3 21z"/></svg>
                                    <span>Editar</span>
                                </button>

                                <button wire:click="delete({{ $member['id'] }})"
                                        type="button"
                                        onclick="return confirm('¿Estás seguro de eliminar a {{ addslashes($member['name']) }}?')"
                                        class="flex-1 px-3 py-2 bg-red-600/20 text-red-300 rounded-lg text-sm font-medium hover:bg-red-600/30 transition-colors border border-red-500/30 flex items-center justify-center gap-2"
                                        aria-label="Eliminar {{ $member['name'] }}">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 6h18M9 6v12m6-12v12M10 6h4"/></svg>
                                    <span>Eliminar</span>
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <!-- Estado vacío -->
            <div class="text-center py-16">
                <div class="text-8xl mb-4">👥</div>
                <h3 class="text-2xl font-bold text-gray-300 mb-2">No hay miembros en el equipo</h3>
                <p class="text-gray-400 mb-6">Comienza agregando el primer miembro del equipo</p>
                <button wire:click="create"
                        class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-purple-500 text-white rounded-lg font-semibold hover:from-cyan-600 hover:to-purple-600 transition-all duration-300 shadow-lg">
                    Agregar Primer Miembro
                </button>
            </div>
        @endif
    </div>

    <!-- Modal Mejorado -->
    @if($showModal)
        <div
            wire:keydown.escape="closeModal"
            tabindex="0"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-title"
            aria-describedby="modal-desc"
            class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 z-50"
        >
            <div class="bg-gray-800 rounded-2xl border border-cyan-500/30 w-full max-w-2xl max-h-[90vh] overflow-y-auto focus:outline-none">
                <!-- Header del Modal -->
                <header class="p-6 border-b border-cyan-500/20 flex items-start justify-between gap-4">
                    <div>
                        <h3 id="modal-title" class="text-xl font-bold text-cyan-100">
                            {{ $editingId ? 'Editar Miembro' : 'Nuevo Miembro' }}
                        </h3>
                        <p id="modal-desc" class="text-sm text-gray-400 mt-1">Los campos marcados con * son obligatorios.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-white transition-colors" aria-label="Cerrar modal">
                            <span class="text-2xl">×</span>
                        </button>
                    </div>
                </header>

                <!-- Formulario -->
                <form class="p-6" wire:submit.prevent="save" novalidate>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Columna Izquierda -->
                        <div class="space-y-4">
                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-cyan-300 mb-2" for="name">Nombre *</label>
                                <input id="name"
                                       type="text"
                                       wire:model.defer="name"
                                       class="w-full px-4 py-3 bg-gray-700 border border-cyan-500/30 rounded-lg text-white placeholder-gray-400 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition-colors"
                                       placeholder="Ej: Juan Pérez"
                                       required>
                                @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Puesto -->
                            <div>
                                <label class="block text-sm font-medium text-cyan-300 mb-2" for="role">Puesto</label>
                                <input id="role"
                                       type="text"
                                       wire:model.defer="role"
                                       class="w-full px-4 py-3 bg-gray-700 border border-cyan-500/30 rounded-lg text-white placeholder-gray-400 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition-colors"
                                       placeholder="Ej: Desarrollador Frontend">
                                @error('role') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Orden -->
                            <div>
                                <label class="block text-sm font-medium text-cyan-300 mb-2" for="sort_order">Orden de visualización</label>
                                <input id="sort_order"
                                       type="number"
                                       wire:model.defer="sort_order"
                                       class="w-32 px-4 py-3 bg-gray-700 border border-cyan-500/30 rounded-lg text-white focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition-colors">
                                @error('sort_order') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Columna Derecha -->
                        <div class="space-y-4">
                            <!-- Descripción -->
                            <div>
                                <label class="block text-sm font-medium text-cyan-300 mb-2" for="description">Descripción</label>
                                <textarea id="description"
                                          wire:model.defer="description"
                                          rows="4"
                                          class="w-full px-4 py-3 bg-gray-700 border border-cyan-500/30 rounded-lg text-white placeholder-gray-400 focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition-colors"
                                          placeholder="Breve descripción del miembro..."></textarea>
                                @error('description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Foto -->
                            <div>
                                <label class="block text-sm font-medium text-cyan-300 mb-2" for="photo">Foto del miembro</label>
                                <input id="photo"
                                       type="file"
                                       wire:model="photo"
                                       accept="image/*"
                                       class="w-full px-4 py-2 bg-gray-700 border border-cyan-500/30 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-cyan-500 file:text-white hover:file:bg-cyan-600 transition-colors">
                                @error('photo') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror

                                @if ($photo)
                                    <div class="mt-3 p-3 bg-gray-700/50 rounded-lg">
                                        <p class="text-sm text-cyan-300 mb-2">Vista previa:</p>
                                        <img src="data:image/{{ $photo->extension() }};base64,{{ base64_encode(file_get_contents($photo->getRealPath())) }}"
     alt="Preview"
     loading="lazy"
     class="w-32 h-32 object-cover rounded-lg border border-cyan-500/30">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex gap-3 mt-8 pt-6 border-t border-cyan-500/20">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:target="save, photo"
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-cyan-500 to-purple-500 text-white rounded-lg font-semibold hover:from-cyan-600 hover:to-purple-600 transition-all duration-300 shadow-lg disabled:opacity-60"
                                aria-label="{{ $editingId ? 'Actualizar miembro' : 'Crear miembro' }}">
                            <span wire:loading.remove wire:target="save, photo">
                                {{ $editingId ? 'Actualizar Miembro' : 'Crear Miembro' }}
                            </span>
                            <span wire:loading wire:target="save, photo" class="inline-flex items-center gap-2">
                                <svg class="animate-spin w-5 h-5" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25" stroke-width="4"/><path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
                                Guardando...
                            </span>
                        </button>

                        <button type="button"
                                wire:click="closeModal"
                                class="px-6 py-3 border border-cyan-400 text-cyan-400 rounded-lg font-semibold hover:bg-cyan-400 hover:text-gray-900 transition-all duration-300"
                                aria-label="Cancelar edición">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
