<div class="max-w-6xl mx-auto px-6 py-8">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-cyan-100">Administrar Reglas</h2>
    <div class="flex gap-2">
      <button wire:click="create" class="px-4 py-2 bg-cyan-600 text-white rounded">Nueva Regla</button>
      <a href="{{ route('about-us') }}" class="px-4 py-2 border rounded text-cyan-300">Ver Landing</a>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-4 text-green-400">{{ session('success') }}</div>
  @endif

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($rules as $r)
      <div class="p-4 bg-gray-800 rounded-lg border border-cyan-500/20">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="font-semibold text-cyan-100">{{ $r['title'] }}</h3>
            <p class="text-sm text-gray-400">Orden: {{ $r['sort_order'] }}</p>
          </div>
          <div class="flex gap-2">
            <button wire:click="edit({{ $r['id'] }})" class="px-3 py-1 bg-cyan-600/20 text-cyan-300 rounded">Editar</button>
            <button wire:click="delete({{ $r['id'] }})" onclick="return confirm('Eliminar regla?')" class="px-3 py-1 bg-red-600/20 text-red-300 rounded">Eliminar</button>
          </div>
        </div>

        <div class="mt-3 text-sm text-gray-300">
          {!! \Illuminate\Support\Str::markdown($r['content'] ?? '') !!}
        </div>

        <div class="mt-3 text-xs">
          <span class="px-2 py-1 rounded {{ $r['active'] ? 'bg-green-600/20 text-green-300' : 'bg-gray-600/20 text-gray-300' }}">
            {{ $r['active'] ? 'Activo' : 'Inactivo' }}
          </span>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Modal --}}
  @if($showModal)
    <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-800 rounded-lg w-full max-w-2xl p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold text-cyan-100">{{ $editingId ? 'Editar Regla' : 'Nueva Regla' }}</h3>
          <button wire:click="closeModal" class="text-gray-400">×</button>
        </div>

        <form wire:submit.prevent="save" novalidate>
          <label class="block mb-3">
            <span class="text-sm text-gray-300">Título *</span>
            <input type="text" wire:model.defer="title" class="w-full mt-1 p-2 bg-gray-700 border border-cyan-500/20 rounded text-white" required>
            @error('title') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror
          </label>

          <label class="block mb-3">
            <span class="text-sm text-gray-300">Contenido (Markdown)</span>
            <textarea wire:model.defer="content" rows="6" class="w-full mt-1 p-2 bg-gray-700 border border-cyan-500/20 rounded text-white"></textarea>
            @error('content') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror
          </label>

          <div class="flex gap-3 items-center">
            <label class="flex items-center gap-2 text-sm">
              <input type="checkbox" wire:model.defer="active" class="rounded"> Activa
            </label>

            <label class="flex items-center gap-2">
              <span class="text-sm text-gray-300">Orden</span>
              <input type="number" wire:model.defer="sort_order" class="w-20 ml-2 p-2 bg-gray-700 border border-cyan-500/20 rounded text-white">
            </label>
          </div>

          <div class="mt-4 flex gap-2">
            <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded" wire:loading.attr="disabled">
              Guardar
            </button>
            <button type="button" wire:click="closeModal" class="px-4 py-2 border rounded text-gray-300">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  @endif
</div>
