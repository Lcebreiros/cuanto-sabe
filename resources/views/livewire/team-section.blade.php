<!--
<div class="max-w-6xl mx-auto py-16 px-6">
  <div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-bold">Conocé Nuestro Equipo</h2>

    @can('edit pages')
      <a href="{{ route('admin.team.index') }}" class="px-3 py-1 border rounded">Administrar Equipo</a>
    @endcan
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach($members as $member)
      <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow">
<div class="w-full aspect-square bg-gray-200 flex items-center justify-center overflow-hidden">
  @if(!empty($member['photo_url']))
    <img 
      src="{{ $member['photo_url'] }}" 
      alt="{{ $member['name'] }}" 
      class="object-contain w-[40%] h-[40%]"
    >
  @else
    <div class="text-gray-500">Sin foto</div>
  @endif
</div>

        <div class="p-4">
          <h3 class="font-semibold">{{ $member['name'] }}</h3>
          @if(!empty($member['role']))<p class="text-sm text-gray-500">{{ $member['role'] }}</p>@endif
          <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">{!! nl2br(e($member['description'])) !!}</p>
        </div>
      </div>
    @endforeach
  </div>
</div>
-->