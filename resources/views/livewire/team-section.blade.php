<div class="max-w-6xl mx-auto py-16 px-6">
  <div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-bold">Conocé Nuestro Equipo</h2>

    @can('edit pages')
      <a href="{{ route('admin.team.index') }}" class="px-3 py-1 border rounded">Administrar Equipo</a>
    @endcan
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach($members as $member)
        <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg flex flex-col">
            
            <!-- CONTENEDOR DE IMAGEN -->
            <div class="w-full aspect-square bg-gray-100 dark:bg-gray-700 flex items-center justify-center p-3">
                @if(!empty($member['photo_url']))
                    <img 
                        src="{{ $member['photo_url'] }}" 
                        alt="{{ $member['name'] }}"
                        class="max-w-full max-h-full object-contain"
                        loading="lazy"
                    >
                @else
                    <div class="text-gray-500 text-sm">Sin foto</div>
                @endif
            </div>

            <!-- CONTENIDO -->
            <div class="p-4 flex-1 flex flex-col">
                <h3 class="font-semibold text-lg">{{ $member['name'] }}</h3>

                @if(!empty($member['role']))
                    <p class="text-sm text-gray-500">{{ $member['role'] }}</p>
                @endif

                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    {!! nl2br(e($member['description'])) !!}
                </p>
            </div>

        </div>
    @endforeach
</div>
