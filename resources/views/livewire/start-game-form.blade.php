<div class="bg-gray-800 text-white p-4 rounded my-4">
    <select wire:model="selectedMotivo" class="p-2 rounded w-full mb-2">
        <option value="">Selecciona motivo</option>
        @foreach($motivos as $m)
            <option value="{{ $m->id }}">{{ $m->name }}</option>
        @endforeach
    </select>
    <button wire:click="startGame" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded">
        Iniciar Juego
    </button>
</div>
